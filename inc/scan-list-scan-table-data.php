<?php

defined('ABSPATH') or die("You can't access this file directly.");

/**
 * PART 2. Defining Custom Table List
 * ============================================================================
 */

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Qcld_seo_help_Scan_List_Table class that will display our custom table
 * records in nice table
 */
class Qcld_seo_help_Scan_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular'  => 'seo_help_menu',
            'plural'    => esc_html__('Seo Help', 'seo-help'),
            //does this table support ajax?
            'ajax'      => true
        ));
    }


    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     */
    function column_config($item)
    {
        
    	$config = json_decode($item['config']);

    	$data_item = '';

    	$data_item .= '<p><b>'.esc_html__('Scope', 'seo-help').'</b>: ';
        $data_item_scope = '';
    	if(!empty($config->destination_type)):
    	$data_item_scope .= esc_html__('Destination type:', 'seo-help').' '. $config->destination_type.', ';
    	endif;
    	if(!empty($config->destination_type)):
    	$data_item_scope .=  esc_html__('Time Scope:', 'seo-help').' '. $config->time_scope.', ';
    	endif;
    	if(!empty($config->notify_default)):
    	$data_item_scope .=  esc_html__('Notify Default:', 'seo-help').' '. $config->notify_default.', ';
    	endif;
    	if(!empty($config->notify_address)):
    	$data_item_scope .=  esc_html__('Notify Address:', 'seo-help').' '. $config->notify_address.', ';
    	endif;
    	if(!empty($config->notify_address_email)):
    	$data_item_scope .= esc_html__('Notify Email:', 'seo-help').' '. $config->notify_address_email.', ';
    	endif;
        $data_item .= substr($data_item_scope, 0, -2);
    	$data_item .= '</p>';

    	$data_item .= '<p><b>'.esc_html__('Post types', 'seo-help').'</b>: ';
        $data_item_post = '';
    	foreach($config->post_types as $post){
    		$data_item_post .= $post.', ';

    	}

        $data_item .= substr($data_item_post, 0, -2);
    	$data_item .= ' </p>';

    	$data_item .= '<p><b>'.esc_html__('Post types', 'seo-help').'</b>: ';
        $data_item_status = '';
    	foreach($config->post_status as $status){
    		$data_item_status .= $status.', ';

    	}
        $data_item .= substr($data_item_status, 0, -2);
    	$data_item .= ' </p>';

    	return $data_item;
    	
    	
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     */
    function column_name($item)
    {
        if($item['status'] == 'running' || $item['status'] == 'completed'){
            $actions = array(
                'show_results'  => sprintf('<a href="?page=qcld-seo-help-scan&view=view_scan&id=%s">%s</a>', $item['scan_id'], __('Show All results', 'qcld-seo-help')),
                'show_resultss'  => sprintf('<a href="?page=qcld-seo-help-scan&view=view_scan&id=%s&msg=404">%s</a>', $item['scan_id'], __('Show 404s Only', 'qcld-seo-help')),
                'edit'          => sprintf('<a href="?page=qcld-seo-help-scan&view=edit_scan&id=%s">%s</a>', $item['scan_id'], __('Edit Scan', 'qcld-seo-help')),
                'delete'        => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['scan_id'], __('Delete', 'qcld-seo-help')),
            );
        }else{
            $actions = array(
                'show_results'  => sprintf('<a href="?page=qcld-seo-help-scan&view=view_scan&id=%s">%s</a>', $item['scan_id'], __('Show All results', 'qcld-seo-help')),
                'edit'          => sprintf('<a href="?page=qcld-seo-help-scan&view=edit_scan&id=%s">%s</a>', $item['scan_id'], __('Edit Scan', 'qcld-seo-help')),
                'start_crawler' => sprintf('<a href="?page=qcld-seo-help-scan&view=start_crawler&id=%s">%s</a>', $item['scan_id'], __('Start crawler', 'qcld-seo-help')),
                'delete'        => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['scan_id'], __('Delete', 'qcld-seo-help')),
            );
        }

        // Created date
        $time_created = strtotime( $item['created_at'].' UTC');
        $offset_time = get_option('gmt_offset') * HOUR_IN_SECONDS;
        
        // Current dates
        $today_date = gmdate('d/m/Y', time() + $offset_time);
        $yesterday_date = gmdate('d/m/Y', time() + $offset_time - 86400);
        
        // Local date and hour
        $created_date = gmdate('d/m/Y', $time_created + $offset_time);
        $created_hour = gmdate('H:i', $time_created + $offset_time);

        // Check today
        if ($created_date == $today_date) {
            
            // Created today
            $timeinfo = sprintf(__('Created today at %s', 'qcld-seo-help'), $created_hour);
        
        // Yesterday
        } elseif ($created_date == $yesterday_date) {
            
            // Created yesterday
            $timeinfo = sprintf(__('Created yesterday at %s', 'qcld-seo-help'), $created_hour);
        
        // Any date
        } else {
            
            // Created date and hour
            $timeinfo = sprintf(__('Created %s at %s', 'qcld-seo-help'), $created_date, $created_hour);
        }

        $status_info = $item['status'];
        if($status_info == 'running'){
            $status_info_msg = '<span class="running_msg">'.esc_html('Running', 'seo-help').'</span>';
            $ready_to_crawler = ''; 
            $scan_results = '';

        }else if($status_info == 'completed'){
            $status_info_msg = '<span class="completed_msg">'.esc_html('Completed', 'seo-help').'</span>';
            $ready_to_crawler = '';

            global $wpdb;
            $id  = $item['scan_id'];
            $table_name_scan = $wpdb->prefix . "qcld_seo_help_urls_locations";
            $total_result_counts = $wpdb->get_var("SELECT COUNT(loc_id) FROM $table_name_scan WHERE scan_id IN($id)");

            $total_unique_url = $wpdb->get_var("SELECT COUNT( DISTINCT(raw_url)) FROM $table_name_scan WHERE scan_id IN($id)");

            $scan_results = '<p><b>'.$total_result_counts.'</b> '.esc_html('Results', 'seo-help').' - <b>'.$total_unique_url.'</b> '.esc_html('Unique URLs', 'seo-help').'</p>';

        }else{

            $status_info_msg = '';   
            $scan_results = '';
            $ready_to_crawler = '<p>'.esc_html('Ready to', 'seo-help').' <a class="qcld_start_crawler" href="?page=qcld-seo-help-scan&view=start_crawler&id='.$item['scan_id'].'">'.esc_html('start the crawler', 'seo-help').'</a></p>';         
            //$ready_to_crawler = '<p>Ready to <a class="qcld_start_crawler" href="#" data-scan_id="'.$item['scan_id'].'">Start The Crawler</a></p>';         
        }

        $scan_title = '<a href="?page=qcld-seo-help-scan&view=view_scan&id='.$item['scan_id'].'">'.$item['name'].'</a>';
                
        $result = $status_info_msg. $scan_title . $ready_to_crawler.'<p><i class="fa fa-clock-o" aria-hidden="true"></i> '.$timeinfo.'</p>'.$scan_results;

        return sprintf('%s %s',
            $result,
            $this->row_actions($actions)
        );
    }

     function column_status($item)
    {
    	return $item['status'];
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['scan_id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     */
    function get_columns()
    {
        $columns = array(
            'cb' 		=> '<input type="checkbox" />',
            'name' 		=> esc_html__('Scan info', 'seo-help'),
           // 'config' 	=> esc_html__('Configuration', 'seo-help'),
        );
        //return $columns;
		
		// All columns
		return array_merge($columns, array(
			'config' 	=> esc_html__('Configuration',	'seo-help'),
            'status' 	=> esc_html__('Status', 'seo-help'),
		));

    }

    /**
     * This method return columns that may be used to sort table
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' 		=> array('name', true),
            'config' 	=> array('config', false),
        );

        return $sortable_columns;
    }

    /**
     * Return array of bult actions if has any
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * This method processes bulk actions
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'qcld_seo_help_scans';
        $table_name_location = $wpdb->prefix . 'qcld_seo_help_urls_locations';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE scan_id IN($ids)");
                $wpdb->query("DELETE FROM $table_name_location WHERE scan_id IN($ids)");
            }
        }
    }

    /**
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'qcld_seo_help_scans'; 

        $per_page = 10;

        $columns = $this->get_columns();
        // Data items
		//$columns = $this->setup_items();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(scan_id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'scan_id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' 	=> $total_items, // total items defined above
            'per_page' 		=> $per_page, // per page constant defined at top of method
            'total_pages' 	=> ceil($total_items / $per_page) // calculate pages count
        ));
    }
}


/**
 * List page handler
 */
if ( ! function_exists( 'cltd_seo_help_scan_result_view' ) ) {
    function cltd_seo_help_scan_result_view(){
        global $wpdb;

        if(isset($_REQUEST['view']) && ($_REQUEST['view'] == 'edit_scan') && !empty($_REQUEST['id'])){


        	if (isset($_POST['qc_seo_linkcheck_scan_edit']) ){
               //link checker 
                if ( $_POST['qc_seo_linkcheck_scan_edit'] == 'Save Scan Changes' ){ 

                  echo qc_seo_help_simple_broken_link_check_scan_edit(); 
                }
            }

            echo qcld_scan_result_single_page_view($_REQUEST['id']);


        }else if(isset($_REQUEST['view']) && ($_REQUEST['view'] == 'view_scan') && !empty($_REQUEST['id'])){
           /* echo cltd_seo_help_scan_header_result_data_view();*/
            echo cltd_seo_help_scan_result_data_view();

        }else if(isset($_REQUEST['view']) && ($_REQUEST['view'] == 'start_crawler') && !empty($_REQUEST['id'])){

        	echo qc_seo_help_simple_broken_link_check_scan_start_crawler($_REQUEST['id']);
            echo qcld_scan_table_result_data_mail($_REQUEST['id']);

            echo cltd_seo_help_scan_result_data_view();

        }else{

            $table = new Qcld_seo_help_Scan_List_Table();
            $table->prepare_items();

            $message = '';
            if ('delete' === $table->current_action()) {
                $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'seo-help'), count($_REQUEST['id'])) . '</p></div>';
            }
        
        ?>

    	<div class="wrap">

    	    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    	    <h2><?php esc_html_e('Scan Results', 'seo-help')?> <!-- <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=qcld-seo-help-new-scan');?>"><?php esc_html_e('Add New Scan', 'seo-help')?></a> -->
    	    </h2>
    	    <?php echo $message; ?>

    	    <form id="qcld-seo-help-scan-table" method="GET">
    	        <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page'], 'seo-help'); ?>"/>
                <p class="qcld-seo-help-scan-msg"><i><?php esc_html_e('Set up Scan Settings from', 'seo-help')?> <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=qcld-seo-help-new-scan');?>"> <?php esc_html_e('Broken Link Scan Settings', 'seo-help')?></a> <?php esc_html_e('page 1st', 'seo-help')?></i></p>
    	        <?php $table->display() ?>
    	    </form>

    	</div>


    <?php
    	}

    }
}

if ( ! function_exists( 'qcld_scan_result_single_page_view' ) ) {
    function qcld_scan_result_single_page_view($id){
        $back_url = 'admin.php?page=qcld-seo-help-scan';
    	$action = 'admin.php?page=qcld-seo-help-scan&view=edit_scan&id='.$id;
    	//return 'result page view by id'. $id;
    	global $wpdb;
        $table_name = $wpdb->prefix . 'qcld_seo_help_scans'; // do not forget about tables prefix
        $datas = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name where scan_id=%d", $id));

        //print_r($datas);
        foreach($datas as $data):

        	$config = json_decode($data->config);

        	$destination_type_all = '';
        	$destination_type_internal = '';
        	$destination_type_external = '';

        	if(!empty($config->destination_type == 'all' )){
        		$destination_type_all = 'selected';
        	}else if(!empty($config->destination_type == 'internal' )){
        		$destination_type_internal = 'selected';
        	}else if(!empty($config->destination_type == 'external' )){
        		$destination_type_external = 'selected';
        	}


        $output ='<div class="wrap qcld-seo-help">
        	<div id="icon-themes" class="icon32"></div>
    	    <h3>'.esc_html('Scan Settings', 'seo-help').'</h3>

    	    <div id="tabs">
    	      	<ul  class="nav-tab-wrapper">
    	          	<li><a class="nav-tab" href="#qcld_seo_tab_scan-48585" style="border: 1px solid transparent; text-align:left;"> Scan </a></li>
    	      	</ul>
    	    
    	      	<div id="qcld_seo_tab_scan-48585">
    		        <div class="qc_seo_wrapper">
    		          	
    		          	<form method="post" action="'.esc_url($action, 'seo-help').'">
    		          	<input type="hidden" name="scan_id" id="scan-id" value="'.esc_attr($id, 'seo-help').'" />
    	          		<table class="form-table form-table-scan">
    						<tbody>
    							<tr>
    								<th><label for="qcld_scan_name">'.esc_html('Scan name ', 'seo-help').'</label></th>
    								<td><input type="text" name="qcld_scan_name" id="qcld_scan_name" value="'.esc_attr($data->name, 'seo-help').'" class="regular-text" maxlength="255"></td>
    							</tr>					
    							<tr>
    								<th><label for="qcld-destination-type">'.esc_html('Destination type', 'seo-help').'</label></th>
    								<td>
    									<select id="qcld_scan_destination_type" name="qcld_scan_destination_type">';
    										switch ($config->destination_type){
    										    case 'all':
    										        $output .='<option  value="internal" disabled="disabled">'.esc_html('Internal URLs', 'seo-help').'</option>
                                                               <option selected value="external">'.esc_html('External URLs', 'seo-help').'</option>';
    										        break;
    										    case 'external':
                                                    $output .='<option  value="internal" disabled="disabled">'.esc_html('Internal URLs', 'seo-help').'</option>
                                                               <option selected value="external">'.esc_html('External URLs', 'seo-help').'</option>';
    										        break;     
    										}
    										$output .='
    									</select>
    								</td>
    							</tr>				
    							<tr>
    								<th><label for="qcld-time-scope">'.esc_html('Time scope', 'seo-help').'</label></th>
    								<td>
    									<select id="qcld-time-scope" name="qcld_time_scope">';
    										switch ($config->time_scope){
    										    case 'anytime':
    										        $output .='<option selected value="anytime">'.esc_html('Anytime content', 'seo-help').'</option>';
    										        break;
    										    case 'yesterday':
    										        $output .='<option value="yesterday">'.esc_html('From yesterday', 'seo-help').'</option>';
    										        break;
    										    case '7days':
    										        $output .='<option value="7days">'.esc_html('Last 7 days', 'seo-help').'</option>';
    										        break;
    										    case '15days':
    										        $output .='<option value="15days">'.esc_html('Last 15 days', 'seo-help').'</option>';
    										        break;
    										    case 'month':
    										        $output .='<option value="month">'.esc_html('One month', 'seo-help').'</option>';
    										        break;
    										    case '3months':
    										        $output .='<option value="3months">'.esc_html('Last 3 months', 'seo-help').'</option>';
    										        break;
    										    case '6months':
    										        $output .='<option value="6months">'.esc_html('Last 6 months', 'seo-help').'</option>';
    										        break;
    										    case 'year':
    										        $output .='<option value="year">'.esc_html('One year', 'seo-help').'</option>';
    										        break;     
    										}
    										$output .='<option value="anytime">'.esc_html('Anytime content', 'seo-help').'</option>
    										<option value="yesterday">'.esc_html('From yesterday', 'seo-help').'</option>
    										<option value="7days">'.esc_html('Last 7 days', 'seo-help').'</option>
    										<option value="15days">'.esc_html('Last 15 days', 'seo-help').'</option>
    										<option value="month">'.esc_html('One month', 'seo-help').'</option>
    										<option value="3months">'.esc_html('Last 3 months', 'seo-help').'</option>
    										<option value="6months">'.esc_html('Last 6 months', 'seo-help').'</option>
    										<option value="year">'.esc_html('One year', 'seo-help').'</option>
    									</select>
    								</td>
    							</tr>	
    							<tr>
    								<th>Post types</th>
    								<td class="qcld-list">';
    									
    									$post_types = qcld_get_post_types();
    									
    									foreach($post_types as $key => $post_type){
    										
    										
    										$output .='<input type="checkbox" name="qcld_post_type_data[]" id="qcld_post_type_'.esc_attr($key, 'seo-help').'" value="'.esc_attr($key, 'seo-help').'"';foreach($config->post_types as $post){
    												if($key == $post){
    													$output .= 'checked';
    												}
    									    	}
    										$output .='>';
    										$output .= '<label for="qcld_post_type_'.esc_attr($key, 'seo-help').'"> '.esc_attr($post_type, 'seo-help').' (<code> '.esc_attr($key, 'seo-help').' </code>)</label><br>';
    									
    									}
    								
    								$output .='</td>
    							</tr>						
    							<tr>
    								<th>'.esc_html('Post status', 'seo-help').'</th>
    								<td class="qcld-list">';
    							
    									$post_statuses = qcld_get_post_status();
    									
    									foreach($post_statuses as $key => $post_status){
    										
    										$output .='<input type="checkbox" name="qcld_post_status[]" id="qcld_post_status_'.esc_attr($post_status, 'seo-help').'" value="'.esc_attr($post_status, 'seo-help').'" '; 
    										foreach($config->post_status as $status){
    												if($post_status == $status){
    													$output .= 'checked';
    												}
    									    	}

    										$output .='>';
    										$output .='<label for="qcld_post_status_'.esc_attr($post_status, 'seo-help').'"> '.esc_attr($post_status, 'seo-help').'</label> &nbsp;';
    									
    									}
    									
    								$output .='</td>
    							</tr>
    							<tr>
    								<th>'.esc_html('Notifications', 'seo-help').'</th>
    								<td class="qcld-list qcld-list-custom">
    									<p>'.esc_html('Send an e-mail when the scan is completed:', 'seo-help').'</p>
    									<p>';
    										
    		                                    $url 			= get_site_url();
    		                                    $url 			= parse_url($url);
    		                                    $domain 		= $url['host'];
    		                                    $support_email 	= get_bloginfo('admin_email');
    		                                    $default_notify = $config->notify_default;
    		                                    if(!empty($default_notify)){
    		                                    	$default_notify_check = 'checked';
    		                                    }else{
    		                                    	$default_notify_check = '';
    		                                    }
    		                                    $default_notify_address = $config->notify_address;
    		                                    if(!empty($default_notify_address)){
    		                                    	$default_notify_address = 'checked';
    		                                    }else{
    		                                    	$default_notify_address = '';
    		                                    }
    		                                    $notify_address_email = $config->notify_address_email;
    		                                    if(!empty($notify_address_email)){
    		                                    	$notify_address_email = $notify_address_email;
    		                                    }else{
    		                                    	$notify_address_email = '';
    		                                    }
    	                                    
    										$output .='<input type="checkbox" id="qcld_notify_default" name="qcld_notify_default" value="on" '.esc_attr($default_notify_check, 'seo-help').'>
    										<label for="qcld_notify_default">'.esc_html('Send to the current blog address', 'seo-help').' <strong> '.esc_attr($support_email, 'seo-help').'</strong></label>
    									</p>
    									<p>
    										<input type="checkbox" id="qcld_notify_address" name="qcld_notify_address" value="on" '.esc_attr($default_notify_address, 'seo-help').'>
    										<label for="qcld_notify_address">'.esc_html('Send to this eMail address:', 'seo-help').'</label><br>
    										<input type="text" name="qcld_notify_address_email" id="qcld_notify_address_email" value="'.esc_attr($notify_address_email, 'seo-help').'" class="regular-text" maxlength="255">
    									</p>
    								</td>
    							</tr>	
    							<tr>
    								<th></th>
    								<td class="qcld-list">
    									<input type="submit" name="qc_seo_linkcheck_scan_edit" value="Save Scan Changes" class="button button-primary"  >
    								</td>
    							</tr>	
    						</tbody>
    					</table>
    					</form>
    		          	<br>
    		          	<br>
                          <p><a href="'.esc_url($back_url).'"> '.esc_html('Back To Scan Results').' </a></p>
    		        </div>
    	      	</div>
    	    </div>
    	</div>';

        	
        endforeach;

        wp_reset_postdata();
        return $output;


    }
}

/**
 * scan edit function
 */
if ( ! function_exists( 'qc_seo_help_simple_broken_link_check_scan_edit' ) ) {
    function qc_seo_help_simple_broken_link_check_scan_edit(){


            global $wpdb;

            $scan_id         = isset($_POST['scan_id']) ? sanitize_text_field($_POST['scan_id']) : '';
            $max_threads     = 10;
            $connect_timeout = 0;
            $request_timeout = 0;
            
            // Update data array
            $updates = array(
                'status'            => 'wait',
                'modified_by'       => get_current_user_id(),
                'modified_at'       => current_time('mysql', true),
                'name'              => isset($_POST['qcld_scan_name']) ? substr(trim(stripslashes($_POST['qcld_scan_name'])), 0, 255) : '',
                'max_threads'       => $max_threads,
                'connect_timeout'   => $connect_timeout,
                'request_timeout'   => $request_timeout,
            );

            // Initialize
            $config = array();
        
            
            // Set e-mail settings
            $config['notify_default']       = isset($_POST['qcld_notify_default'])? substr(trim(stripslashes($_POST['qcld_notify_default'])), 0, 255) : '';
            $config['notify_address']       = isset($_POST['qcld_notify_address'])? substr(trim(stripslashes($_POST['qcld_notify_address'])), 0, 255) : '';
            $config['notify_address_email'] = isset($_POST['qcld_notify_address_email'])? substr(trim(sanitize_email($_POST['qcld_notify_address_email'])), 0, 255) : '';

            // General tab
            $config['destination_type']     = isset($_POST['qcld_scan_destination_type'])? substr(trim(stripslashes($_POST['qcld_scan_destination_type'])),0,255) :'all';
            $config['time_scope']           = isset($_POST['qcld_time_scope'])? substr(trim(stripslashes($_POST['qcld_time_scope'])), 0, 255) : 'anytime';
            $config['link_types']           = array();
            $config['redir_status']         = 'on';
            
            // Content options tab
            $config['post_types']           = isset($_POST['qcld_post_type_data'])? $_POST['qcld_post_type_data'] : array();
            $config['post_status']          = isset($_POST['qcld_post_status'])? $_POST['qcld_post_status'] : array();

            
            // Add to update
            if (!empty($config))
                $updates['config'] = @json_encode($config);

            $wpdb->update($wpdb->prefix.'qcld_seo_help_scans', $updates , array('scan_id' => $scan_id ));

        // Restore original Post Data
        wp_reset_postdata();
        $output = '<div class="updated below-h2" id="message"><p>'.esc_html('Scan Data Successfully Updated!', 'seo-help').'</p></div>';
        echo $output;

    }
}
