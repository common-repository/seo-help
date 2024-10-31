<?php 

defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'qcld_seo_help_scan_page_callback_func' ) ) {
	function qcld_seo_help_scan_page_callback_func(){

	 	$action = 'admin.php?page=qcld-seo-help-new-scan';

	 	ob_start();


	    if (isset($_POST['qc_seo_linkcheck_scan']) ){
		    //link checker 
		    if ($_POST['qc_seo_linkcheck_scan'] == 'Save Scan'){ 
		      qc_seo_help_link_check_scan();
		    }
		}


	 	global $wpdb;
	    $table_name = $wpdb->prefix . 'qcld_seo_help_scans';
	    $total_items = $wpdb->get_var("SELECT COUNT(scan_id) FROM $table_name");

	    $data_scan 	= $wpdb->get_row("SELECT * FROM $table_name");

	    if(empty($total_items)){
	?>
	<!-- Create a header 'wrap' container -->
	<div class="wrap qcld-seo-help">
	    <div id="icon-themes" class="icon32"></div>
	    <h2><?php esc_html_e('Scan Settings', 'seo-help'); ?></h2>

	    <div id="tabs">
	      	<ul  class="nav-tab-wrapper">
	          	<li><a class="nav-tab" href="#qcld_seo_tab_scan-48585" style="border: 1px solid transparent; text-align:left;"> <?php esc_html_e('Scan', 'seo-help') ?> </a></li>
	      	</ul>
	    
	      	<div id="qcld_seo_tab_scan-48585">
		        <div class="qc_seo_wrapper">
		          	<form method="post" action="<?php echo esc_url($action, 'seo-help'); ?>">
	          		<table class="form-table form-table-scan">
						<tbody>
							<tr>
								<th><label for="qcld_scan_name"><?php esc_html_e('Scan name', 'seo-help') ?></label></th>
								<td><input type="text" name="qcld_scan_name" id="qcld_scan_name" value="" class="regular-text" maxlength="255"></td>
							</tr>					
							<tr>
								<th><label for="qcld-destination-type"><?php esc_html_e('Destination type', 'seo-help') ?></label></th>
								<td>
									<select id="qcld_scan_destination_type" name="qcld_scan_destination_type">
										<option value="internal" disabled="disabled"><?php esc_html_e('Internal URLs', 'seo-help') ?></option>
										<option selected="" value="external"><?php esc_html_e('External URLs', 'seo-help') ?></option>
									</select>
								</td>
							</tr>				
							<tr>
								<th><label for="qcld-time-scope"><?php esc_html_e('Time scope', 'seo-help') ?></label></th>
								<td>
									<select id="qcld-time-scope" name="qcld_time_scope">
										<option selected="" value="anytime"><?php esc_html_e('Anytime content', 'seo-help') ?></option>
										<option value="yesterday"><?php esc_html_e('From yesterday', 'seo-help') ?></option>
										<option value="7days"><?php esc_html_e('Last 7 days', 'seo-help') ?></option>
										<option value="15days"><?php esc_html_e('Last 15 days', 'seo-help') ?></option>
										<option value="month"><?php esc_html_e('One month', 'seo-help') ?></option>
										<option value="3months"><?php esc_html_e('Last 3 months', 'seo-help') ?></option>
										<option value="6months"><?php esc_html_e('Last 6 months', 'seo-help') ?></option>
										<option value="year"><?php esc_html_e('One year', 'seo-help') ?></option>
									</select>
								</td>
							</tr>	
							<tr>
								<th><?php esc_html_e('Post types', 'seo-help') ?></th>
								<td class="qcld-list">
									<?php 
									$post_types = qcld_get_post_types();
									
									foreach($post_types as $key => $post_type){
										?>
										<input type="checkbox" name="qcld_post_type_data[]" id="qcld_post_type_<?php echo esc_attr($key, 'seo-help') ?>" value="<?php echo esc_attr($key, 'seo-help') ?>" <?php if($key == 'post' || $key == 'page'){ echo 'checked'; }  ?> >
										<label for="qcld_post_type_<?php echo esc_attr($key, 'seo-help') ?>"><?php echo esc_attr($post_type, 'seo-help') ?> (<code><?php echo esc_attr($key, 'seo-help') ?></code>)</label><br>
									<?php 
									}
									?>
								</td>
							</tr>						
							<tr>
								<th><?php esc_html_e('Post status', 'seo-help') ?></th>
								<td class="qcld-list"><?php 
									$post_statuses = qcld_get_post_status();
									
									foreach($post_statuses as $key => $post_status){
										?>
										<input type="checkbox" name="qcld_post_status[]" id="qcld_post_status_<?php echo esc_attr($post_status, 'seo-help') ?>" value="<?php echo esc_attr($post_status, 'seo-help') ?>" <?php if($post_status == 'publish'){ echo 'checked'; }  ?> >
										<label for="qcld_post_status_<?php echo esc_attr($post_status, 'seo-help') ?>"><?php echo esc_attr($post_status, 'seo-help') ?></label> &nbsp;
									<?php 
									}
									?>
								</td>
							</tr>
							<tr>
								<th>Notifications<?php esc_html_e('Post status', 'seo-help') ?></th>
								<td class="qcld-list qcld-list-custom">
									<p><?php esc_html_e('Send an e-mail when the scan is completed:', 'seo-help') ?></p>
									<p>
										<?php
	                      $url = get_site_url();
	                      $url = parse_url($url);
	                      $domain = $url['host'];
	                      $support_email = get_bloginfo('admin_email');
	                    ?>
										<input checked="" type="checkbox" id="qcld_notify_default" name="qcld_notify_default" value="on">
										<label for="qcld_notify_default"><?php esc_html_e('Send to the current blog address', 'seo-help') ?> <strong><?php esc_html_e($support_email); ?></strong></label>
									</p>
									<p>
										<input type="checkbox" id="qcld_notify_address" name="qcld_notify_address" value="on">
										<label for="qcld_notify_address"><?php esc_html_e('Send to this eMail address:', 'seo-help') ?></label><br>
										<input type="text" name="qcld_notify_address_email" id="qcld_notify_address_email" value="" class="regular-text" maxlength="255">
									</p>
								</td>
							</tr>	
							<tr>
								<th></th>
								<td class="qcld-list">
									<input type="submit" name="qc_seo_linkcheck_scan" value="Save Scan" class="button button-primary"  >
								</td>
							</tr>	
						</tbody>
					</table>
					</form>
		          	<br>
		          	<br>
		        </div>
	      	</div>
	    </div>
	</div>
	  <!-- /.wrap -->



	<?php 

	 	wp_reset_query();
	 // wp_die();

	}else{
		if (isset($_POST['qc_seo_linkcheck_scan_edit']) ){
	           //link checker 
	            if ( $_POST['qc_seo_linkcheck_scan_edit'] == 'Save Scan Changes' ){ 

	              echo qc_seo_help_simple_broken_link_check_scan_edit(); 
	            }
	        }

	        echo qcld_scan_result_single_page_view($data_scan->scan_id);
	}

	// ob_get_clean();

	}
}

if ( ! function_exists( 'qc_seo_help_link_check_scan' ) ) {
	function qc_seo_help_link_check_scan(){

		global $wpdb;

		$table_data_cheks = $wpdb->prefix.'qcld_seo_help_scans';

		$total_items = $wpdb->get_var("SELECT COUNT(scan_id) FROM $table_data_cheks");

		if($total_items >= 1){
		
		}else{

			$max_threads 	 = 10;
			$connect_timeout = 0;
			$request_timeout = 0;
			
			$scope_data_check_to = isset($_POST['qcld_time_scope'])? substr(trim(stripslashes($_POST['qcld_time_scope'])), 0, 255) : 'anytime';
			
			switch ($scope_data_check_to) {
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


	    	$date_args = array(
					        array(
					            'column' => 'post_date_gmt',
					            'after'  => $time_scope_date,
					        )
						);

		    // WP_Query arguments
		    $qcld_args = array (
		        'post_type'         => isset($_POST['qcld_post_type_data'])? $_POST['qcld_post_type_data'] : array(),
		        'posts_per_page'    => -1,
		        'post_status'       => isset($_POST['qcld_post_status'])? $_POST['qcld_post_status'] : array(),
		        'date_query' 		=> $date_args
		    );

		    // The Query
		    $qcld_query = new WP_Query( $qcld_args );

		    // Update data array
				$updates = array(
					'modified_by' 			=> get_current_user_id(),
					'modified_at' 			=> current_time('mysql', true),
					'name' 							=> isset($_POST['qcld_scan_name'])? substr(trim(stripslashes($_POST['qcld_scan_name'])), 0, 255) : '',
					'max_threads' 			=> $qcld_query->found_posts,
					'connect_timeout' 	=> $connect_timeout,
					'request_timeout' 	=> $request_timeout,
				);

			// Initialize
			$config = array();
			
				
			// Set e-mail settings
			$config['notify_default']				= isset($_POST['qcld_notify_default']) ? substr(trim(stripslashes($_POST['qcld_notify_default'])), 0, 255) : '';
			$config['notify_address']				= isset($_POST['qcld_notify_address']) ? substr(trim(stripslashes($_POST['qcld_notify_address'])), 0, 255) : '';
			$config['notify_address_email']	= isset($_POST['qcld_notify_address_email']) ? substr(trim(stripslashes($_POST['qcld_notify_address_email'])), 0, 255) : '';
			
			
			// General tab
			$config['destination_type'] 	= isset($_POST['qcld_scan_destination_type']) ? substr(trim(stripslashes($_POST['qcld_scan_destination_type'])),0,255) :'all';
			$config['time_scope'] 			= isset($_POST['qcld_time_scope']) ? substr(trim(stripslashes($_POST['qcld_time_scope'])), 0, 255) : 'anytime';
			$config['link_types']				= array();
			$config['redir_status']			= 'on';
			
			// Content options tab
			$config['post_types']				= isset($_POST['qcld_post_type_data'])? $_POST['qcld_post_type_data'] : array();
			$config['post_status']			= isset($_POST['qcld_post_status'])? $_POST['qcld_post_status'] : array();
			
			// Add to update
			if (!empty($config))
				$updates['config'] 				= @json_encode($config);

			$hash = md5(rand(0, 9999).microtime().rand(0, 9999));

			$wpdb->insert($wpdb->prefix.'qcld_seo_help_scans', array_merge($updates, array('status' => 'wait', 'hash' => $hash, 'created_at' => current_time('mysql', true))));
		
			// Restore original Post Data
		   /* wp_reset_postdata();

		    $output = '<div class="updated below-h2" id="message"><p>Scan Data Successfully Saved!</p></div>';
		    echo $output;*/

		}

	}
}


if ( ! function_exists( 'qcld_get_post_types' ) ) {
	function qcld_get_post_types($output = 'keys-names') {
			
		// Current avoid post types
		$avoid_post_types = apply_filters('qcld_avoid_post_types', array_map('trim', explode(',', 'attachment, nav_menu_item, revision, custom_css, customize_changeset, user_request, wp_block, oembed_cache')));
		if (empty($avoid_post_types) || !is_array($avoid_post_types))
			return false;
		
		// Compute allowed post types
		$post_types = get_post_types(array(), 'objects');
		$allowed_post_types = array_diff_key($post_types, array_fill_keys($avoid_post_types, true));
		
		// Return key-name values
		if ('keys-names' == $output) {
			$keys_names = array();
			foreach ($allowed_post_types as $key => $post_type)
				$keys_names[$key] = $post_type->labels->name;
			return $keys_names;
			
		// Return keys
		} elseif ('keys' == $output) {
			return array_keys($allowed_post_types);
		
		// Return names
		} elseif ('names' == $output) {
			$names = array();
			foreach ($allowed_post_types as $key => $post_type)
				$names[] = $post_type->labels->name;
			return $names;
		}
		
		// Default
		return $allowed_post_types;
	}
}

if ( ! function_exists( 'qcld_get_post_status' ) ) {
	function qcld_get_post_status() {
		$post_status_allowed = array_map('trim', explode(',', 'publish, future, draft, pending, private, trash'));
		$post_status = apply_filters('qcld_allow_post_status', $post_status_allowed);
		return (empty($post_status) && !is_array($post_status))? false : array_intersect($post_status, $post_status_allowed);
	}
}
