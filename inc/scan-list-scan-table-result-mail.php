<?php 

defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'qcld_scan_table_result_data_mail' ) ) {
	function qcld_scan_table_result_data_mail($id){

		global $wpdb;
	    $id  		= $id;
	    $table_name = $wpdb->prefix . "qcld_seo_help_urls_locations";

	    $table_name_scans 	= $wpdb->prefix . 'qcld_seo_help_scans';
	    $data_scan 			= $wpdb->get_row("SELECT * FROM $table_name_scans where scan_id=".$id);

	    // $data_scan 			= $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_scans where scan_id=%d", $id));
	    $data_scan_results = $wpdb->get_results("SELECT * FROM $table_name  WHERE scan_id IN($id)");

	    $config = json_decode($data_scan->config);

		$notify_default 		= $config->notify_default;
		$notify_address 		= $config->notify_address;
		$notify_address_email 	= $config->notify_address_email;
		
	    $subject                = esc_html('Scan Result Details', 'seo-help');

	  	$url = get_site_url();
	    $url = parse_url($url);
	    $domain = $url['host'];
	    $support_email = get_bloginfo('admin_email');

	    if($notify_default == 'on'){

		    $toEmail = $support_email;

		    if (filter_var($toEmail, FILTER_VALIDATE_EMAIL) === false) {
		     
		    } else {
		        //build email body
		        $bodyContent = "";

		        $bodyContent .= "<p><strong>".esc_html('Scan Result Details:', 'seo-help')."</strong></p><hr>";

		        $bodyContent .= ' <table>
				    <thead>
				      <tr>
				        <th>'.esc_html('URL', 'seo-help').'</th>
				        <th>'.esc_html('Status', 'seo-help').'</th>
				        <th>'.esc_html('Anchor Text', 'seo-help').'</th>
				        <th>'.esc_html('Content', 'seo-help').'</th>
				      </tr>
				    </thead><tbody>';
				    
				      

		        foreach($data_scan_results as $result){

		            $status_check = $result->status_code;
		            switch ($status_check) {
			            case "200":
			                $status_check = '<p><strong style="color:green">'.esc_attr($status_check, 'seo-help'). ' '.esc_html('OK', 'seo-help').' </strong></p>';
			                break;
			            default:
			                $status_check = '<p><strong style="color:red">'.esc_attr($status_check, 'seo-help'). ' '.esc_html('Not Found', 'seo-help').' </strong></p>';
			        }

			        $bodyContent .= "<tr>
				        <td>'".esc_attr($result->chunk, 'seo-help')."'</td>
				        <td>'".esc_attr($status_check, 'seo-help')."'</td>
				        <td>'".esc_attr($result->anchor, 'seo-help')."'</td>
				        <td>'".esc_attr($result->content, 'seo-help')."'</td>
				      </tr>";

		        }

		        $bodyContent .= "</tbody></table><p>".esc_html('Mail Generated on:', 'seo-help')." " . date("F j, Y, g:i a") . "</p>";


		        $to = $toEmail;
		        $body = $bodyContent;
		        $headers = array();
		        $headers[] = 'Content-Type: text/html; charset=UTF-8';
		        $headers[] = 'From: '.esc_html('Scan Result', 'seo-help').'  <' . esc_attr($support_email, 'seo-help') . '>';
		        $headers[] = 'Reply-To:  '.esc_html('Scan Result', 'seo-help').' <' . esc_attr($toEmail, 'seo-help') . '>';

		        if($notify_address == 'on'){
				    $headers[] = 'Bcc: '.esc_attr($notify_address_email, 'seo-help');
				}

		        wp_mail($to, $subject, $body, $headers);

		    }

		   // ob_clean();
		   // wp_reset_postdata();
		
		}


	}

}