<?php 
defined('ABSPATH') or die("You can't access this file directly.");
/*
 * Parts of the code are inspired and/or copied from FEEDZY RSS Aggregator which is an open-source project.
 */

if ( ! function_exists( 'qcld_seo_help_form_meta_box_handler' ) ) {
    function qcld_seo_help_form_meta_box_handler($item){

    $post_id        = isset($item->ID) ? $item->ID : '';
    $website_url    = get_post_meta( $post_id, 'qcld_seo_help_website_url', true );
    $post_type      = get_post_meta( $post_id, 'import_post_type', true );
    $post_status    = get_post_meta( $post_id, 'import_post_status', true );
    $import_post_term    = get_post_meta( $post_id, 'import_post_term', true );

    $import_post_title                  = get_post_meta( $post_id, 'import_post_title', true );
    $import_post_date                   = get_post_meta( $post_id, 'import_post_date', true );
    $import_post_content                = get_post_meta( $post_id, 'import_post_content', true );
    $import_post_featured_img           = get_post_meta( $post_id, 'import_post_featured_img', true );
    $qcld_seo_help_cron_execution       = get_post_meta( $post_id, 'qcld_seo_help_cron_execution', true );
    $qcld_seo_help_cron_schedule        = get_post_meta( $post_id, 'qcld_seo_help_cron_schedule', true );
    $import_post_excerpt                = get_post_meta( $post_id, 'import_post_excerpt', true );
    $import_use_external_image          = get_post_meta( $post_id, 'qcld_seo_help_import_use_external_image', true );
    $import_post_tags                   = get_post_meta( $post_id, 'import_post_tags', true );


?>

        <div class="qcld_seo_help_wrap" id="qcld_seo_help_import-form">
            <div class="qcld_seo_help_accordion">
                <!-- qcld_seo_help_website_urls configuration Step Start -->
                <div class="qcld_seo_help_accordion-item">
                    <div class="qcld_seo_help_accordion-item__content border-top">
                        <div class="qcld_seo_help_form-wrap">
                          <div class="form-block">
                            <label class="form-label"><?php esc_html_e('Rewrite Content with OpenAI', 'qcld-seo-help' ); ?> </label> <a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>" target="_blank" class="qcld_seo_pro_feature"><?php esc_html_e('(Pro Feature)', 'qcld-seo-help' ); ?></a>
                            <div class="qcld-seo-rss-switch">
                                <input type="checkbox" name="qcld_seo_help_rewrite_content_openai" value="1" disabled="">
                                <span class="spinner"></span>
                            </div>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('RSS Feed Sources', 'qcld-seo-help' ); ?> </label></p>
                            <input type="test" name="qcld_seo_help_website_url" class="form-control" value="<?php echo esc_attr( $website_url ); ?>" />
                            <p><?php esc_html_e('Ex: http://example.com/feed/ ', 'qcld-seo-help' ); ?> </p>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Post Type', 'qcld-seo-help' ); ?> </label></p>
                                <select id="post_type" class="form-control" name="import_post_type" >
                                    <?php
                                        $post_types             = get_post_types( '', 'names' );
                                        $qcld_seo_help_post_type       = isset( $post_type ) ? $post_type : '';
                                        foreach ( $post_types as $post_type ) {
                                    ?>
                                        <option value="<?php echo esc_attr( $post_type ); ?>" <?php selected( $qcld_seo_help_post_type, $post_type ); ?>>
                                            <?php echo esc_html( $post_type ); ?>
                                        </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Post Status', 'qcld-seo-help' ); ?> </label></p>
                            <select id="post_status" class="form-control" name="import_post_status">
                                <?php
                                    $published_status      = array( 'publish', 'draft' );
                                    $import_post_status    = isset( $post_status ) ? $post_status : '';
                                    foreach ( $published_status as $status ) {
                                ?>
                                    <option value="<?php echo esc_attr( $status ); ?>" <?php selected( $import_post_status, $status ); ?>>
                                        <?php echo esc_html( ucfirst( $status ) ); ?>
                                    </option>
                                <?php
                                    }
                                ?>
                            </select>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Post Taxonomy', 'qcld-seo-help' ); ?> </label></p>
                            <?php 
                                // $post_categories = get_categories();
                                $post_categories = get_categories( array(
                                    'taxonomy'   => 'category',
                                    'orderby'    => 'name',
                                    'parent'     => 0,
                                    'hide_empty' => 0,
                                ) );
                            ?>
                            <select id="import_post_term" class="form-control" name="import_post_term">
                                <?php

                                    $import_post_status    = isset( $post_status ) ? $post_status : '';
                                    foreach($post_categories as $cat ){
                                ?>
                                    <option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $import_post_term, $cat->term_id ); ?>>
                                        <?php echo esc_html( ucfirst( $cat->name ) ); ?>
                                    </option>
                                <?php
                                    }

                                ?>
                            </select>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Post Title', 'qcld-seo-help' ); ?> </label></p>
                            <input type="test" name="import_post_title" class="form-control" value="<?php echo esc_attr( $import_post_title ? $import_post_title : '[#item_title]' ); ?>" />
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Post Date', 'qcld-seo-help' ); ?> </label></p>
                            <input type="test" name="import_post_date" class="form-control" value="<?php echo esc_attr( $import_post_date ? $import_post_date : '[#item_date]' ); ?>" />
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Content', 'qcld-seo-help' ); ?> </label></p>
                            <input type="test" name="import_post_content" class="form-control" value="<?php echo esc_attr( $import_post_content ? $import_post_content : '[#item_content]' ); ?>" />
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Post Excerpt', 'qcld-seo-help' ); ?> </label></p>
                            <input type="test" name="import_post_excerpt" class="form-control" value="<?php echo esc_attr( $import_post_excerpt ? $import_post_excerpt : '[#item_title] [#item_content] [#item_description]' ); ?>" />
                            <p><i> <?php esc_html_e('Ex: [#item_title] [#item_content] [#item_description] ', 'qcld-seo-help' ); ?></i></p>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Featured image', 'qcld-seo-help' ); ?> </label></p>
                            <input type="test" name="import_post_featured_img" class="form-control" value="<?php echo esc_attr( $import_post_featured_img ? $import_post_featured_img : '[#item_image]' ); ?>" />
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Tags', 'qcld-seo-help' ); ?> </label></p>
                            <input type="test" name="import_post_tags" class="form-control" value="<?php echo esc_attr( $import_post_tags ? $import_post_tags : '' ); ?>" />
                            <p><?php esc_html_e('You can add multiple tags as coma(,) seperated value or leave these empty. Ex: tag1,tag2 ', 'qcld-seo-help' ); ?></p>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Generate Feature Image with OpenAI', 'qcld-seo-help' ); ?> </label> <a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>" target="_blank" class="qcld_seo_pro_feature"><?php esc_html_e('(Pro Feature)', 'qcld-seo-help' ); ?></a></p>
                            <div class="qcld-seo-rss-switch">
                                <input type="checkbox" name="qcld_seo_help_import_use_external_image" value="yes" disabled >
                                <span class="spinner"></span>
                            </div>
                            <p><?php esc_html_e('AI image generate based on the (rewritten) title in stead of the feature post thumbnail images', 'qcld-seo-help' ); ?></p>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Author', 'qcld-seo-help' ); ?> </label> <a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>" target="_blank" class="qcld_seo_pro_feature"><?php esc_html_e('(Pro Feature)', 'qcld-seo-help' ); ?></a></p>
                            <select class="form-control" name="qcld_seo_help_author" disabled>
                                <?php $users = get_users();
                                foreach ($users as $user) {

                                    $qcld_seo_help_author = (isset( $qcld_seo_help_author ) && !empty($qcld_seo_help_author)) ? $qcld_seo_help_author : get_current_user_id();
                                ?>
                                    <option value="<?php echo esc_attr($user->ID); ?>" <?php selected( $qcld_seo_help_author, $user->ID ); ?>><?php esc_html_e( $user->display_name, 'qcld-seo-help' ); ?></option>
                                <?php }?>
                                
                            </select>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('First cron execution time', 'qcld-seo-help' ); ?> </label></p>
                            <input type="datetime-local" class="form-control" name="qcld_seo_help_cron_execution" value="<?php echo esc_attr( $qcld_seo_help_cron_execution ?  date("Y-m-d\TH:i:s", strtotime( $qcld_seo_help_cron_execution )) : '' ); ?>"> 
                            <p><?php /*echo esc_attr( $qcld_seo_help_cron_execution ? date('m/d/Y h:i A', strtotime( $qcld_seo_help_cron_execution )) : '' );*/ ?></p>
                          </div>
                          <div class="form-block">
                            <p><label class="form-label"><?php esc_html_e('Schedule', 'qcld-seo-help' ); ?> </label></p>
                            <select class="form-control" name="qcld_seo_help_cron_schedule">
                                <option value="hourly" <?php selected( $qcld_seo_help_cron_schedule, 'hourly' ); ?>><?php esc_html_e('Once Hourly (hourly)', 'qcld-seo-help' ); ?></option>
                                <option value="weekly" <?php selected( $qcld_seo_help_cron_schedule, 'weekly' ); ?>><?php esc_html_e('Once Weekly (weekly)', 'qcld-seo-help' ); ?></option>
                                <option value="every_1_minute" <?php selected( $qcld_seo_help_cron_schedule, 'every_1_minute' ); ?>><?php esc_html_e('1 min (every_1_minute)', 'qcld-seo-help' ); ?></option>
                                <option value="monthly" <?php selected( $qcld_seo_help_cron_schedule, 'monthly' ); ?>><?php esc_html_e('Monthly (monthly)', 'qcld-seo-help' ); ?></option>
                                <option value="fifteendays" <?php selected( $qcld_seo_help_cron_schedule, 'fifteendays' ); ?>><?php esc_html_e('Every 15 Days (fifteendays)', 'qcld-seo-help' ); ?></option>
                                <option value="wp_1_wc_privacy_cleanup_cron_interval" <?php selected( $qcld_seo_help_cron_schedule, 'wp_1_wc_privacy_cleanup_cron_interval' ); ?>><?php esc_html_e('Every 5 minutes (wp_1_wc_privacy_cleanup_cron_interval)', 'qcld-seo-help' ); ?></option>
                                <option value="twicedaily" <?php selected( $qcld_seo_help_cron_schedule, 'twicedaily' ); ?>><?php esc_html_e('Twice Daily (twicedaily)', 'qcld-seo-help' ); ?></option>
                                <option value="daily" <?php selected( $qcld_seo_help_cron_schedule, 'daily' ); ?>><?php esc_html_e('Once Daily (daily)', 'qcld-seo-help' ); ?></option>
                            </select>
                          </div>
                        </div>
                    </div>
                </div>
                <!-- qcld_seo_help_website_urls configuration Step End -->

            </div>
        </div>



<?php
    }
}


if ( ! function_exists( 'qcld_seo_help_wp_kses_allowed_html' ) ) {
    function qcld_seo_help_wp_kses_allowed_html( $tags, $context ) {
        if ( ! isset( $tags['iframe'] ) ) {
            $tags['iframe'] = array(
                'src'             => true,
                'height'          => true,
                'width'           => true,
                'frameborder'     => true,
                'allowfullscreen' => true,
                'data-*'          => true,
            );
        }
        if ( isset( $tags['span'] ) ) {
            $tags['span']['disabled'] = true;
        }

        return $tags;
    }
}

if ( ! function_exists( 'qcld_seo_help_is_duplicate_post' ) ) {
    function qcld_seo_help_is_duplicate_post( $post_type = 'post', $key = '', $value = '', $compare = '=' ) {
        if ( empty( $key ) || empty( $value ) ) {
            return false;
        }
        // Check post exists OR Not.
        $data = get_posts(
            array(
                'posts_per_page' => 80,
                'post_type'      => $post_type,
                'meta_key'       => $key,
                'meta_value'     => $value,
                'meta_compare'   => $compare,
                'fields'         => 'ids',
            )
        );

        return $data;
    }
}



if ( ! function_exists( 'qcld_seo_help_rss_register_post_type' ) ) {
    function qcld_seo_help_rss_register_post_type(){

        $labels = array(
            'name'                => __( 'Import and Rewrite from RSS feed', 'qcld-seo-help' ),
            'singular_name'       => __( 'RSS Import', 'qcld-seo-help' ),
            'add_new'             => __( 'Add New', 'qcld-seo-help' ),
            'add_new_item'        => __( 'Add New Import', 'qcld-seo-help' ),
            'edit_item'           => __( 'Edit Import', 'qcld-seo-help' ),
            'new_item'            => __( 'New Import', 'qcld-seo-help' ),
            'all_items'           => __( 'All Import', 'qcld-seo-help' ),
            'view_item'           => __( 'View Import', 'qcld-seo-help' ),
            'search_items'        => __( 'Search Imports', 'qcld-seo-help' ),
            'not_found'           => __( 'No events found', 'qcld-seo-help' ),
            'not_found_in_trash'  => __( 'No events found in Trash', 'qcld-seo-help' ),
            'menu_name'           => __( 'Import and Rewrite from RSS feeds', 'qcld-seo-help' ),
        );

        //$supports = array( 'title', 'editor' );
        $supports = array( 'title' );

        //$slug = get_theme_mod( 'event_permalink' );
        //$slug = ( empty( $slug ) ) ? 'event' : $slug;

        $args = array(
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'query_var'           => true,
            //'rewrite'             => array( 'slug' => $slug ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => $supports,
        );

        register_post_type( 'qcld_rss_imports', $args );

    }
}
add_action('init', 'qcld_seo_help_rss_register_post_type');


add_filter( 'post_row_actions', 'qcld_seo_help_rss_remove_row_actions', 10, 2 );
if(!function_exists('qcld_seo_help_rss_remove_row_actions')){
    function qcld_seo_help_rss_remove_row_actions( $actions, $post ){
        
        if ($post->post_type == "qcld_rss_imports") {
            unset( $actions['view'] );
        }
        return $actions;
    }
}



//Custom Columns for Directory Listing
if(!function_exists('qcld_seo_help_rss_column_head')){
    function qcld_seo_help_rss_column_head($defaults) {

        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __('Import Title', 'qcld-seo-help' ),
            'website_url'   => __('Source', 'qcld-seo-help' ),
            'content'       => __('Current Status', 'qcld-seo-help' ),
            'status'        => __('Status', 'qcld-seo-help' ),
        );
        return $columns;

    }
}
 

if(!function_exists('qcld_seo_help_rss_add_custom_meta_boxs')){
    function qcld_seo_help_rss_add_custom_meta_boxs(){

        add_meta_box("qcld_seo_help_rss_meta_box", __( "Rss Import", "qcld-seo-help" ), "qcld_seo_help_form_meta_box_handler", "qcld_rss_imports", "normal", "high" );

    }
}
add_action("add_meta_boxes", "qcld_seo_help_rss_add_custom_meta_boxs");


if(!function_exists('qcld_seo_help_rss_column_callback')){
    function qcld_seo_help_rss_column_callback($column_name, $post_ID){
        $post_id = $post_ID;

        if ($column_name == 'website_url') {

            $website_url    = get_post_meta( $post_ID, 'qcld_seo_help_website_url', true );
            echo sprintf('<a href="%s" target="_blank">%s</a>', $website_url, $website_url );

        }

        if ($column_name == 'content') {
            $post_status    = ( get_post_meta( $post_ID, 'qcld_seo_help_post_status', true ) == 'publish' ) ? 'checked' : '';
            echo sprintf('<div class="qcld-seo-rss-switch">
                            <input type="checkbox" class="qcld-seo-rss-switch-btn" data-id="%s" %s />
                            <span class="spinner"></span>
                        </div>', $post_ID, $post_status );

        }

        if ($column_name == 'status') {


                $last = get_post_meta( $post_id, 'last_run', true );
                $msg  = __( 'Never Run', 'qcld-seo-help' );
                if ( $last ) {
                    $now  = new DateTime();
                    $then = new DateTime();
                    $then = $then->setTimestamp( $last );
                    $in   = $now->diff( $then );
                    $msg  = sprintf( __( 'Ran %1$d hours %2$d minutes ago', 'qcld-seo-help' ), $in->format( '%h' ), $in->format( '%i' ) );
                }


            echo sprintf(' <div class="qcld-seo-rss-status-wrap">
                            <p>%s</p>
                            <span class="spinner"></span>
                            <button type="button" class="button button-primary qcld_seo_help_rss_run_now" data-id="%s" >'.esc_html('Run Now').'</button>
                            
                          <div class="form-block">
                            <label class="form-label"> '.esc_html("Rewrite Content with OpenAI", "qcld-seo-help" ).' </label> <a href="'.esc_url('https://www.dna88.com/product/seo-help-pro/').'" target="_blank" class="qcld_seo_pro_feature">'.esc_html( "(Pro Feature)", "qcld-seo-help" ).'</a>
                            <div class="qcld-seo-rss-switch">
                                <input type="checkbox" name="qcld_seo_help_rewrite_content_openai" value="1" disabled="">
                                <span class="spinner"></span>
                            </div>
                          </div>
                        </div>', $msg, $post_ID );


            $msg    = '';
            $last   = get_post_meta( $post_id, 'last_run', true );
            $status = array(
                'total'      => '-',
                'items'      => '-',
                'duplicates' => '-',
                'cumulative' => '-',
            );
            if ( $last ) {
                $status = array(
                    'total'      => 0,
                    'items'      => 0,
                    'duplicates' => 0,
                    'cumulative' => 0,
                );
                $status = qcld_seo_help_get_complete_import_status( $post_id );
            }

            // link to the posts listing for this job.
            $job_linked_posts = add_query_arg(
                array(
                    'qcld_seo_help_job_id'  => $post_id,
                    'post_type'             => get_post_meta(
                        $post_id,
                        'import_post_type',
                        true
                    ),
                    '_nonce'        => wp_create_nonce( 'job_run_linked_posts' ),
                ),
                admin_url( 'edit.php' )
            );

            // link to the posts listing for this job run.
            $job_run_linked_posts = '';
            $job_run_id           = get_post_meta( $post_id, 'last_run_id', true );
            if ( ! empty( $job_run_id ) ) {
                $job_run_linked_posts = add_query_arg(
                    array(
                        'qcld_seo_help_job_id'   => $post_id,
                        'qcld_seo_help_job_time' => $job_run_id,
                        '_nonce'          => wp_create_nonce( 'job_run_linked_posts' ),
                        'post_type'       => get_post_meta(
                            $post_id,
                            'import_post_type',
                            true
                        ),
                    ),
                    admin_url( 'edit.php' )
                );
            }

            $errors = qcld_seo_help_get_import_errors( $post_id );
            // popup for errors found.
            if ( ! empty( $errors ) ) {
                $msg .= '<div class="qcld_seo_help-errors-found-' . $post_id . ' qcld_seo_help-dialog" title="' . __( 'Errors', 'qcld-seo-help' ) . '">' . $errors . '</div>';
            }


            $msg .= sprintf(
                '<table class="qcld_seo_help-table">
                    <tr>
                        <td class="qcld_seo_help-items %s" data-value="%d"><a class="qcld_seo_help-found-details" title="%s" data-dialog="qcld_seo_help-items-found-%d">%s</a></td>
                        <td class="qcld_seo_help-duplicates %s" data-value="%d"><a class="qcld_seo_help-duplicates-details qcld_seo_help-dialog-open" title="%s" data-dialog="qcld_seo_help-duplicates-found-%d">%s</a></td>
                        <td class="qcld_seo_help-imported %s" data-value="%d"><a target="%s" href="%s" class="qcld_seo_help-popup-details" title="%s">%s</a></td>
                        <td class="qcld_seo_help-cumulative %s" data-value="%d"><a target="%s" href="%s" class="qcld_seo_help-popup-details" title="%s">%s</a></td>
                        <td class="qcld_seo_help-error-status %s" data-value="%d"><a class="qcld_seo_help-popup-details qcld_seo_help-dialog-open" data-dialog="qcld_seo_help-errors-found-%d" title="%s">%s</a></td>
                    </tr>
                    <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                    </tr>
                </table>',
            // first cell
            is_array( $status['items'] ) ? 'qcld_seo_help-has-popup' : '',
            is_array( $status['items'] ) ? count( $status['items'] ) : $status['items'],
            __( 'Items that were found in the feed', 'qcld-seo-help' ),
            $post_id,
            is_array( $status['items'] ) ? count( $status['items'] ) : $status['items'],
            // second cells
            is_array( $status['duplicates'] ) ? 'qcld_seo_help-has-popup' : '',
            is_array( $status['duplicates'] ) ? count( $status['duplicates'] ) : $status['duplicates'],
            __( 'Items that were discarded as duplicates', 'qcld-seo-help' ),
            $post_id,
            is_array( $status['duplicates'] ) ? count( $status['duplicates'] ) : $status['duplicates'],
            // third cell
            $status['total'] > 0 && ! empty( $job_run_linked_posts ) ? 'qcld_seo_help-has-popup' : '',
            $status['total'],
            defined( 'TI_CYPRESS_TESTING' ) ? '' : '_blank',
            $status['total'] > 0 && ! empty( $job_run_linked_posts ) ? $job_run_linked_posts : '',
            __( 'Items that were imported', 'qcld-seo-help' ),
            $status['total'],
            // fourth cell
            $status['cumulative'] > 0 ? 'qcld_seo_help-has-popup' : '',
            $status['cumulative'],
            defined( 'TI_CYPRESS_TESTING' ) ? '' : '_blank',
            $status['cumulative'] > 0 ? $job_linked_posts : '',
            __( 'Items that were imported across all runs', 'qcld-seo-help' ),
            $status['cumulative'],
            // fifth cell
            empty( $last ) ? '' : ( ! empty( $errors ) ? 'qcld_seo_help-has-popup import-error' : 'import-success' ),
            empty( $last ) ? '-1' : ( ! empty( $errors ) ? 0 : 1 ),
            $post_id,
            __( 'View the errors', 'qcld-seo-help' ),
            empty( $last ) ? '-' : ( ! empty( $errors ) ? '<i class="dashicons dashicons-warning"></i>' : '<i class="dashicons dashicons-yes-alt"></i>' ),
            // second row
            __( 'Found', 'qcld-seo-help' ),
            __( 'Duplicates', 'qcld-seo-help' ),
            __( 'Imported', 'qcld-seo-help' ),
            __( 'Cumulative', 'qcld-seo-help' ),
            __( 'Status', 'qcld-seo-help' )
        );



            // popup for items found.
            if ( is_array( $status['items'] ) ) {
                $msg .= '<div class="qcld_seo_help-items-found qcld_seo_help-dialog" title="' . __( 'Items found', 'qcld-seo-help' ) . '"><b>' . __( 'Items found', 'qcld-seo-help' ) . '</b><ol>';
                foreach ( $status['items'] as $url => $title ) {
                    $msg .= sprintf( '<li><p><a href="%s" target="_blank">%s</a></p></li>', esc_url( $url ), esc_html( $title ) );
                }
                $msg .= '</ol></div>';
            }

            // popup for duplicates found.
            if ( is_array( $status['duplicates'] ) ) {
                $msg .= '<div class="qcld_seo_help-duplicates-found qcld_seo_help-dialog" title="' . __( 'Duplicates found', 'qcld-seo-help' ) . '"><b>' . __( 'Duplicates found', 'qcld-seo-help' ) . '</b><ol>';
                foreach ( $status['duplicates'] as $url => $title ) {
                    $msg .= sprintf( '<li><p><a href="%s" target="_blank">%s</a></p></li>', esc_url( $url ), esc_html( $title ) );
                }
                $msg .= '</ol></div>';
            }

        echo $msg;




        }

    }
}


if ( ! function_exists( 'qcld_seo_help_get_complete_import_status' ) ) {
    function qcld_seo_help_get_complete_import_status( $post_id ) {
        $items_count = get_post_meta( $post_id, 'imported_items_count', true );
        $items       = get_post_meta( $post_id, 'imported_items_hash', true );
        if ( empty( $items ) ) {
            $items = get_post_meta( $post_id, 'imported_items', true );
        }
        $count = $items_count;
        if ( '' === $count && $items ) {
            // compatibility where imported_items_count post_meta has not been populated yet
            $count = count( $items );
        }

        $status = array(
            'total'      => $count,
            'items'      => 0,
            'duplicates' => 0,
            'cumulative' => 0,
        );

        $import_info = get_post_meta( $post_id, 'import_info', true );
        if ( $import_info ) {
            foreach ( $import_info as $label => $value ) {
                switch ( $label ) {
                    case 'total':
                        if ( count( $value ) > 0 ) {
                            $status['items'] = $value;
                        }
                        break;
                    case 'duplicates':
                        if ( count( $value ) > 0 ) {
                            $status['duplicates'] = $value;
                        }
                        break;
                }
            }
        }

        $items = get_post_meta( $post_id, 'imported_items_hash', true );
        if ( empty( $items ) ) {
            $items = get_post_meta( $post_id, 'imported_items', true );
        }
        if ( $items ) {
            $status['cumulative'] = count( $items );
        }

        return $status;

    }
}

if ( ! function_exists( 'qcld_seo_help_get_import_errors' ) ) {
    function qcld_seo_help_get_import_errors( $post_id ) {
        $msg           = '';
        $import_errors = get_post_meta( $post_id, 'import_errors', true );
        if ( $import_errors ) {
            $errors = '';
            if ( is_array( $import_errors ) ) {
                foreach ( $import_errors as $err ) {
                    $errors .= '<div><i class="dashicons dashicons-warning"></i>' . $err . '</div>';
                }
            } else {
                $errors = '<div><i class="dashicons dashicons-warning"></i>' . $import_errors . '</div>';
            }
            $msg = '<div class="qcld_seo_help-error qcld_seo_help-api-error">' . $errors . '</div>';
        }

        $pro_msg = apply_filters( 'qcld_seo_help_run_status_errors', '', $post_id );

        // the pro messages may not have the dashicons, so let's add them.
        if ( $pro_msg && strpos( $pro_msg, 'dashicons-warning' ) === false ) {
            $errors     = '';
            $pro_errors = explode( '<br>', $pro_msg );
            if ( is_array( $pro_errors ) ) {
                foreach ( $pro_errors as $err ) {
                    $errors .= '<div><i class="dashicons dashicons-warning"></i>' . $err . '</div>';
                }
            } else {
                $errors = '<div><i class="dashicons dashicons-warning"></i>' . $pro_errors . '</div>';
            }
            $pro_msg = '<div class="qcld_seo_help-error qcld_seo_help-api-error">' . $errors . '</div>';

        }

        return $msg . $pro_msg;
    }
}

add_filter('manage_qcld_rss_imports_posts_columns', 'qcld_seo_help_rss_column_head');
add_action('manage_qcld_rss_imports_posts_custom_column', 'qcld_seo_help_rss_column_callback', 10, 2);



add_action( 'save_post', 'qcld_seo_help_rss_custom_post_type_data_save');
if(!function_exists('qcld_seo_help_rss_custom_post_type_data_save')){
    function qcld_seo_help_rss_custom_post_type_data_save( $postid = false ){

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;
        if ( !current_user_can( 'edit_page', $postid ) ) return false;

        if( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'qcld_rss_imports'){

            update_post_meta($postid, 'qcld_seo_help_rewrite_content_openai', '' );

            if( isset( $_POST['qcld_seo_help_import_use_external_image'] ) && !empty( $_POST['qcld_seo_help_import_use_external_image'] ) ){
                update_post_meta($postid, 'qcld_seo_help_import_use_external_image', sanitize_text_field($_POST['qcld_seo_help_import_use_external_image']) );
            }else{
                update_post_meta($postid, 'qcld_seo_help_import_use_external_image', '' );
            }
            
            update_post_meta($postid, 'qcld_seo_help_website_url', sanitize_text_field($_POST['qcld_seo_help_website_url']));
            update_post_meta($postid, 'import_post_type', sanitize_text_field($_POST['import_post_type']));
            update_post_meta($postid, 'import_post_status', sanitize_text_field($_POST['import_post_status']));
            update_post_meta($postid, 'import_post_term', sanitize_text_field($_POST['import_post_term']));
            update_post_meta($postid, 'import_post_excerpt', sanitize_text_field($_POST['import_post_excerpt']));

            update_post_meta($postid, 'import_post_title', sanitize_text_field($_POST['import_post_title']));
            update_post_meta($postid, 'import_post_date', sanitize_text_field($_POST['import_post_date']));
            update_post_meta($postid, 'import_post_content', sanitize_text_field($_POST['import_post_content']));
            update_post_meta($postid, 'import_post_featured_img', sanitize_text_field($_POST['import_post_featured_img']));

            update_post_meta($postid, 'qcld_seo_help_cron_execution',  sanitize_text_field( $_POST['qcld_seo_help_cron_execution'] ) );
            update_post_meta($postid, 'qcld_seo_help_cron_schedule', sanitize_text_field($_POST['qcld_seo_help_cron_schedule']));
            update_post_meta($postid, 'import_post_tags', sanitize_text_field($_POST['import_post_tags']));



        }

        
    }

}




add_action( 'wp_ajax_qcld_seo_help_rss_post_status',  'qcld_seo_help_rss_post_status_function_callback' );
add_action('wp_ajax_nopriv_qcld_seo_help_rss_post_status',  'qcld_seo_help_rss_post_status_function_callback' );
if ( ! function_exists( 'qcld_seo_help_rss_post_status_function_callback' ) ) {
    function qcld_seo_help_rss_post_status_function_callback(  ){

        check_ajax_referer( 'seo-help-pro', 'security');

        //global $wpdb;

        $id                             = isset( $_POST['id'] ) ? $_POST['id'] : '';
        $qcld_seo_help_post_status      = get_post_meta( $id, 'qcld_seo_help_post_status', true );
        if( $qcld_seo_help_post_status == 'publish' ){
            delete_post_meta( $id, 'qcld_seo_help_post_status' );

        }else{
            update_post_meta( $id, 'qcld_seo_help_post_status', 'publish' );
        }



        $message = '<p class="qcld_running_process">'.esc_html('The process is running in the background.').'</p>';

        $response = array( 'message' => $message );
        echo wp_send_json($response);
        wp_die();

    }
}



add_action( 'wp_ajax_qcld_seo_help_rss_run_now_ajax',  'qcld_seo_help_rss_run_now_function_callback' );
add_action('wp_ajax_nopriv_qcld_seo_help_rss_run_now_ajax',  'qcld_seo_help_rss_run_now_function_callback' );
if ( ! function_exists( 'qcld_seo_help_rss_run_now_function_callback' ) ) {
    function qcld_seo_help_rss_run_now_function_callback(  ){

        check_ajax_referer( 'seo-help-pro', 'security');

        //global $wpdb;

        $id  = isset( $_POST['id'] ) ? $_POST['id'] : '';
        global $post;

        $job = get_post($id);

        //$job

        $qcld_seo_help_website_url = get_post_meta( $id, 'qcld_seo_help_website_url', true );

        $max = 100;

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


if ( ! function_exists( 'qcld_seo_help_get_job_feed' ) ) {
    function qcld_seo_help_get_job_feed( $options, $import_content = null, $raw_feed_also = false ) {

        $feedURL     = qcld_seo_help_normalize_urls( $options['feeds'] );
        $source_type = get_post_meta( $options['__jobID'], '__qcld_seo_help_source_type', true );


        $feedURL = apply_filters( 'qcld_seo_help_import_feed_url', $feedURL, $import_content, $options );
        if ( is_wp_error( $feedURL ) ) {
            return $feedURL;
        }
        $feed = qcld_seo_help_fetch_feed( $feedURL, isset( $options['refresh'] ) ? $options['refresh'] : '12_hours', $options );

        $feed->force_feed( true );
        $feed->enable_order_by_date( false );

        if ( is_string( $feed ) ) {
            return array();
        }
        //'size'            => '250'
        $size = isset( $options['size'] ) ? $options['size'] : '250';
        $sizes      = array(
            'width'  => $size,
            'height' => $size,
        );
        $sizes      = apply_filters( 'qcld_seo_help_thumb_sizes', $sizes, $feedURL );
        $feed_items = apply_filters( 'qcld_seo_help_get_feed_array', array(), $options, $feed, $feedURL, $sizes );
        if ( $raw_feed_also ) {
            return array(
                'items' => $feed_items,
                'feed'  => $feed,
            );
        }

        return $feed_items;
    }
}

if ( ! function_exists( 'qcld_seo_help_normalize_urls' ) ) {
    function qcld_seo_help_normalize_urls( $raw ) {
        $feeds    = apply_filters( 'qcld_seo_help_process_feed_source', $raw );
        $feed_url = apply_filters( 'qcld_seo_help_get_feed_url', $feeds );
        if ( is_array( $feed_url ) ) {
            foreach ( $feed_url as $index => $url ) {
                $feed_url[ $index ] = trim( qcld_seo_help_smart_convert( $url ) );
            }
        } else {
            $feed_url = trim( qcld_seo_help_smart_convert( $feed_url ) );
        }

        return $feed_url;
    }
}

if ( ! function_exists( 'qcld_seo_help_smart_convert' ) ) {
    function qcld_seo_help_smart_convert( $url ) {

        $url = htmlspecialchars_decode( $url );

        // Automatically fix deprecated google news feeds.
        if ( false !== strpos( $url, 'news.google.' ) ) {

            $parts = wp_parse_url( $url );
            parse_str( $parts['query'], $query );

            if ( isset( $query['q'] ) ) {
                $search_query = $query['q'];
                unset( $query['q'] );
                $url = sprintf( 'https://news.google.com/news/rss/search/section/q/%s/%s?%s', $search_query, $search_query, http_build_query( $query ) );

            }
        }

        return apply_filters( 'qcld_seo_help_alter_feed_url', $url );
    }
}

if ( ! function_exists( 'qcld_seo_help_fetch_feed' ) ) {
    function qcld_seo_help_fetch_feed( $feed_url, $cache = '12_hours', $sc = '' ) {
        // Load SimplePie if not already.
        do_action( 'qcld_seo_help_pre_http_setup', $feed_url );
        if ( function_exists( 'qcld_seo_help_amazon_get_locale_hosts' ) ) {
            $amazon_hosts     = qcld_seo_help_amazon_get_locale_hosts();
            $is_amazon_source = false;
            if ( is_array( $feed_url ) ) {
                $url_host = array_map(
                    function( $url ) {
                        return 'webservices.' . wp_parse_url( $url, PHP_URL_HOST );
                    },
                    $feed_url
                );
                $url_host         = array_diff( $url_host, $amazon_hosts );
                $is_amazon_source = ! empty( $amazon_hosts ) && empty( $url_host );
            } else {
                $url_host         = 'webservices.' . wp_parse_url( $feed_url, PHP_URL_HOST );
                $is_amazon_source = ! empty( $amazon_hosts ) && in_array( $url_host, $amazon_hosts, true );
            }
            if ( $is_amazon_source ) {
                $feed = $this->init_amazon_api(
                    $feed_url,
                    isset( $sc['refresh'] ) ? $sc['refresh'] : '12_hours',
                    array(
                        'number_of_item' => isset( $sc['max'] ) ? $sc['max'] : 5,
                        'no-cache'       => false,
                    )
                );
                return $feed;
            }
        }
        // Load SimplePie Instance.
        $feed = qcld_seo_help_init_feed( $feed_url, $cache, $sc ); // Not used as log as #41304 is Opened.

        // Report error when is an error loading the feed.
        if ( is_wp_error( $feed ) ) {
            // Fallback for different edge cases.
            if ( is_array( $feed_url ) ) {
                $feed_url = array_map( 'html_entity_decode', $feed_url );
            } else {
                $feed_url = html_entity_decode( $feed_url );
            }

            $feed_url = qcld_seo_help_get_valid_source_urls( $feed_url, $cache );

            $feed = qcld_seo_help_init_feed( $feed_url, $cache, $sc ); // Not used as log as #41304 is Opened.

        }

        do_action( 'qcld_seo_help_post_http_teardown', $feed_url );

        return $feed;
    }
}

if ( ! function_exists( 'qcld_seo_help_get_valid_source_urls' ) ) {
    function qcld_seo_help_get_valid_source_urls( $feed_url, $cache, $echo = true ) {
        $valid_feed_url = array();
        if ( is_array( $feed_url ) ) {
            foreach ( $feed_url as $url ) {
                $source_type = 'xml';
                if ( function_exists( 'qcld_seo_help_amazon_get_locale_hosts' ) ) {
                    $amazon_hosts  = qcld_seo_help_amazon_get_locale_hosts();
                    $url_host      = 'webservices.' . wp_parse_url( $url, PHP_URL_HOST );
                    $is_amazon_url = ! empty( $amazon_hosts ) && in_array( $url_host, $amazon_hosts, true ) ? true : false;
                    $source_type   = $is_amazon_url ? 'amazon' : $source_type;
                }
                if ( $this->check_valid_source( $url, $cache, $source_type ) ) {
                    $valid_feed_url[] = $url;
                } else {
                    if ( $echo ) {
                        echo wp_kses_post( sprintf( __( 'Feed URL: %s not valid and removed from fetch.', 'qcld-seo-help' ), '<b>' . esc_url( $url ) . '</b>' ) );
                    }
                }
            }
        } else {
            $source_type = 'xml';
            if ( function_exists( 'qcld_seo_help_amazon_get_locale_hosts' ) ) {
                $url_host      = 'webservices.' . wp_parse_url( $feed_url, PHP_URL_HOST );
                $amazon_hosts  = qcld_seo_help_amazon_get_locale_hosts();
                $is_amazon_url = ! empty( $amazon_hosts ) && in_array( $url_host, $amazon_hosts, true ) ? true : false;
                $source_type   = $is_amazon_url ? 'amazon' : $source_type;
            }
            if ( qcld_seo_help_check_valid_source( $feed_url, $cache, $source_type ) ) {
                $valid_feed_url[] = $feed_url;
            } else {
                if ( $echo ) {
                    echo wp_kses_post( sprintf( __( 'Feed URL: %s not valid and removed from fetch.', 'qcld-seo-help' ), '<b>' . esc_url( $feed_url ) . '</b>' ) );
                }
            }
        }

        return $valid_feed_url;
    }
}

if ( ! function_exists( 'qcld_seo_help_check_valid_source' ) ) {
    function qcld_seo_help_check_valid_source( $url, $cache, $source_type = 'xml' ) {
        global $post;

        // phpcs:disable WordPress.Security.NonceVerification
        if ( null === $post && ! empty( $_POST['id'] ) ) {
            $post_id = (int) $_POST['id'];
        } else {
            $post_id = $post->ID;
        }
        $is_valid = true;
        if ( 'amazon' === $source_type ) {
            $amazon_api_errors = array();
            $amazon_products = '';
            if ( ! empty( $amazon_products->get_errors() ) ) {
                $amazon_api_errors['source_type'] = __( '[Amazon Product Advertising API] ', 'qcld-seo-help' );
                $amazon_api_errors['source']      = array( $url );
                $amazon_api_errors['errors']      = $amazon_products->get_errors();
                update_post_meta( $post_id, '__transient_qcld_seo_help_invalid_source_errors', $amazon_api_errors );
                $is_valid = false;
            }
        } else {
            $feed = qcld_seo_help_init_feed( $url, $cache, array() );
            if ( $feed->error() ) {
                $is_valid = false;
            }
            // phpcs:ignore WordPress.Security.NonceVerification
            if ( isset( $_POST['qcld_seo_help_meta_data']['import_link_author_admin'] ) && 'yes' === $_POST['qcld_seo_help_meta_data']['import_link_author_admin'] ) {
                if ( $feed->get_items() ) {
                    $author = $feed->get_items()[0]->get_author();
                    if ( empty( $author ) ) {
                        update_post_meta( $post_id, '__transient_qcld_seo_help_invalid_dc_namespace', array( $url ) );
                        $is_valid = false;
                    }
                }
            }
        }
        // Update source type.
        update_post_meta( $post_id, '__qcld_seo_help_source_type', $source_type );

        return $is_valid;
    }
}

if ( ! function_exists( 'qcld_seo_help_init_feed' ) ) {
    function qcld_seo_help_init_feed( $feed_url, $cache, $sc, $allow_https = QCLD_SEO_ALLOW_HTTPS ) {
        $unit_defaults = array(
            'mins'  => MINUTE_IN_SECONDS,
            'hours' => HOUR_IN_SECONDS,
            'days'  => DAY_IN_SECONDS,
        );
        $cache_time    = 12 * HOUR_IN_SECONDS;
        $cache = trim( $cache );
        if ( isset( $cache ) && '' !== $cache ) {
            list( $value, $unit ) = explode( '_', $cache ?? '' );
            if ( isset( $value ) && is_numeric( $value ) && $value >= 1 && $value <= 100 ) {
                if ( isset( $unit ) && in_array( strtolower( $unit ), array( 'mins', 'hours', 'days' ), true ) ) {
                    $cache_time = $value * $unit_defaults[ $unit ];
                }
            }
        }

        $feed = new Qcld_seo_help_Rss_Feeds_Util_SimplePie( $sc );
        if ( ! $allow_https && method_exists( $feed, 'set_curl_options' ) ) {
            $feed->set_curl_options(
                array(
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                )
            );
        }
        require_once ABSPATH . WPINC . '/class-wp-feed-cache-transient.php';
        require_once ABSPATH . WPINC . '/class-wp-simplepie-file.php';

        $feed->set_file_class( 'WP_SimplePie_File' );
        $default_agent = qcld_seo_help_get_default_user_agent( $feed_url );
        $feed->set_useragent( apply_filters( 'http_headers_useragent', $default_agent ) );
        if ( false === apply_filters( 'qcld_seo_help_disable_db_cache', false, $feed_url ) ) {
            SimplePie_Cache::register( 'wp_transient', 'WP_Feed_Cache_Transient' );
            $feed->set_cache_location( 'wp_transient' );
            if ( ! has_filter( 'wp_feed_cache_transient_lifetime' ) ) {
                add_filter(
                    'wp_feed_cache_transient_lifetime',
                    function( $time ) use ( $cache_time ) {
                        return $cache_time;
                    },
                    10,
                    1
                );
            }
            $feed->set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', $cache_time, $feed_url ) );
        } else {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
            global $wp_filesystem;

            $dir = $wp_filesystem->wp_content_dir() . 'uploads/simplepie';
            if ( ! $wp_filesystem->exists( $dir ) ) {
                $done = $wp_filesystem->mkdir( $dir );
                if ( false === $done ) {
                    do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Unable to create directory %s', $dir ), 'error', __FILE__, __LINE__ );
                }
            }
            $feed->set_cache_location( $dir );
        }

        // Do not use force_feed for multiple URLs.
        $feed->force_feed( apply_filters( 'qcld_seo_help_force_feed', ( is_string( $feed_url ) || ( is_array( $feed_url ) && 1 === count( $feed_url ) ) ) ) );

        do_action( 'qcld_seo_help_modify_feed_config', $feed );

        $cloned_feed = clone $feed;

        // set the url as the last step, because we need to be able to clone this feed without the url being set
        // so that we can fall back to raw data in case of an error.
        $feed->set_feed_url( $feed_url );

        // Allow unsafe html.
        if ( defined( 'qcld_seo_help_ALLOW_UNSAFE_HTML' ) && qcld_seo_help_ALLOW_UNSAFE_HTML ) {
            $feed->strip_htmltags( false );
        }

        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
            $set_server_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
            $feed->set_useragent( apply_filters( 'http_headers_useragent', $set_server_agent ) );
        }

        global $qcld_seo_help_current_error_reporting;
        $qcld_seo_help_current_error_reporting = error_reporting();

        // to avoid the Warning! Non-numeric value encountered. This can be removed once SimplePie in core is fixed.
        if ( version_compare( phpversion(), '7.1', '>=' ) ) {
            error_reporting( E_ALL & ~E_WARNING & ~E_DEPRECATED );
            // reset the error_reporting back to its original value.
            add_action(
                'shutdown',
                function() {
                    global $qcld_seo_help_current_error_reporting;
                    error_reporting( $qcld_seo_help_current_error_reporting );
                }
            );
        }

        $feed->init();

        if ( ! $feed->get_type() ) {
            return $feed;
        }

        $error = $feed->error();
        // error could be an array, so let's join the different errors.
        if ( is_array( $error ) ) {
            $error = implode( '|', $error );
        }

        if ( ! empty( $error ) ) {
            do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Error while parsing feed: %s', $error ), 'error', __FILE__, __LINE__ );

            // curl: (60) SSL certificate problem: unable to get local issuer certificate
            if ( strpos( $error, 'SSL certificate' ) !== false ) {
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Got an SSL Error (%s), retrying by ignoring SSL', $error ), 'debug', __FILE__, __LINE__ );
                $feed = qcld_seo_help_init_feed( $feed_url, $cache, $sc, false );
            } elseif ( is_string( $feed_url ) || ( is_array( $feed_url ) && 1 === count( $feed_url ) ) ) {
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, 'Trying to use raw data', 'debug', __FILE__, __LINE__ );
                $data = wp_remote_retrieve_body( wp_safe_remote_get( $feed_url, array( 'user-agent' => $default_agent ) ) );
                $cloned_feed->set_raw_data( $data );
                $cloned_feed->init();
                $error_raw = $cloned_feed->error();
                if ( empty( $error_raw ) ) {
                    // only if using the raw url produces no errors, will we consider the new feed as good to go.
                    // otherwise we will use the old feed.
                    $feed = $cloned_feed;
                }
            } else {
                do_action( 'themeisle_log_event', qcld_seo_help_NAME, 'Cannot use raw data as this is a multifeed URL', 'debug', __FILE__, __LINE__ );
            }
        }
        return $feed;
    }
}

if ( ! function_exists( 'qcld_seo_help_import_trim_tags' ) ) {
    function qcld_seo_help_import_trim_tags( $content = '' ) {
        if ( ! empty( $content ) && is_string( $content ) ) {
            $content = explode( ',', $content ?? '' );
            $content = array_map( 'trim', $content );
            $content = implode( ' ', $content );
        }

        return $content;
    }
}

if ( ! function_exists( 'qcld_seo_help_get_default_user_agent' ) ) {
    function qcld_seo_help_get_default_user_agent( $urls ) {

        $set = array();
        if ( ! is_array( $urls ) ) {
            $set[] = $urls;
        }
        foreach ( $set as $url ) {
            if ( strpos( $url, 'medium.com' ) !== false ) {
                return QCLD_SEO_USER_AGENT;
            }
        }

        return SIMPLEPIE_USERAGENT;
    }
}

if ( ! function_exists( 'qcld_seo_help_sanitize_attr' ) ) {
    function qcld_seo_help_sanitize_attr( $sc, $feed_url ) {
        // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
        if ( '0' == $sc['max'] ) {
            $sc['max'] = '999';
        } elseif ( empty( $sc['max'] ) || ! is_numeric( $sc['max'] ) ) {
            $sc['max'] = '5';
        }

        if ( empty( $sc['offset'] ) || ! is_numeric( $sc['offset'] ) ) {
            $sc['offset'] = '0';
        }

        if ( empty( $sc['size'] ) || ! ctype_digit( (string) $sc['size'] ) ) {
            $sc['size'] = '150';
        }
        if ( ! empty( $sc['keywords_title'] ) ) {
            if ( is_array( $sc['keywords_title'] ) ) {
                $sc['keywords_title'] = implode( ',', $sc['keywords_title'] );
            }
            $sc['keywords_title'] = qcld_seo_help_filter_custom_pattern( $sc['keywords_title'] );
        }
        if ( ! empty( $sc['keywords_inc'] ) ) {
            if ( is_array( $sc['keywords_inc'] ) ) {
                $sc['keywords_inc'] = implode( ',', $sc['keywords_inc'] );
            }
            $sc['keywords_inc'] = qcld_seo_help_filter_custom_pattern( $sc['keywords_inc'] );
        }
        if ( ! empty( $sc['keywords_ban'] ) ) {
            if ( is_array( $sc['keywords_ban'] ) ) {
                $sc['keywords_ban'] = implode( ',', $sc['keywords_ban'] );
            }
            $sc['keywords_ban'] = qcld_seo_help_filter_custom_pattern( $sc['keywords_ban'] );
        }
        if ( ! empty( $sc['keywords_exc'] ) ) {
            if ( is_array( $sc['keywords_exc'] ) ) {
                $sc['keywords_exc'] = implode( ',', $sc['keywords_exc'] );
            }
            $sc['keywords_exc'] = qcld_seo_help_filter_custom_pattern( $sc['keywords_exc'] );
        }
        if ( empty( $sc['summarylength'] ) || ! is_numeric( $sc['summarylength'] ) ) {
            $sc['summarylength'] = '';
        }
        if ( empty( $sc['default'] ) ) {
            $sc['default'] = apply_filters( 'qcld_seo_help_default_image', $sc['default'], $feed_url );
        }

        return $sc;
    }
}

if ( ! function_exists( 'qcld_seo_help_handle_content_actions' ) ) {
    function qcld_seo_help_handle_content_actions( $actions = '', $type = '' ) {
        // $action_instance       = qcld_seo_help_Rss_Feeds_Actions::instance();
        // $action_instance->type = $type;
        // $action_instance->set_actions( $actions );
        // $action_instance->set_settings( $this->settings );

        if( isset( $type ) && $type == 'item_content' ){
            return ! empty( $actions ) ? $actions : '';
        }


        return $actions;
    }
}



add_filter('qcld_seo_get_post_content_generate', 'qcld_seo_get_post_content_generate', 10, 1 );
if(!function_exists('qcld_seo_get_post_content_generate')){
    function qcld_seo_get_post_content_generate( $topic ) {

        $meta_desc_prompt       = ( isset( $topic ) && !empty( $topic ) ) ? $topic  : '';
        $meta_desc_prompt_data  = ( isset( $topic ) && !empty( $topic ) ) ? $topic  : '';

        if( !empty( $topic ) ){


            $meta_desc_prompt = sprintf( "You are a professional Blog article writer for a new website. Your writing style is friendly amd informative. Write at least 1000-word SEO-optimized article about the broad topic '%s'. The sub topic can be anything relevant to the broad topic. Begin with a catchy, SEO-optimized level 1 heading (#) that captivates the reader. Follow with SEO optimized introductory paragraphs. Then organize the rest of the article into detailed heading tags (##) and lower-level heading tags (###, ####, etc.). Include detailed paragraphs under each heading and subheading that provide in-depth information about the topic. Use bullet points, unordered lists, bold text, underlined text, code blocks etc to enhance readability and engagement.", $meta_desc_prompt );


            $OPENAI_API_KEY = !empty($api_key) ? $api_key : get_option('qcld_seohelp_api_key');



            $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

            if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'palm' ) {



                $qcld_palm_api_key              = get_option('qcld_palm_api_key');
                $qcld_palm_max_token            = get_option('qcld_palm_max_token');
                $qcld_palm_ai_temperature       = get_option('qcld_palm_ai_temperature') ? get_option('qcld_palm_ai_temperature') : 0.1;
                $qcld_palm_ai_candidatecount    = get_option('qcld_palm_ai_candidatecount') ? get_option('qcld_palm_ai_candidatecount') : 1;
                $qcld_palm_ai_top_k             = get_option('qcld_palm_ai_top_k') ? get_option('qcld_palm_ai_top_k') : 40;
                $qcld_palm_ai_top_p             = get_option('qcld_palm_ai_top_p') ? get_option('qcld_palm_ai_top_p') : 0.8;

                $prompt_msg = '{
                                "prompt": { "text": "'.$meta_desc_prompt.'" },
                                "temperature": 1.0,
                                "candidateCount": 2}';

                $apt_key = $qcld_palm_api_key;
                $url     = 'https://generativelanguage.googleapis.com/v1beta2/models/text-bison-001:generateText?key='.$apt_key;
               

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers    = array(
                   "Content-Type: application/json"
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POSTFIELDS,  $prompt_msg );
                $result     = curl_exec($curl);
                curl_close($curl);
                $results    = json_decode($result);

     
                $result_data = isset( $results->candidates[0]->output ) ? trim( $results->candidates[0]->output ) : '';
                return $result_data;
                

            }else{

                $ai_engines     = "text-davinci-003";
                $max_token      = "3000";
                $temperature    = "0.3";
                $ppenalty       = "0";
                $fpenalty       = "0";

                $request_body = [
                    "prompt"            =>  $meta_desc_prompt,
                    "model"             =>  $ai_engines,
                    "max_tokens"        =>  ( int ) $max_token,
                    "temperature"       =>  ( float ) $temperature,
                    "presence_penalty"  =>  ( float ) $ppenalty,
                    "frequency_penalty" =>  ( float ) $fpenalty,
                ];
                $data    = json_encode($request_body);
                $url     = "https://api.openai.com/v1/completions";
                $apt_key = "Authorization: Bearer ". $OPENAI_API_KEY;

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers    = array(
                   "Content-Type: application/json",
                   $apt_key ,
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $result     = curl_exec($curl);
                curl_close($curl);
                $results    = json_decode($result);

                $result_data = isset( $results->choices[0]->text ) ? trim( $results->choices[0]->text ) : '';

                return $result_data;

            }

        }
    }
}