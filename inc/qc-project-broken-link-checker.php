<?php

defined('ABSPATH') or die("You can't access this file directly.");


function qc_seo_help_brokenlink_form_show(){
	$action = 'admin.php?page=qc-seo-broken-link-checker';

    $output = '';
    $output.='<form method="post" action="'.esc_url($action, 'seo-help').'">';
    $output.='<table class="form-table"  >';

    $output.='<tr><td> '.esc_html('Check for Broken Links', 'seo-help').'</td>';
    $output.='<td><input type="submit" name="qc_seo_linkcheck" value="Start Checking" class="button button-primary qc_seo_linkcheck"  >&nbsp;&nbsp;</td>';
    $output.='</tr></table></form>';

    return $output;
        
}




/********************************************************
 * Simple link directory check
 *******************************************************/
function qc_seo_help_simple_brokenlink_form_show(){
	$action = 'admin.php?page=qc-seo-broken-link-checker#qcld_seo_tab-22';

    $output = '';
    $output.='<form method="post" action="'.esc_url($action, 'seo-help').'">';
    $output.='<table class="form-table"  >';
    $output.='<tr><td>'.esc_html('Check for Broken Links', 'seo-help').'</td>';
    $output.='<td><input type="submit" name="qc_seo_simple_linkcheck" value="Start Link Checking" class="button button-primary qc_seo_simple_linkcheck"  >&nbsp;&nbsp;</td>';
    $output.='</tr></table></form>';

    return $output;
        
}

/********************************************************
 * simple broken link checker results
 *******************************************************/
function qc_seo_help_simple_broken_link_check(){
    
    $output = '';
    $output.= '<table class="form-table qc_seo_link_check">';
    $output.= '<thead>';
    $output.= '<tr>';
    $output.= '<th>'.esc_html('Link URL', 'seo-help').'</th><th> '.esc_html('Status', 'seo-help').' </th><th> '.esc_html('Content', 'seo-help').' </th>';
    $output.= '</tr>';
    $output.= '</thead>';
    //we use the WP wp_remote function
         
    // WP_Query arguments
	$args = array (
	  	'post_type'        => 'sld',
	    'posts_per_page'   => -1,
	);

	// The Query
	$query = new WP_Query( $args );	

	// The Loop
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();


            $datas = get_post_meta(get_the_ID(), 'qcopd_list_item01', true);

            $output.= '';

            foreach($datas as $data){
          
                    
                $response = wp_remote_get(trim($data['qcopd_item_link']), array('timeout' => 20));
                $response_code = wp_remote_retrieve_response_code($response);

                //link must be changed or is no longer valid
                if ($response_code != 200) {
                    $output.= '<tr><td>';
                    $output.= $data['qcopd_item_link'];
                    $output.= '</td>';
                    $output.= '<td style="color:red">';
                    $output.= $response_code;
                    $output.= '</td><td>';
                    $editstring = get_edit_post_link(get_the_ID());
                    $output .= the_title('<a href="' . $editstring . '">', '</a>', false);
                    $output.= '</td></tr>';
                }

                
	        }
           
        }
    } else {
        // no posts found
        $output.='<tr><td> '.esc_html('No Broken Links found!', 'seo-help').' </td></tr>';
    }
    $output.= '</table>';
	// Restore original Post Data
    wp_reset_postdata();
    echo $output;
}