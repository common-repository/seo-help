<?php 
defined('ABSPATH') or die("You can't access this file directly.");
/*
 * Parts of the code are inspired and/or copied from FEEDZY RSS Aggregator which is an open-source project.
 */


// RSS Cron
add_action( 'init', 'qcld_seo_help_add_cron' );
add_action( 'qcld_seo_help_cron', 'qcld_seo_help_run_cron' );
if ( ! function_exists( 'qcld_seo_help_add_cron' ) ) {
    function qcld_seo_help_add_cron() {

        $qcld_seo_help_cron_execution = get_option('qcld_seo_help_cron_execution');
        $qcld_seo_help_cron_schedule = get_option('qcld_seo_help_cron_schedule');
        $time     = ! empty( $qcld_seo_help_cron_execution ) ?  $qcld_seo_help_cron_execution : time();
        $schedule = ! empty( $qcld_seo_help_cron_schedule ) ? $qcld_seo_help_cron_schedule : 'hourly';

        if ( ! empty( $qcld_seo_help_cron_execution ) && ! empty( $qcld_seo_help_cron_schedule )  ) {
            $execution = isset( $_POST['qcld_seo_help_cron_execution'] ) ? sanitize_text_field( wp_unslash( $_POST['qcld_seo_help_cron_execution'] ) ) : $schedule;
            $offset    = isset( $_POST['qcld_seo_help_execution_offset'] ) ? sanitize_text_field( wp_unslash( $_POST['qcld_seo_help_execution_offset'] ) ) : 0;
            //$time      = qcld_seo_help_get_cron_execution( $execution, $offset );
            $schedule  = isset( $_POST['qcld_seo_help_cron_schedule'] ) ? sanitize_text_field( wp_unslash( $_POST['qcld_seo_help_cron_schedule'] ) ) : 'hourly';
            wp_clear_scheduled_hook( 'qcld_seo_help_run_cron' );
        }
        
        if ( false === wp_next_scheduled( 'qcld_seo_help_run_cron' ) ) {
            wp_schedule_event( $time, $schedule, 'qcld_seo_help_run_cron' );
        }
    }
}

if ( ! function_exists( 'qcld_seo_help_get_cron_execution' ) ) {
    function qcld_seo_help_get_cron_execution( $execution, $offset = 0 ) {
        if ( empty( $offset ) && ! empty( $qcld_seo_help_execution_offset ) ) {
            $offset = $qcld_seo_help_execution_offset;
        }
        $execution = strtotime( $execution ) ? strtotime( $execution ) + ( HOUR_IN_SECONDS * $offset ) : time() + ( HOUR_IN_SECONDS * $offset );
        return $execution;
    }
}

add_action( 'qcld_seo_help_run_cron', 'qcld_seo_help_run_cron' );
if ( ! function_exists( 'qcld_seo_help_run_cron' ) ) {
    function qcld_seo_help_run_cron( $max = 100 ) {
        if ( empty( $max ) ) {
            $max = 10;
        }
        global $post;
        $args           = array(
            'post_type'   => 'qcld_rss_imports',
            'post_status' => 'publish',
            'numberposts' => 99,
        );
        $qcld_seo_help_imports = get_posts( $args );
        foreach ( $qcld_seo_help_imports as $post ) {
            $result = qcld_seo_help_run_job( $post, $max );
            if ( empty( $result ) ) {
                qcld_seo_help_run_job( $post, $max );
            }
            do_action( 'qcld_seo_help_run_cron_extra', $job );
        }
    }
}


if ( ! function_exists( 'qcld_seo_help_run_job' ) ) {
    function qcld_seo_help_run_job( $post, $max ){

        $id  = isset( $post->ID ) ? $post->ID : '';
        global $post;

        $job = get_post($id);

        $qcld_seo_help_website_url = get_post_meta( $id, 'qcld_seo_help_website_url', true );

        $source                   = get_post_meta( $id, 'qcld_seo_help_website_url', true );
        $inc_key                  = get_post_meta( $id, 'inc_key', true );
        $exc_key                  = get_post_meta( $id, 'exc_key', true );
        $inc_on                   = get_post_meta( $id, 'inc_on', true );
        $exc_on                   = get_post_meta( $id, 'exc_on', true );
        $import_title             = get_post_meta( $id, 'import_post_title', true );
        $import_title             = qcld_seo_help_import_trim_tags( $import_title );
        $import_date              = get_post_meta( $id, 'import_post_date', true );
        $post_excerpt             = get_post_meta( $id, 'import_post_excerpt', true );
        $post_excerpt             = qcld_seo_help_import_trim_tags( $post_excerpt );
        $import_content           = get_post_meta( $id, 'import_post_content', true );
        $import_featured_img      = get_post_meta( $id, 'import_post_featured_img', true );
        $import_post_type         = get_post_meta( $id, 'import_post_type', true );
        $import_post_term         = get_post_meta( $id, 'import_post_term', true );
        $import_feed_limit        = get_post_meta( $id, 'import_feed_limit', true );
        $import_item_img_url      = get_post_meta( $id, 'qcld_seo_help_import_use_external_image', true );
        $import_post_tags         = get_post_meta( $id, 'import_post_tags', true );
        $import_remove_duplicates = get_post_meta( $id, 'import_remove_duplicates', true );
        $import_selected_language = get_post_meta( $id, 'language', true );
        $from_datetime            = get_post_meta( $id, 'from_datetime', true );
        $to_datetime              = get_post_meta( $id, 'to_datetime', true );
        $import_auto_translation  = get_post_meta( $id, 'import_auto_translation', true );
        $import_auto_translation  = 'yes' === $import_auto_translation ? true : false;
        $import_translation_lang  = get_post_meta( $id, 'import_auto_translation_lang', true );
        $max                      = $import_feed_limit;

        if ( metadata_exists( $import_post_type, $id, 'import_post_status' ) ) {
            $import_post_status = get_post_meta( $id, 'import_post_status', true );
        } else {
            add_post_meta( $id, 'import_post_status', 'publish' );
            $import_post_status = get_post_meta( $id, 'import_post_status', true );
        }

        // the array of imported items that uses the old scheme of custom hashing the url and date
        $imported_items     = array();
        $imported_items_old = get_post_meta( $id, 'imported_items', true );
        if ( ! is_array( $imported_items_old ) ) {
            $imported_items_old = array();
        }

        // the array of imported items that uses the new scheme of SimplePie's hash/id
        $imported_items_new = get_post_meta( $id, 'imported_items_hash', true );
        if ( ! is_array( $imported_items_new ) ) {
            $imported_items_new = array();
        }

        // Get default thumbnail ID.
        $default_thumbnail = 0;
        

        // Note: this implementation will only work if only one of the fields is allowed to provide
        // the date, because if the title can have UTC date and content can have local date then it
        // all goes sideways.
        // also if the user provides multiple date types, local will win.
        $meta = 'yes';
        if ( strpos( $import_title, '[#item_date_local]' ) !== false ) {
            $meta = 'author, date, time, tz=local';
        } elseif ( strpos( $import_title, '[#item_date_feed]' ) !== false ) {
            $meta = 'author, date, time, tz=no';
        }

        $options = apply_filters(
            'qcld_seo_shortcode_options',
            array(
                'feeds'           => $source,
                'max'             => $max,
                'feed_title'      => 'no',
                'target'          => '_blank',
                'title'           => '',
                'meta'            => $meta,
                'summary'         => 'yes',
                'summarylength'   => '',
                'thumb'           => 'auto',
                'default'         => '',
                'size'            => '250',
                'keywords_inc'    => $inc_key, // this is not keywords_title
                'keywords_ban'    => $exc_key, // to support old pro that does not support keywords_exc
                'keywords_exc'    => $exc_key, // this is not keywords_ban
                'keywords_inc_on' => $inc_on,
                'keywords_exc_on' => $exc_on,
                'columns'         => 1,
                'offset'          => 0,
                'multiple_meta'   => 'no',
                'refresh'         => '55_mins',
                'from_datetime'   => $from_datetime,
                'to_datetime'     => $to_datetime,
            ),
            $job
        );

        //$admin   = qcld_seo_help_Rss_Feeds::instance()->get_admin();
        $options = qcld_seo_help_sanitize_attr( $options, $source );

        $options['__jobID'] = $id;

        $last_run = time();
        update_post_meta( $id, 'last_run', $last_run );
        // we will use this last_run_id to associate imports with a specific job run.
        update_post_meta( $id, 'last_run_id', $last_run );
        delete_post_meta( $id, 'import_errors' );
        delete_post_meta( $id, 'import_info' );

        // let's increase this time in case spinnerchief/wordai is being used.
        set_time_limit( apply_filters( 'qcld_seo_max_execution_time', 500 ) );

        $count = $index = $import_image_errors = $duplicates = 0;

        // the array that captures errors about the import.
        $import_errors = array();

        // the array that captures additional information about the import.
        $import_info   = array();
        $results       = qcld_seo_help_get_job_feed( $options, $import_content, true );

        $xml_results = '';
        if ( str_contains( $import_content, '_full_content' ) ) {
            $xml_results = qcld_seo_help_get_job_feed( $options, '[#item_content]', true );
        }

        if ( is_wp_error( $results ) ) {
            $import_errors[] = $results->get_error_message();
            update_post_meta( $id, 'import_errors', $import_errors );
            update_post_meta( $id, 'imported_items_count', 0 );
            
            $msg =   __( 'Nothing imported!', 'qcld-seo-help' );
            $msg .= ' (' . __( 'Refresh this page for the updated status', 'qcld-seo-help' ) . ')';

            wp_send_json_success( array( 'msg' => $msg, 'import_success' => $count > 0 ) );

            //return;
        }

        $result = $results['items'];
        do_action( 'qcld_seo_help_run_job_pre', $job, $result );

        // check if we should be using the old scheme of custom hashing the url and date
        // or the new scheme of depending on SimplePie's hash/id
        // basically if the old scheme hasn't be used before, use the new scheme
        // BUT if the old scheme has been used, continue with it.
        $use_new_hash   = empty( $imported_items_old );
        $imported_items = $use_new_hash ? $imported_items_new : $imported_items_old;

        $start_import = true;
        // bail if both title and content are empty because the post will not be created.
        if ( empty( $import_title ) && empty( $import_content ) ) {
            $import_errors[] = __( 'Title & Content are both empty.', 'qcld-seo-help' );
            $start_import    = false;
        }

        if ( ! $start_import ) {
            update_post_meta( $id, 'import_errors', $import_errors );

            $msg =   __( 'Nothing imported!', 'qcld-seo-help' );
            $msg .= ' (' . __( 'Refresh this page for the updated status', 'qcld-seo-help' ) . ')';

            wp_send_json_success( array( 'msg' => $msg, 'import_success' => $count > 0 ) );

           // return 0;
        }

        $rewrite_service_endabled = true;
        

        $duplicates       = $items_found = array();
        $found_duplicates = array();
        foreach ( $result as $key => $item ) {
            $item_obj = $item;
            // find item index key when import full content.
            if ( ! empty( $xml_results ) ) {
                $item_unique_hash = array_column( $xml_results['items'], 'item_unique_hash' );
                $real_index_key   = array_search( $item['item_unique_hash'], $item_unique_hash, true );
                if ( isset( $xml_results['items'][ $real_index_key ] ) ) {
                    $item_obj = $xml_results['items'][ $real_index_key ];
                }
            }


            $item_hash                        = $use_new_hash ? $item['item_id'] : hash( 'sha256', $item['item_url'] . '_' . $item['item_date'] );
            $is_duplicate                     = $use_new_hash ? in_array( $item_hash, $imported_items_new, true ) : in_array( $item_hash, $imported_items_old, true );
            $items_found[ $item['item_url'] ] = $item['item_title'];

            if ( 'yes' === $import_remove_duplicates && ! $is_duplicate ) {
                $is_duplicate_post = qcld_seo_help_is_duplicate_post( $import_post_type, 'qcld_seo_help_item_url', esc_url_raw( $item['item_url'] ) );
                if ( ! empty( $is_duplicate_post ) ) {
                    foreach ( $is_duplicate_post as $p ) {
                        $found_duplicates[] = get_post_meta( $p, 'qcld_seo_help_item_url', true );
                        wp_delete_post( $p, true );
                    }
                }
            }
            if ( $is_duplicate ) {
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Ignoring %s as it is a duplicate (%s hash).', $item_hash, $use_new_hash ? 'new' : 'old' ), 'warn', __FILE__, __LINE__ );
                $index ++;
                $duplicates[ $item['item_url'] ] = $item['item_title'];
                continue;
            }

            $author = '';
            if ( $item['item_author'] ) {
                if ( is_string( $item['item_author'] ) ) {
                    $author = $item['item_author'];
                } elseif ( is_object( $item['item_author'] ) ) {
                    $author = $item['item_author']->get_name();
                    if ( empty( $author ) ) {
                        $author = $item['item_author']->get_email();
                    }
                }
            } else {
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Author is empty for %s.', $item['item_title'] ), 'warn', __FILE__, __LINE__ );
            }

            // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
            $item_date = date( get_option( 'date_format' ) . ' at ' . get_option( 'time_format' ), $item['item_date'] );
            $item_date = $item['item_date_formatted'];

            // Get translated item title.
            $translated_title = '';
            if ( $import_auto_translation && ( false !== strpos( $import_title, '[#translated_title]' ) || false !== strpos( $post_excerpt, '[#translated_title]' ) ) ) {
                $translated_title = apply_filters( 'qcld_seo_help_invoke_auto_translate_services', $item['item_title'], '[#translated_title]', $import_translation_lang, $job, $language_code, $item );
            }

            $post_title = str_replace(
                array(
                    '[#item_title]',
                    '[#item_author]',
                    '[#item_date]',
                    '[#item_date_local]',
                    '[#item_date_feed]',
                    '[#item_source]',
                    '[#translated_title]',
                ),
                array(
                    $item['item_title'],
                    $author,
                    $item_date,
                    $item_date,
                    $item_date,
                    $item['item_source'],
                    $translated_title,
                ),
                $import_title
            );

           // if ( $this->qcld_seo_help_is_business() ) {
                $post_title = apply_filters( 'qcld_seo_help_parse_custom_tags', $post_title, $item_obj );
           // }

            $post_title = apply_filters( 'qcld_seo_help_invoke_services', $post_title, 'title', $item['item_title'], $job );

            // Get translated item link text.
            $item_link_txt = __( 'Read More', 'qcld-seo-help' );
            if ( $import_auto_translation && false !== strpos( $import_content, '[#item_url]' ) ) {
                $item_link_txt = apply_filters( 'qcld_seo_help_invoke_auto_translate_services', $item_link_txt, '[#item_url]', $import_translation_lang, $job, $language_code, $item );
            }

            $item_link = '<a href="' . $item['item_url'] . '" target="_blank" class="qcld_seo_help-rss-link-icon">' . $item_link_txt . '</a>';

            // Rewriter item title from qcld_seo_help API.
            if ( $rewrite_service_endabled && false !== strpos( $post_title, '[#title_qcld_seo_help_rewrite]' ) ) {
                $title_qcld_seo_help_rewrite = apply_filters( 'qcld_seo_help_invoke_content_rewrite_services', $item['item_title'], '[#title_qcld_seo_help_rewrite]', $job, $item );
                $post_title           = str_replace( '[#title_qcld_seo_help_rewrite]', $title_qcld_seo_help_rewrite, $post_title );
            }

            $item_link = '<a href="' . $item['item_url'] . '" target="_blank" class="qcld_seo_help-rss-link-icon">' . __( 'Read More', 'qcld-seo-help' ) . '</a>';

            $image_html = '';
            if ( ! empty( $item['item_img_path'] ) ) {
                $image_html = '<img src="' . $item['item_img_path'] . '" title="' . $item['item_title'] . '" />';
            }

            // Get translated item description.
            $translated_description = '';
            if ( $import_auto_translation && ( false !== strpos( $import_content, '[#translated_description]' ) || false !== strpos( $post_excerpt, '[#translated_description]' ) ) ) {
                $translated_description = apply_filters( 'qcld_seo_help_invoke_auto_translate_services', $item['item_full_description'], '[#translated_description]', $import_translation_lang, $job, $language_code, $item );
            }

            // Get translated item content.
            $translated_content = '';
            if ( $import_auto_translation && ( false !== strpos( $import_content, '[#translated_content]' ) || false !== strpos( $post_excerpt, '[#translated_content]' ) ) ) {
                $translated_content = ! empty( $item['item_content'] ) ? $item['item_content'] : $item['item_description'];
                $translated_content = apply_filters( 'qcld_seo_help_invoke_auto_translate_services', $translated_content, '[#translated_content]', $import_translation_lang, $job, $language_code, $item );
            }

            // Used as a new line character in import content.
            $import_content = rawurldecode( $import_content );
            $import_content = str_replace( PHP_EOL, "\r\n", $import_content );
            $import_content = trim( $import_content );

            $post_content = str_replace(
                array(
                    '[#item_description]',
                    '[#item_content]',
                    '[#item_image]',
                    '[#item_url]',
                    '[#item_categories]',
                    '[#item_source]',
                    '[#translated_description]',
                    '[#translated_content]',
                    '[#item_price]',
                    '[#item_author]',
                ),
                array(
                    $item['item_description'],
                    ! empty( $item['item_content'] ) ? $item['item_content'] : $item['item_description'],
                    $image_html,
                    $item_link,
                    $item['item_categories'],
                    $item['item_source'],
                    $translated_description,
                    $translated_content,
                    ! empty( $item['item_price'] ) ? $item['item_price'] : '',
                    $author,
                ),
                $import_content
            );

            //if ( $this->qcld_seo_help_is_business() ) {
                $full_content = ! empty( $item['item_full_content'] ) ? $item['item_full_content'] : $item['item_content'];
                if ( str_contains( $import_content, '_full_content' ) ) {
                    // if full content is empty, log a message
                    if ( empty( $full_content ) ) {
                        // let's see if there is an error.
                        $full_content_error = isset( $item['full_content_error'] ) && ! empty( $item['full_content_error'] ) ? $item['full_content_error'] : '';
                        if ( empty( $full_content_error ) ) {
                            $full_content_error = __( 'Unknown', 'qcld-seo-help' );
                        }
                        $import_errors[] = sprintf( __( 'Full content is empty. Error: %s', 'qcld-seo-help' ), $full_content_error );
                    }

                    $post_content = str_replace(
                        array(
                            '[#item_full_content]',
                        ),
                        array(
                            $full_content,
                        ),
                        $post_content
                    );
                }
                $post_content = apply_filters( 'qcld_seo_help_invoke_services', $post_content, 'full_content', $full_content, $job );
            //}
            // Item content action.
            //$content_action = qcld_seo_help_handle_content_actions( $post_content, 'item_content' );
           // $post_content   = $content_action->get_tags();
            // Item content action process.
           // $post_content = $content_action->run_action_job( $post_content, $import_translation_lang, $job, $language_code, $item );
            // Parse custom tags.
           // if ( $this->qcld_seo_help_is_business() ) {
                $post_content = apply_filters( 'qcld_seo_help_parse_custom_tags', $post_content, $item_obj );
            //}

            $post_content = apply_filters( 'qcld_seo_help_invoke_services', $post_content, 'content', $item['item_description'], $job );

            // Translate full-content.
            if ( $import_auto_translation && false !== strpos( $post_content, '[#translated_full_content]' ) ) {
                $translated_full_content = apply_filters( 'qcld_seo_help_invoke_auto_translate_services', $item['item_url'], '[#translated_full_content]', $import_translation_lang, $job, $language_code, $item );
                $post_content            = str_replace( '[#translated_full_content]', rtrim( $translated_full_content, '.' ), $post_content );
            }
            // Rewriter item content from qcld_seo_help API.
            if ( $rewrite_service_endabled && false !== strpos( $post_content, '[#content_qcld_seo_help_rewrite]' ) ) {
                $item_content           = ! empty( $item['item_content'] ) ? $item['item_content'] : $item['item_description'];
                $content_qcld_seo_help_rewrite = apply_filters( 'qcld_seo_help_invoke_content_rewrite_services', $item_content, '[#content_qcld_seo_help_rewrite]', $job, $item );
                $post_content           = str_replace( '[#content_qcld_seo_help_rewrite]', $content_qcld_seo_help_rewrite, $post_content );
            }

            // Rewriter item full content from qcld_seo_help API.
            if ( $rewrite_service_endabled && false !== strpos( $post_content, '[#full_content_qcld_seo_help_rewrite]' ) ) {
                $full_content_qcld_seo_help_rewrite = apply_filters( 'qcld_seo_help_invoke_content_rewrite_services', $item['item_url'], '[#full_content_qcld_seo_help_rewrite]', $job, $item );
                $post_content                = str_replace( '[#full_content_qcld_seo_help_rewrite]', $full_content_qcld_seo_help_rewrite, $post_content );
            }

            // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
            $item_date = date( 'Y-m-d H:i:s', $item['item_date'] );
            // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
            $now = date( 'Y-m-d H:i:s' );
            if ( trim( $import_date ) === '' ) {
                $post_date = $now;
            }
            $post_date = str_replace( '[#item_date]', $item_date, $import_date );
            $post_date = str_replace( '[#post_date]', $now, $post_date );

            if ( ! defined( 'qcld_seo_help_ALLOW_UNSAFE_HTML' ) || ! qcld_seo_help_ALLOW_UNSAFE_HTML ) {
                $post_content = wp_kses( $post_content, apply_filters( 'qcld_seo_help_wp_kses_allowed_html', array() ) );

                if ( ! function_exists( 'use_block_editor_for_post_type' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/post.php';
                }

                if ( function_exists( 'use_block_editor_for_post_type' ) && use_block_editor_for_post_type( $import_post_type ) ) {
                    $post_content = ! empty( $post_content ) ? '<!-- wp:html -->' . trim( force_balance_tags( wpautop( $post_content, 'br' ) ) ) . '<!-- /wp:html -->' : $post_content;
                    $post_content = trim( $post_content );
                }
            }

            if ( isset( $rewrite_content_openai ) && $rewrite_content_openai == "1" ) {
               
                // generate  content by openai hook
                $post_contents = apply_filters( 'qcld_seo_get_post_content_generate', $post_title );

                $post_content = !empty( $post_contents ) ? $post_contents : $post_content;
            }

            $item_post_excerpt = str_replace(
                array(
                    '[#item_title]',
                    '[#item_content]',
                    '[#item_description]',
                    '[#translated_title]',
                    '[#translated_content]',
                    '[#translated_description]',
                ),
                array(
                    $post_title,
                    $post_content,
                    $item['item_description'],
                    $translated_title,
                    $translated_content,
                    $translated_description,
                ),
                $post_excerpt
            );

           // if ( $this->qcld_seo_help_is_business() ) {
                $item_post_excerpt = apply_filters( 'qcld_seo_help_parse_custom_tags', $item_post_excerpt, $item_obj );
            //}

            $new_post = apply_filters(
                'qcld_seo_help_insert_post_args',
                array(
                    'post_type'    => $import_post_type,
                    'post_title'   => wp_kses( $post_title, apply_filters( 'qcld_seo_help_wp_kses_allowed_html', array() ) ),
                    'post_content' => $post_content,
                    'post_date'    => $post_date,
                    'post_status'  => $import_post_status,
                    'post_excerpt' => $item_post_excerpt,
                ),
                $item_obj,
                $post_title,
                $post_content,
                $item_post_excerpt,
                $index,
                $job
            );

            // no point creating a post if either the title or the content is null.
            if ( is_null( $post_title ) || is_null( $post_content ) ) {
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'NOT creating a new post as title (%s) or content (%s) is null.', $post_title, $post_content ), 'info', __FILE__, __LINE__ );
                $index ++;
                $import_errors[] = __( 'Title or Content is empty.', 'qcld-seo-help' );
                continue;
            }

            if ( 'attachment' === $import_post_type ) {
                $image_url       = '';
                $img_success     = true;
                $new_post_id     = 0;
                $default_img_tag = ! empty( $import_featured_img ) ? '[#item_image]' : '';

                // image tag
                if ( strpos( $default_img_tag, '[#item_image]' ) !== false ) {
                    // image exists in item
                    if ( ! empty( $item['item_img_path'] ) ) {
                        $image_url = str_replace( '[#item_image]', $item['item_img_path'], $default_img_tag );
                    } else {
                        $img_success = false;
                    }
                } elseif ( strpos( $default_img_tag, '[#item_custom' ) !== false ) {
                    // custom image tag
                    //if ( $this->qcld_seo_help_is_business() || $this->qcld_seo_help_is_personal() ) {
                        $value = apply_filters( 'qcld_seo_help_parse_custom_tags', $default_img_tag, $item_obj );
                    //}

                    if ( ! empty( $value ) && strpos( $value, '[#item_custom' ) === false ) {
                        $image_url = $value;
                    } else {
                        $img_success = false;
                    }
                } else {
                    $image_url = $default_img_tag;
                }

                if ( ! empty( $image_url ) ) {
                    $img_success = qcld_seo_help_generate_featured_image( $image_url, 0, $item['item_title'], $import_errors, $import_info, $new_post );
                    $new_post_id = $img_success;
                }

                if ( ! $img_success ) {
                    $import_image_errors ++;
                }
            } else {
                $new_post_id = wp_insert_post( $new_post, true );
            }

            // Set post language.
            if ( function_exists( 'pll_set_post_language' ) && ! empty( $import_selected_language ) ) {
                pll_set_post_language( $new_post_id, $import_selected_language );
            } elseif ( function_exists( 'icl_get_languages' ) && ! empty( $import_selected_language ) ) {
                $this->set_wpml_element_language_details( $import_post_type, $new_post_id, $import_selected_language );
            }

            if ( $new_post_id === 0 || is_wp_error( $new_post_id ) ) {
                $error_reason = 'N/A';
                if ( is_wp_error( $new_post_id ) ) {
                    $error_reason = $new_post_id->get_error_message();
                    if ( ! empty( $error_reason ) ) {
                        $import_errors[] = $error_reason;
                    }
                }
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Unable to create a new post with params %s. Error: %s', print_r( $new_post, true ), $error_reason ), 'error', __FILE__, __LINE__ );
                $index ++;
                continue;
            }
            do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'created new post with ID %d with post_content %s', $new_post_id, $post_content ), 'debug', __FILE__, __LINE__ );
            if ( ! in_array( $item_hash, $found_duplicates, true ) ) {
                $imported_items[] = $item_hash;
                $count ++;
            }

            if ( $import_post_term !== '' ) {
                //var_dump( $import_post_term );
                //wp_die();
                // let's get the slug of the uncategorized category, even if it renamed.
                $uncategorized    = get_category( 1 );
                $terms            = explode( ',', $import_post_term ?? '' );
                $terms            = array_filter(
                    $terms,
                    function( $term ) {
                        if ( empty( $term ) ) {
                            return;
                        }
                        if ( false !== strpos( $term, '[#item_' ) ) {
                            return;
                        }
                        return $term;
                    }
                );
                $default_category = (int) get_option( 'default_category' );
                foreach ( $terms as $term ) {
                    // this handles both x_2, where 2 is the term id and x is the taxonomy AND x_2_3_4 where 4 is the term id and the taxonomy name is "x 2 3 4".
                    $array    = explode( '_', $term ?? '' );
                    $term_id  = array_pop( $array );
                    $taxonomy = implode( '_', $array );

                    // uncategorized
                    // 1. may be the unmodified category ID 1
                    // 2. may have been recreated ('uncategorized') and may have a different slug in different languages.
                    if ( $default_category === $uncategorized->term_id ) {
                        wp_remove_object_terms(
                            $new_post_id, apply_filters(
                                'qcld_seo_help_uncategorized', array(
                                    1,
                                    'uncategorized',
                                    $uncategorized->slug,
                                ), $id
                            ), 'category'
                        );
                    }
                }
            
                $taxonomy = 'category';

                $result = wp_set_object_terms( $new_post_id, intval( $import_post_term ), $taxonomy, true );
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'After creating post in %s/%d, result = %s', $taxonomy, $import_post_term, print_r( $result, true ) ), 'debug', __FILE__, __LINE__ );
            }


            if ( isset( $import_post_tags ) && !empty( $import_post_tags ) ) {

                $import_post_tag    = explode(',', $import_post_tags );
                $taxonomy_tag       = 'post_tag';

                wp_set_object_terms( $new_post_id, $import_post_tag, $taxonomy_tag );
                
            }

            do_action( 'qcld_seo_help_import_extra', $job, $item_obj, $new_post_id, $import_errors, $import_info );

            $default_img_tag = ! empty( $import_featured_img ) ? '[#item_image]' : '';
            if ( ! empty( $default_img_tag ) && 'attachment' !== $import_post_type ) {
                $image_url   = '';
                $img_success = true;

                // image tag
                if ( strpos( $default_img_tag, '[#item_image]' ) !== false ) {
                    // image exists in item
                    if ( ! empty( $item['item_img_path'] ) ) {
                        $image_url = str_replace( '[#item_image]', $item['item_img_path'], $default_img_tag );
                    } else {
                        $img_success = false;
                    }
                } elseif ( strpos( $default_img_tag, '[#item_custom' ) !== false ) {
                    // custom image tag
                    if ( $this->qcld_seo_help_is_business() || $this->qcld_seo_help_is_personal() ) {
                        $value = apply_filters( 'qcld_seo_help_parse_custom_tags', $default_img_tag, $item_obj );
                    }
                    if ( ! empty( $value ) && strpos( $value, '[#item_custom' ) === false ) {
                        $image_url = $value;
                    } else {
                        $img_success = false;
                    }
                }

                // Fetch image from graby.
                if ( empty( $image_url ) && ( wp_doing_cron() || defined( 'qcld_seo_help_PRO_FETCH_ITEM_IMG_URL' ) ) ) {
                    // if license does not exist, use the site url
                    // this should obviously never happen unless on dev instances.
                    $license = apply_filters( 'product_qcld_seo_help_license_key', sprintf( 'n/a - %s', get_site_url() ) );

                    $response = wp_remote_post(
                        qcld_seo_help_PRO_FETCH_ITEM_IMG_URL,
                        apply_filters(
                            'qcld_seo_help_fetch_item_image',
                            array(
                                'timeout' => 100,
                                'body'    => array_merge(
                                    array(
                                        'item_url' => $item['item_url'],
                                        'license'  => $license,
                                        'site_url' => get_site_url(),
                                    )
                                ),
                            )
                        )
                    );

                    if ( ! is_wp_error( $response ) ) {
                        if ( array_key_exists( 'response', $response ) && array_key_exists( 'code', $response['response'] ) && intval( $response['response']['code'] ) !== 200 ) {
                            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
                            do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'error in response = %s', print_r( $response, true ) ), 'error', __FILE__, __LINE__ );
                        }
                        $body = wp_remote_retrieve_body( $response );
                        if ( ! is_wp_error( $body ) ) {
                            $response_data = json_decode( $body, true );
                            if ( isset( $response_data['url'] ) ) {
                                $image_url = $response_data['url'];
                            }
                        } else {
                            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
                            do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'error in body = %s', print_r( $body, true ) ), 'error', __FILE__, __LINE__ );
                        }
                    } else {
                        // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
                        do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'error in request = %s', print_r( $response, true ) ), 'error', __FILE__, __LINE__ );
                    }
                }

                if ( 'yes' === $import_item_img_url ) {
                    // Item image action.
                    $import_featured_img = rawurldecode( $import_featured_img );
                    $import_featured_img = trim( $import_featured_img );
                    //$img_action          = qcld_seo_help_handle_content_actions( $import_featured_img, 'item_image' );
                    // Item image action process.
                    //$image_url = $img_action->run_action_job( $import_featured_img, $import_translation_lang, $job, $language_code, $item, $image_url );

                    if ( ! empty( $image_url ) ) {
                        if ( 'yes' === $import_item_img_url ) {
                            // Set external image URL.
                            update_post_meta( $new_post_id, 'qcld_seo_help_item_external_url', $image_url );
                        } else {
                            // if import_featured_img is a tag.
                            $img_success = qcld_seo_help_generate_featured_image( $image_url, $new_post_id, $item['item_title'], $import_errors, $import_info );
                        }
                    }
                }

                // Set default thumbnail image.
                if ( ! $img_success && ! empty( $default_thumbnail ) ) {
                    $img_success = set_post_thumbnail( $new_post_id, $default_thumbnail );
                }

                if ( ! $img_success ) {
                    $import_image_errors ++;
                }
            }

            $index ++;

            // indicate that this post was imported by qcld_seo_help.
            update_post_meta( $new_post_id, 'qcld_seo_help', 1 );
            update_post_meta( $new_post_id, 'qcld_seo_help_item_url', esc_url_raw( $item['item_url'] ) );
            update_post_meta( $new_post_id, 'qcld_seo_help_job', $id );
            update_post_meta( $new_post_id, 'qcld_seo_help_item_author', sanitize_text_field( $author ) );

            // we can use this to associate the items that were imported in a particular run.
            update_post_meta( $new_post_id, 'qcld_seo_help_job_time', $last_run );

            do_action( 'qcld_seo_help_after_post_import', $new_post_id, $item, get_option( 'qcld-seo-help-rss-feeds-settings', array() ) );
        }

        if ( $use_new_hash ) {
            update_post_meta( $id, 'imported_items_hash', $imported_items );
        } else {
            update_post_meta( $id, 'imported_items', $imported_items );
        }
        update_post_meta( $id, 'imported_items_count', $count );

        if ( $import_image_errors > 0 ) {
            $import_errors[] = sprintf( __( 'Unable to find an image for %1$d out of %2$d items imported', 'qcld-seo-help' ), $import_image_errors, $count );
        }
        update_post_meta( $id, 'import_errors', $import_errors );

        // the order of these matters in how they are finally shown in the summary.
        $import_info['total']      = $items_found;
        $import_info['duplicates'] = $duplicates;

        update_post_meta( $id, 'import_info', $import_info );



        $msg =  __( 'Successfully run!', 'qcld-seo-help' );
        //$msg =   __( 'Nothing imported!', 'qcld-seo-help' );
        $msg .= ' (' . __( 'Refresh this page for the updated status', 'qcld-seo-help' ) . ')';

        

        //var_dump( $count );
        //wp_die();

        wp_send_json_success( array( 'msg' => $msg, 'import_success' => $count > 0 ) );

        //return $count;
    }
}