<?php 

defined('ABSPATH') or die("You can't access this file directly.");

/**
 * scan edit function
 */
if ( ! function_exists( 'qc_seo_help_simple_broken_link_check_scan_start_crawler' ) ) {
    function qc_seo_help_simple_broken_link_check_scan_start_crawler($id){

        global $wpdb;
        $id  = isset($id) ? $id : sanitize_text_field($_REQUEST['id']);
        $table_name = $wpdb->prefix . "qcld_seo_help_urls_locations";

        $action = 'admin.php?page=qcld-seo-help-scan&view=view_scan&id='.$id;

        // Delete Previous Scan Result
        if (!empty($id)) {
            $wpdb->query("DELETE FROM $table_name WHERE scan_id IN($id)");
        }

        $table_name_scans = $wpdb->prefix . 'qcld_seo_help_scans';
        $data_scan = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_scans where scan_id=%d", $id));

        $config = json_decode($data_scan->config);

    	$post_types = $config->post_types;
    	$post_status = $config->post_status;
    	$time_scope = $config->time_scope;


        // scan running
        $wpdb->update($wpdb->prefix.'qcld_seo_help_scans', array('status' => 'running') , array('scan_id' => $id ));

    	    switch ($time_scope) {
    		    case 'yesterday':
    		        $time_scope_date = esc_html__('2 days ago', 'seo-help');
    		        break;
    		    case '7days':
    		        $time_scope_date = esc_html__('7 days ago', 'seo-help');
    		        break;
    		    case '15days':
    		        $time_scope_date = esc_html__('15 days ago', 'seo-help');
    		        break;
    		    case 'month':
    		        $time_scope_date = esc_html__('30 days ago', 'seo-help');
    		        break;
    		    case '3months':
    		        $time_scope_date = esc_html__('90 days ago', 'seo-help');
    		        break;
    		    case '6months':
    		        $time_scope_date = esc_html__('180 days ago', 'seo-help');
    		        break;
    		    case 'year':
    		        $time_scope_date = esc_html__('365 days ago', 'seo-help');
    		        break;
    		    default:
    		        $time_scope_date = '';
    		}


        $date_args =  array(
    			        array(
    			            'column' => 'post_date_gmt',
    			            'after'  => $time_scope_date,
    			        )
    				);

        // WP_Query arguments
        $args = array (
            'post_type'         => $post_types,
            'posts_per_page'    => -1,
            'post_status'       => $post_status,
            'date_query' 		=> $date_args
        );

        // The Query
        $query = new WP_Query( $args );

        // The Loop
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $content = get_the_content(get_the_ID());


                /*if (preg_match_all('#(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\-nocookie\.com\/embed\/|youtube\.com\/(?:embed\/|v\/|e\/|\?v=|shared\?ci=|watch\?v=|watch\?.+&v=))([-_A-Za-z0-9]{10}[AEIMQUYcgkosw048])(.*?)\b#s', $content, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {

                        //var_dump( $match[1] );
                        //wp_die();

                        $response       = wp_remote_get($match[0], array('timeout' => 50));
                        $response_code  = wp_remote_retrieve_response_code($response);
                        
                        $editstring = get_edit_post_link(get_the_ID());
                        $the_title  = the_title('<a href="' . $editstring . '">', '</a>', false);

                         $wpdb->insert($table_name,  
                            array(
                                'scan_id'       	=> $id, 
                                'object_id'     	=> get_the_ID(),
                                'object_type'  		=> get_post_type(get_the_ID()),
                                'object_post_type'  => get_post_type(get_the_ID()),
                                'object_field'  	=> get_the_permalink(get_the_ID()),
                                'object_edit'  		=> get_edit_post_link(get_the_ID()),
                                'object_view'  		=> get_the_permalink(get_the_ID()),
                                'object_trash'  	=> get_delete_post_link(get_the_ID()),
                                'chunk'         	=> isset($match[0]) ? $match[0] : ' ',
                                'anchor'        	=> isset($match[1]) ? $match[1] : ' ',
                                'raw_url'       	=> parse_url($match[0], PHP_URL_HOST),
                                'status_code'   	=> $response_code,
                                'content'       	=> isset($the_title) ? $the_title : ' ',
                                'created_at'    	=> current_time('mysql', true), 
                                'started_at'    	=> current_time('mysql', true),
                                'request_at'    	=> current_time('mysql', true)
                            )
                        );

                    }
                }else */

                if ( preg_match_all('/(<a[^>]+href=["|\'](.+)["|\'][^>]*>)(.*)<\/a>/isUu', $content, $matches, PREG_SET_ORDER) > 0 ) {
                    
                    foreach ($matches as $match) {

                        $response       = wp_remote_get($match[2], array('timeout' => 50));
                        $response_code  = wp_remote_retrieve_response_code($response);
                        
                        $editstring = get_edit_post_link(get_the_ID());
                        $the_title  = the_title('<a href="' . $editstring . '">', '</a>', false);

                         $wpdb->insert($table_name,  
                            array(
                                'scan_id'           => $id, 
                                'object_id'         => get_the_ID(),
                                'object_type'       => get_post_type(get_the_ID()),
                                'object_post_type'  => get_post_type(get_the_ID()),
                                'object_field'      => get_the_permalink(get_the_ID()),
                                'object_edit'       => get_edit_post_link(get_the_ID()),
                                'object_view'       => get_the_permalink(get_the_ID()),
                                'object_trash'      => get_delete_post_link(get_the_ID()),
                                'chunk'             => isset($match[2]) ? $match[2] : ' ',
                                'anchor'            => isset($match[0]) ? $match[0] : ' ',
                                'raw_url'           => parse_url($match[2], PHP_URL_HOST),
                                'status_code'       => $response_code,
                                'content'           => isset($the_title) ? $the_title : ' ',
                                'created_at'        => current_time('mysql', true), 
                                'started_at'        => current_time('mysql', true),
                                'request_at'        => current_time('mysql', true)
                            )
                        );

                    }

                }else if ( preg_match_all( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $content, $matches, PREG_SET_ORDER) > 0 ) {

                    foreach ($matches as $match) {

                        $response = wp_remote_get($match[0], array('timeout' => 500 ));
                        $response_code = wp_remote_retrieve_response_code($response);
                        
                        $editstring = get_edit_post_link(get_the_ID());
                        $the_title  = the_title('<a href="' . $editstring . '">', '</a>', false);

                        $row_url = !empty(parse_url($match[0], PHP_URL_HOST)) ? parse_url($match[0], PHP_URL_HOST) : ' ';

                         $wpdb->insert($table_name,  
                            array(
                                'scan_id'           => $id, 
                                'object_id'         => get_the_ID(),
                                'object_type'       => get_post_type(get_the_ID()),
                                'object_post_type'  => get_post_type(get_the_ID()),
                                'object_field'      => get_the_permalink(get_the_ID()),
                                'object_edit'       => get_edit_post_link(get_the_ID()),
                                'object_view'       => get_the_permalink(get_the_ID()),
                                'object_trash'      => get_delete_post_link(get_the_ID()),
                                'chunk'             => isset($match[0]) ? $match[0] : ' ',
                                'anchor'            => isset($match[2]) ? $match[2] : ' ',
                                'raw_url'           => $row_url,
                                'status_code'       => $response_code,
                                'content'           => isset($the_title) ? $the_title : ' ',
                                'created_at'        => current_time('mysql', true), 
                                'started_at'        => current_time('mysql', true),
                                'request_at'        => current_time('mysql', true)
                            )
                        );

                    }

                }



               
            }
        }

        $wpdb->update($wpdb->prefix.'qcld_seo_help_scans', array('status' => 'completed') , array('scan_id' => $id ));

    	// Restore original Post Data
        wp_reset_postdata();
        $output = '<div class="updated below-h2" id="message"><p>'. esc_html('Scan Successfully Updated!', 'seo-help').'</p></div>';
        echo $output;

    }
}