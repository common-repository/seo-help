<?php 

defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'qcld_linkbait_render_modal' ) ) {
	function qcld_linkbait_render_modal() { // Linkbait Generator Modal //

		check_ajax_referer( 'seo-help-pro', 'security');

		$title = isset($_POST['linkbait_title']) ? sanitize_text_field(trim($_POST['linkbait_title'])) : '';
		$data = simplexml_load_file(qcld_Linkbait_dir1.'/assets/data/data.xml'); //loading data from file
		$data = qcld_linkbait_xml2array($data->item); //getting data array
		$keys = array_rand($data, 5); //taking random 5 element from array
		?>
		<div id="title_Generator" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<span class="close">×</span>
				<h3><?php esc_html_e( "LinkBait Title Generator", 'seo-help' ); ?></h3>
				<hr/>
				<div class="sm_shortcode_list">

					<div class="linkbait_single_field">
						<label style="width: 100px;display: inline-block;font-weight: bold;"><?php esc_html_e( "Subject", 'seo-help' ); ?></label><input type="text" style="width: 79%;" value="<?php echo esc_html($title); ?>" id="linkbait_subject" />
					</div>
					<hr />
					<div class="linkbait_single_field">

                        <div class="linkbait_choose_field_option">
                            <div>
                            <input id="linkbait_singular" type="radio" name="linkbait_filter" checked="checked" value="singular"><label for="linkbait_singular"><span><span></span></span><?php esc_html_e( "Singular Subject", 'seo-help' ); ?></label>
                            </div>
                            <div>
                                <input id="linkbait_plural" type="radio" name="linkbait_filter" value="plural">
                                <label for="linkbait_plural"><span><span></span></span><?php esc_html_e( "Plural Subject", 'seo-help' ); ?></label>
                            </div>
                            <div>
                                <input id="linkbait_openai" type="radio" name="linkbait_filter" value="openai">
                                <label for="linkbait_openai"><span><span></span></span><?php esc_html_e( "Open AI", 'seo-help' ); ?></label>
                            </div>
                        </div>


						<div>
							<img src="<?php echo esc_url(qcld_linkbait_assets_url.'/images/loader.gif'); ?>" id="linkbait_loading" style="float:right;margin-left:8px;margin-top: 11px;display:none"/>
							<input style="float:right" type="button" id="linkbait_generate" value="Re/Generate" />
						</div>
						<div style="clear:both"></div>
					</div>
					<hr/>
					<div class="linkbait_single_field">
						<?php 
							if($title!=''){ //check if title exists
						?>
								
							<div id="linkbait_ajax_data">
							<h4><?php esc_html_e( "Select your title", 'seo-help' ); ?></h4>
								<?php 
									//checking if variable is empty array
									$cuntvar = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);
									
									if(!empty($data)){
									$flag = true;
									$skip = array(); //skip repeating element
									foreach($keys as $k=>$v){ 
									$skip[] = $v;
									$sugtitle = str_replace('####',$title,$data[$v]);
									$rvalue = array_rand($cuntvar);
									$sugtitle = str_replace('[#]',$cuntvar[$rvalue],$sugtitle);
									$sugtitle = stripslashes($sugtitle);
								?>
									
										<input id="<?php echo $v; ?>" type="radio" name="linkbait_radio" value="<?php echo esc_html($sugtitle) ?>" <?php echo ($flag==true?'checked="checked"':'') ?>><label for="<?php echo $v; ?>"><span><span></span></span><?php echo esc_html($sugtitle) ?></label>
									
								<?php
									$flag = false;
									}
									?>
									<input type="hidden" value="<?php echo sanitize_text_field(implode(',',$skip)); ?>" id="linkbait_skip" name="linkbait_skip" />
									<input type="hidden" value="" id="linkbait_skip2" name="linkbait_skip2" />
									<input type="hidden" value="" id="linkbait_skip2" name="linkbait_skip2" />
								<?php 
									}
								?>

							  </div>
						<?php
							}else{
						?>
							
							<div id="linkbait_ajax_data">
								<h4 style="color:red"><?php esc_html_e( "You have to put Subject first! Then click Generate.", 'seo-help' ); ?></h4>	
							 </div>					
						<?php
							}
						?>
						  
					</div>
					
					<div class="linkbait_single_field qcld_seo_copy_msg" style="text-align:center">
						
						<button type="button" id="linkbait_add"><?php esc_html_e( "Copy Title", 'seo-help' ); ?></button>
					</div>
				</div>
			</div>

		</div>
		<?php
		exit;
	}
}
add_action( 'wp_ajax_qcld_linkbait_show_suggestion', 'qcld_linkbait_render_modal');
add_action('wp_ajax_nopriv_qcld_linkbait_show_suggestion', 'qcld_linkbait_render_modal');

if ( ! function_exists( 'qcld_linkbait_generate_modal' ) ) {
	function qcld_linkbait_generate_modal() { // Linkbait Generator Modalo //
		//this function run when click generate
		check_ajax_referer( 'seo-help-pro', 'security');
		
		$title = isset($_POST['linkbait_title']) ? sanitize_text_field(trim($_POST['linkbait_title'])) : '';
		$data = simplexml_load_file(qcld_Linkbait_dir1.'/assets/data/data.xml');
		$filter = ( isset($_POST['linkbait_filter']) and !empty($_POST['linkbait_filter']) ) ? sanitize_text_field(trim($_POST['linkbait_filter'])):'';
		$linkbait_skip = isset($_POST['linkbait_skip']) ? sanitize_text_field(trim($_POST['linkbait_skip'])) :'';
		$linkbait_skip2 = isset($_POST['linkbait_skip2']) ? sanitize_text_field(trim($_POST['linkbait_skip2'])) :'';
		
		
        if( $filter == 'google' ) {

            $source = "en";
     
            $target_text = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" .$source. "&tl=" .$source. "&dt=t&q=". rawurlencode($title);

            $response = file_get_contents($target_text);
            $obj =json_decode($response,true);

            $target_keyword = ( isset($obj[0][0][0]) && !empty($obj[0][0][0]) ) ? $obj[0][0][0] : $title;


            $url = sprintf("http://suggestqueries.google.com/complete/search?client=chrome&hl=%s&q=%s", $source, urlencode($target_keyword));
            $json = file_get_contents($url);
            $results = json_decode(utf8_encode($json));

            ?>
            <h4><?php esc_html_e( "Select your title", 'seo-help' ); ?></h4>
            <?php

            foreach ($results[1] as $key => $keywordResults){
              ?>
      
                <label for="<?php echo $key; ?>"> <input id="<?php echo $key; ?>" type="radio" name="linkbait_radio" value="<?php echo esc_attr($keywordResults) ?>" <?php echo ($key==0?'checked="checked"':'') ?>> <?php echo esc_html($keywordResults) ?></label>
                
           <?php 
            } 
                     
            exit;

        }else if( $filter == 'openai' ){ 

            
			$OPENAI_API_KEY = get_option('qcld_seohelp_api_key');
			$ai_engines 	= get_option('qcld_seohelp_ai_engines');
			$max_token 		= get_option('qcld_seohelp_max_token');
			$temperature 	= get_option('qcld_seohelp_ai_temperature');
			$ppenalty 		= get_option('qcld_seohelp_ai_ppenalty');
			$fpenalty 		= get_option('qcld_seohelp_ai_fpenalty');
			$prompt 		= "Write some Link Bait blog title for ". $title;

			if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini' ){
                $gptkeyword = [];
                $ch = curl_init();
                $url = 'https://api.openai.com/v1/chat/completions';

                array_push($gptkeyword, array(
                           "role"       => "user",
                           "content"    =>  $prompt
                        ));

                $post_fields = array(
                    "model"         => $ai_engines,
                    "messages"      => $gptkeyword,
                    "max_tokens"    => 200,
                    "temperature"   => 0
                );
                $header  = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $OPENAI_API_KEY
                ];
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error: ' . curl_error($ch);
                }
                curl_close($ch);
                $complete = json_decode( $result );
                // we need to catch the error here
                
                if ( isset( $complete->error ) ) {
                    $complete = $complete->error->message;
                    // exit
                    echo  esc_html( $complete ) ;
                    exit;
                } else {
                    //$complete = $complete->choices[0]->message->content;
                    $complete = isset( $complete->choices[0]->message->content ) ? trim( $complete->choices[0]->message->content ) : '';
                }

            }else{

                $request_body = [
                    "prompt"            => $prompt,
                    "model"             => $ai_engines,
                    "max_tokens"        => (int)$max_token,
                    "temperature"       => (float)$temperature,
                    "presence_penalty"  => (float)$ppenalty,
                    "frequency_penalty" => (float)$fpenalty,
                    "top_p"             => 1,
                    "best_of"           => 1,
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

                $complete = json_decode( $result );
                // we need to catch the error here
                
                if ( isset( $complete->error ) ) {
                    $complete = $complete->error->message;
                    // exit
                    echo  esc_html( $complete ) ;
                    exit;
                } else {
                    //$complete = $complete->choices[0]->text;
                    $complete = isset( $complete->choices[0]->text ) ? trim( $complete->choices[0]->text ) : '';
                }

            }

           // var_dump( $complete );
           // wp_die();

            ?>
            <h4><?php esc_html_e( "Select your title", 'seo-help' ); ?></h4>
            <?php

            if(!empty($complete)){
               $results = explode("\n", $complete );

	            foreach ($results as $key => $keywordResults){
	        ?>
	            <label for="<?php echo $key; ?>"> <input id="<?php echo $key; ?>" type="radio" name="linkbait_radio" value="<?php echo esc_attr($keywordResults) ?>" <?php echo ($key==0?'checked="checked"':'') ?>> <?php echo esc_html($keywordResults) ?></label>
	                
	        <?php 

            	} 
            } 
                     
            exit;

		}else if($filter=='plural'){ // Check if Plural //
			$data = qcld_linkbait_xml2array($data->itemp); //Making array from object //
			$skip = $linkbait_skip; //getting keys for skipping element
			$skip = explode(',',$skip);
			if(is_array($skip) && !empty($skip)){ //check skip array not empty
				foreach($skip as $sk=>$sv){
					unset($data[$sv]); // unseting keys that previously displayed.
				}
			}else{
				$skip = array(); 
			}
			if(sizeof($data)<4) //checking data less then 5
			$keys = array_rand($data, sizeof($data));
			else
				$keys = array_rand($data, 4);
			
			$algo = qcld_linkbait_algorithm($title); //Collecting Second algorithm data //
			$skip2 = $linkbait_skip2; //getting keys for skipping element
			$skip2 = explode(',',$skip2);
			
			if(is_array($skip2) && !empty($skip2)){ //check skip array not empty
				foreach($skip2 as $sk2=>$sv2){
					unset($algo[$sv2]); // unseting keys that previously displayed.
				}
			}else{
				$skip2 = array(); 
			}
			
			$key2 = array_rand($algo);
			$skip2[] = $key2;
		} else { // Code for Singular //
			$data = qcld_linkbait_xml2array($data->item); //Making array from object //
			$skip = $linkbait_skip; //getting keys for skipping element
			$skip = explode(',',$skip);
			if(is_array($skip) && !empty($skip)){ //check skip array not empty
				foreach($skip as $sk=>$sv){
					unset($data[$sv]); // unseting keys that previously displayed.
				}
			}else{
				$skip = array(); 
			}
			//checking data less then 5
			if(sizeof($data)<5)
				$keys = array_rand($data, sizeof($data));
			else
				$keys = array_rand($data, 5);
		}
		
		if($title!=''){
			?>
					<h4><?php esc_html_e( "Select your title", 'seo-help' ); ?></h4>
					<?php 
						$cuntvar = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);
						if(!empty($data)){
						$flag = true;
						foreach($keys as $k=>$v){ //creating html element though array
						$skip[] = $v;
						$sugtitle = str_replace('####',$title,$data[$v]); //placeing title to right position
						$rvalue = array_rand($cuntvar);
						$sugtitle = str_replace('[#]',$cuntvar[$rvalue],$sugtitle);
						$sugtitle = stripslashes($sugtitle);
					?>
							<label for="<?php echo esc_attr($v); ?>"> <input id="<?php echo esc_attr($v); ?>" type="radio" name="linkbait_radio" value="<?php echo esc_html($sugtitle) ?>" <?php echo ($flag==true?'checked="checked"':'') ?>> <?php echo esc_html($sugtitle) ?></label>
					<?php
						$flag = false;
						}
						if($filter=='plural' and $key2!=''){
						$sugtitle2 = str_replace('####',$title,$algo[$key2]);
						?>
						<label for="<?php echo esc_attr('sec2'); ?>"><input id="<?php echo esc_attr('sec2'); ?>" type="radio" name="linkbait_radio" value="<?php echo esc_html($sugtitle2) ?>"> <?php echo esc_html($sugtitle2) ?></label>
					<?php
						}
						?>
						<input type="hidden" value="<?php //echo esc_html(implode(',',$skip)); ?>" id="linkbait_skip" name="linkbait_skip" />
						<input type="hidden" value="<?php //echo esc_html(implode(',',$skip2)); ?>" id="linkbait_skip2" name="linkbait_skip2" />
						<?php
						
						}else{
						?>
							<p style="color:red"><?php esc_html_e( "No Data Found!", 'seo-help' ); ?></p>
						<?php
						}
					?>
			<?php
				}else{
					echo esc_html('No Subject Found!', 'seo-help');
				}
			?>
		<?php
		exit;
	}
}
add_action( 'wp_ajax_qcld_linkbait_generate_suggestion', 'qcld_linkbait_generate_modal');
add_action('wp_ajax_nopriv_qcld_linkbait_generate_suggestion', 'qcld_linkbait_generate_modal');



/**********************************
 * Broken link checking by ajax
 **********************************/

add_action('wp_ajax_qcld_seo_help_broken_link_checking_by_ajax', 'qcld_seo_help_broken_link_checking_by_ajax');
add_action('wp_ajax_nopriv_qcld_seo_help_broken_link_checking_by_ajax', 'qcld_seo_help_broken_link_checking_by_ajax');
if ( ! function_exists( 'qcld_seo_help_broken_link_checking_by_ajax' ) ) {
	function qcld_seo_help_broken_link_checking_by_ajax(){

		check_ajax_referer( 'seo-help-pro', 'security');
	    
	    $output = '';
	    $output.= '<table class="form-table qc_seo_link_check">';
	    $output.= '<thead>';
	    $output.= '<tr>';
	    $output.='<th>'.esc_html('Chunk', 'seo-help').'</th><th> '.esc_html('Link URL', 'seo-help').' </th><th> '.esc_html('Status', 'seo-help').' </th><th> '.esc_html('Content', 'seo-help').' </th>';
	    $output.= '</tr>';
	    $output.= '</thead>';
	    //we use the WP wp_remote function
	         
	    // WP_Query arguments
		$args = array (
		  	'post_type'        	=> array('page', 'post'),
		    'posts_per_page'   	=> -1,
	    	'post_status' 		=> 'publish',
		);

		// The Query
		$query = new WP_Query( $args );

		// The Loop
	    if ($query->have_posts()) {
	        while ($query->have_posts()) {
	            $query->the_post();

	            $content = get_the_content(get_the_ID());
	            if (preg_match_all('/(<a[^>]+href=["|\'](.+)["|\'][^>]*>)(.*)<\/a>/isUu', $content, $matches, PREG_SET_ORDER) > 0) {
	            	foreach ($matches as $match) {

	            		$response = wp_remote_get($match[2], array('timeout' => 20));
	            		$response_code = wp_remote_retrieve_response_code($response);
	            		
	            		 if ($response_code == 300 || $response_code == 301 || $response_code == 302 || $response_code == 303 || $response_code == 305 || $response_code == 307 || $response_code == 308 || $response_code == 400 || $response_code == 401 || $response_code == 402 || $response_code == 403 || $response_code == 404 || $response_code == 405 || $response_code == 406 || $response_code == 407 || $response_code == 408 || $response_code == 409 || $response_code == 410 || $response_code == 411 || $response_code == 412 || $response_code == 414 || $response_code == 415 || $response_code == 416 || $response_code == 417 || $response_code == 420 || $response_code == 422 || $response_code == 423 || $response_code == 424 || $response_code == 450 || $response_code == 500 || $response_code == 501 || $response_code == 502 || $response_code == 503 || $response_code == 505 ||  $response_code == 506 ||  $response_code == 507 ||  $response_code == 509 ||  $response_code == 510 ||  $response_code == 999 ) {
			                //link must be changed or is no longer valid
			                $output.= '<tr><td>';
			                $output .= $match[0];
			                $output.= '</td>';
			                $output.= '<td>';
			                $output.= $match[2];
			                $output.= '</td>';
			                $output.= '<td style="color:red">';
			                $errors_pages = '( <a class="qcld_help_links" href="admin.php?page=qcld-seo-help-section#qcld_seo_tab-555" target="_blank">?</a> )';
			                $output.= $response_code. ' '.$errors_pages.'';
			                $output.= '</td><td>';
			                $editstring = get_edit_post_link(get_the_ID());
	                		$output .= the_title('<a href="' . $editstring . '">', '</a>', false);
			                $output.= '</td></tr>';
			            }

			        }
	            }
	           
	        }
	    } else {
	        // no posts found
	        $output.='<tr><td>'.esc_html('No Broken Links found!', 'seo-help').'</td></tr>';
	    }
	    $output.= '</table>';

	    wp_reset_query();
	    $response = array( 'html' => $output );
	    echo wp_send_json($response);
	    wp_die();

	}
}

/**********************************
 * Broken sld link checking by ajax
 **********************************/

add_action('wp_ajax_qcld_seo_help_broken_sld_link_checking_by_ajax', 'qcld_seo_help_broken_sld_link_checking_by_ajax');
add_action('wp_ajax_nopriv_qcld_seo_help_broken_sld_link_checking_by_ajax', 'qcld_seo_help_broken_sld_link_checking_by_ajax');
if ( ! function_exists( 'qcld_seo_help_broken_sld_link_checking_by_ajax' ) ) {
	function qcld_seo_help_broken_sld_link_checking_by_ajax(){

		check_ajax_referer( 'seo-help-pro', 'security');
		
	    $output = '';
	    $output.= '<table class="form-table qc_seo_link_check">';
	    $output.= '<thead>';
	    $output.= '<tr>';
	    $output.= '<th> '.esc_html('Link URL', 'seo-help').' </th><th> '.esc_html('Status', 'seo-help').' </th><th> '.esc_html('Content', 'seo-help').' </th>';
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


	            $datas = get_post_meta(get_the_ID(), 'qcopd_list_item01');
	            //	print_r($datas);exit;
	            $output.= '';

	            foreach($datas as $data){


	            			$link = trim($data['qcopd_item_link']);
	            			
			            	$response = wp_remote_get($link, array('timeout' => 20));
	            			$response_code = wp_remote_retrieve_response_code($response);

				            //link must be changed or is no longer valid
				            // 300, 301, 302, 303, 305, 307, 308, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 414, 415, 416, 417, 420, 422, 423, 424, 450, 500, 501, 502, 503, 504, 505, 506, 507, 509, 510, 999
				            if ($response_code == 300 || $response_code == 301 || $response_code == 302 || $response_code == 303 || $response_code == 305 || $response_code == 307 || $response_code == 308 || $response_code == 400 || $response_code == 401 || $response_code == 402 || $response_code == 403 || $response_code == 404 || $response_code == 405 || $response_code == 406 || $response_code == 407 || $response_code == 408 || $response_code == 409 || $response_code == 410 || $response_code == 411 || $response_code == 412 || $response_code == 414 || $response_code == 415 || $response_code == 416 || $response_code == 417 || $response_code == 420 || $response_code == 422 || $response_code == 423 || $response_code == 424 || $response_code == 450 || $response_code == 500 || $response_code == 501 || $response_code == 502 || $response_code == 503 || $response_code == 505 ||  $response_code == 506 ||  $response_code == 507 ||  $response_code == 509 ||  $response_code == 510 ||  $response_code == 999 ) {
				                $output.= '<tr><td>';
				               
				                $output.= '<a href="'.$link.'" target="_blank">'.$link.'</a>';
				                $output.= '</td>';
				                $output.= '<td style="color:red">';
				                $errors_pages = '( <a class="qcld_help_links" href="admin.php?page=qcld-seo-help-section#qcld_seo_tab-555" target="_blank">?</a> )';
				                $output.= $response_code. ' '.$errors_pages.'';
				                $output.= '</td><td>';
			                	$editstring = get_edit_post_link(get_the_ID());
	                			$output .= the_title('<a href="' . $editstring . '">', '</a>', false);
				                $output.= '</td></tr>';
				            }

		        }
	           
	        }
	    } else {
	        // no posts found
	        $output.='<tr><td>'.esc_html('No Broken Links found!', 'seo-help').'</td></tr>';
	    }
	    $output.= '</table>';

	    wp_reset_query();
	    $response = array( 'html' => $output );
	    echo wp_send_json($response);
	    wp_die();

	}
}



add_action( 'wp_ajax_qcldseohelp_keyword_suggestion_content', 'qcldseohelp_keyword_suggestion_content' );
add_action('wp_ajax_nopriv_qcldseohelp_keyword_suggestion_content', 'qcldseohelp_keyword_suggestion_content');
if ( ! function_exists( 'qcldseohelp_keyword_suggestion_content' ) ) {
    function qcldseohelp_keyword_suggestion_content(){

        check_ajax_referer( 'seo-help-pro', 'security');

        set_time_limit(600);

        $OPENAI_API_KEY                     = get_option('qcld_seohelp_api_key');
        $ai_engines                         = get_option('qcld_seohelp_ai_engines');
        $max_token                          = get_option('qcld_seohelp_max_token');
        $temperature                        = get_option('qcld_seohelp_ai_temperature');
        $ppenalty                           = get_option('qcld_seohelp_ai_ppenalty');
        $fpenalty                           = get_option('qcld_seohelp_ai_fpenalty');

        $qcld_article_text                  = isset($_POST['keyword'])                          ? sanitize_text_field( $_POST['keyword'] ) : '';
        $keyword_number                     = isset( $_POST['keyword_number'] )                 ? sanitize_text_field( $_POST['keyword_number'] ) : '';
        $qcld_article_language              = isset($_POST['qcld_article_language'])            ? sanitize_text_field( $_POST['qcld_article_language'] ) : '';
        $qcld_article_number_of_heading     = isset($_POST['qcld_article_number_of_heading'])   ? sanitize_text_field( $_POST['qcld_article_number_of_heading'] ) : '';
        $qcld_article_heading_tag           = isset($_POST['qcld_article_heading_tag'])         ? sanitize_text_field( $_POST['qcld_article_heading_tag'] ) : '';
        $qcld_article_heading_style         = isset($_POST['qcld_article_heading_style'])       ? sanitize_text_field( $_POST['qcld_article_heading_style'] ) : '';
        $qcld_article_heading_tone          = isset($_POST['qcld_article_heading_tone'])        ? sanitize_text_field( $_POST['qcld_article_heading_tone'] ) : '';
        $qcld_article_heading_img           = isset($_POST['qcld_article_heading_img'])         ? sanitize_text_field( $_POST['qcld_article_heading_img'] ) : '';
        $qcld_article_heading_tagline       = isset($_POST['qcld_article_heading_tagline'])     ? sanitize_text_field( $_POST['qcld_article_heading_tagline'] ) : '';
        $qcld_article_heading_intro         = isset($_POST['qcld_article_heading_intro'])       ? sanitize_text_field( $_POST['qcld_article_heading_intro'] ) : '';
        $qcld_article_heading_conclusion    = isset($_POST['qcld_article_heading_conclusion'])  ? sanitize_text_field( $_POST['qcld_article_heading_conclusion'] ) : '';
        $qcld_article_label_anchor_text     = isset($_POST['qcld_article_label_anchor_text'])   ? sanitize_text_field( $_POST['qcld_article_label_anchor_text'] ) : '';
        $qcld_article_target_url            = isset($_POST['qcld_article_target_url'])          ? sanitize_text_field( $_POST['qcld_article_target_url'] ) : '';
        $qcld_article_target_label_cta      = isset($_POST['qcld_article_target_label_cta'])    ? sanitize_text_field( $_POST['qcld_article_target_label_cta'] ) : '';
        $qcld_article_cta_pos               = isset($_POST['qcld_article_cta_pos'])             ? sanitize_text_field( $_POST['qcld_article_cta_pos'] ) : '';
        $qcld_article_label_keywords        = isset($_POST['qcld_article_label_keywords'])      ? sanitize_text_field( $_POST['qcld_article_label_keywords'] ) : '';
        $qcld_article_label_word_to_avoid   = isset($_POST['qcld_article_label_word_to_avoid']) ? sanitize_text_field( $_POST['qcld_article_label_word_to_avoid'] ) : '';
        $qcld_article_label_keywords_bold   = isset($_POST['qcld_article_label_keywords_bold']) ? intval( $_POST['qcld_article_label_keywords_bold'] ) : '';
        $qcld_article_heading_faq           = isset($_POST['qcld_article_heading_faq'])         ? intval( $_POST['qcld_article_heading_faq'] ) : '';

        $img_size                           = isset($_POST['qcld_article_img_size'])            ? sanitize_text_field( $_POST['qcld_article_img_size'] ) : '';
        //$img_size = "512x512";

        if ( empty($qcld_article_language) ) {
            $qcld_article_language = "en";
        }
        // if number of heading is not set, set it to 5
        if ( empty($qcld_article_number_of_heading) ) {
            $qcld_article_number_of_heading = 5;
        }
        // if writing style is not set, set it to descriptive
        if ( empty($qcld_article_heading_style) ) {
            $qcld_article_heading_style = "infor";
        }
        // if writing tone is not set, set it to assertive
        if ( empty($qcld_article_heading_tone) ) {
            $qcld_article_heading_tone = "formal";
        }
        // if heading tag is not set, set it to h2
        if ( empty($qcld_article_heading_tag) ) {
            $qcld_article_heading_tag = "h2";
        }

        $writing_style  = apply_filters('qcld_seo_openai_filter_for_style', $qcld_article_heading_style, $qcld_article_language );
        $tone_text      = apply_filters('qcld_seo_openai_filter_for_tone', $qcld_article_heading_tone, $qcld_article_language );

        if ( $qcld_article_language == "en" ) {

            if ( $qcld_article_number_of_heading == 1 ) {
                $prompt_text = " blog topic about ";
            } else {
                $prompt_text = " blog topics about ";
            }
            
            $intro_text = "Write an introduction about ";
            $conclusion_text = "Write a conclusion about ";
            $tagline_text = "Write a tagline about ";
            $introduction = "Introduction";
            $conclusion = "Conclusion";
            $faq_text = strval( $qcld_article_number_of_heading ) . " questions and answers about " . $qcld_article_text . ".";
            $faq_heading = "Q&A";
            $style_text = "Writing style: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Keywords: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Exclude following keywords: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Write a Call to action about: " . $qcld_article_text . " and create a href tag link to: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "de" ) {
            $prompt_text = " blog-Themen über ";
            $intro_text = "Schreiben Sie eine Einführung über ";
            $conclusion_text = "Schreiben Sie ein Fazit über ";
            $tagline_text = "Schreiben Sie eine Tagline über ";
            $introduction = "Einführung";
            $conclusion = "Fazit";
            $faq_text = strval( $qcld_article_number_of_heading ) . " Fragen und Antworten über " . $qcld_article_text . ".";
            $faq_heading = "Fragen und Antworten";
            $style_text = "Schreibstil: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Schlüsselwörter: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Ausschließen folgende Schlüsselwörter: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Schreiben Sie eine Call to action über: " . $qcld_article_text . " und erstellen Sie einen href-Tag-Link zu: " . $qcld_article_target_label_cta . ".";
        } else  if ( $qcld_article_language == "fr" ) {
            $prompt_text = " sujets de blog sur ";
            $intro_text = "Écrivez une introduction sur ";
            $conclusion_text = "Écrivez une conclusion sur ";
            $tagline_text = "Rédigez un slogan sur ";
            $introduction = "Introduction";
            $conclusion = "Conclusion";
            $faq_text = strval( $qcld_article_number_of_heading ) . " questions et réponses sur " . $qcld_article_text . ".";
            $faq_heading = "Questions et réponses";
            $style_text = "Style d'écriture: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Mots clés: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Exclure les mots-clés suivants: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Écrivez un appel à l'action sur: " . $qcld_article_text . " et créez un lien href tag vers: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "es" ) {
            $prompt_text = " temas de blog sobre ";
            $intro_text = "Escribe una introducción sobre ";
            $conclusion_text = "Escribe una conclusión sobre ";
            $tagline_text = "Escribe una eslogan sobre ";
            $introduction = "Introducción";
            $conclusion = "Conclusión";
            $faq_text = strval( $qcld_article_number_of_heading ) . " preguntas y respuestas sobre " . $qcld_article_text . ".";
            $faq_heading = "Preguntas y respuestas";
            $style_text = "Estilo de escritura: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Palabras clave: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Excluir las siguientes palabras clave: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Escribe una llamada a la acción sobre: " . $qcld_article_text . " y cree un enlace de etiqueta html <a href> para: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "it" ) {
            $prompt_text = " argomenti di blog su ";
            $intro_text = "Scrivi un'introduzione su ";
            $conclusion_text = "Scrivi una conclusione su ";
            $tagline_text = "Scrivi un slogan su ";
            $introduction = "Introduzione";
            $conclusion = "Conclusione";
            $faq_text = strval( $qcld_article_number_of_heading ) . " domande e risposte su " . $qcld_article_text . ".";
            $faq_heading = "Domande e risposte";
            $style_text = "Stile di scrittura: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Parole chiave: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Escludere le seguenti parole chiave: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Scrivi un call to action su: " . $qcld_article_text . " e crea un href tag link a: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "pt" ) {
            $prompt_text = " tópicos de blog sobre ";
            $intro_text = "Escreva uma introdução sobre ";
            $conclusion_text = "Escreva uma conclusão sobre ";
            $tagline_text = "Escreva um slogan sobre ";
            $introduction = "Introdução";
            $conclusion = "Conclusão";
            $faq_text = strval( $qcld_article_number_of_heading ) . " perguntas e respostas sobre " . $qcld_article_text . ".";
            $faq_heading = "Perguntas e respostas";
            $style_text = "Estilo de escrita: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Palavras-chave: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Excluir as seguintes palavras-chave: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Escreva um call to action sobre: " . $qcld_article_text . " e crie um href tag link para: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "nl" ) {
            $prompt_text = " blogonderwerpen over ";
            $intro_text = "Schrijf een inleiding over ";
            $conclusion_text = "Schrijf een conclusie over ";
            $tagline_text = "Schrijf een slogan over ";
            $introduction = "Inleiding";
            $conclusion = "Conclusie";
            $faq_text = strval( $qcld_article_number_of_heading ) . " vragen en antwoorden over " . $qcld_article_text . ".";
            $faq_heading = "Vragen en antwoorden";
            $style_text = "Schrijfstijl: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Trefwoorden: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Sluit de volgende trefwoorden uit: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Schrijf een call to action over: " . $qcld_article_text . " en maak een href tag link naar: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "ru" ) {
            $prompt_text = "Перечислите ";
            $prompt_last = " идей блога о ";
            $intro_text = "Напишите введение о ";
            $conclusion_text = "Напишите заключение о ";
            $tagline_text = "Напишите слоган о ";
            $introduction = "Введение";
            $conclusion = "Заключение";
            $faq_text = strval( $qcld_article_number_of_heading ) . " вопросов и ответов о " . $qcld_article_text . ".";
            $faq_heading = "Вопросы и ответы";
            $style_text = "Стиль написания: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Ключевые слова: " . $qcld_article_label_keywords . ".";
                $myprompt = $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Исключите следующие ключевые слова: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Напишите call to action о: " . $qcld_article_text . " и сделайте href tag link на: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "ja" ) {
            $prompt_text = " に関するブログのアイデアを ";
            $prompt_last = " つ挙げてください";
            $intro_text = " について紹介文を書く";
            $conclusion_text = " についての結論を書く";
            $tagline_text = " についてのスローガンを書く";
            $introduction = "序章";
            $conclusion = "結論";
            $faq_text = $qcld_article_text . " に関する " . strval( $qcld_article_number_of_heading ) . " の質問と回答.";
            $faq_heading = "よくある質問";
            $style_text = "書き方: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = $qcld_article_text . $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . ".";
            } else {
                $keyword_text = ". キーワード: " . $qcld_article_label_keywords . ".";
                $myprompt = $qcld_article_text . $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " 次のキーワードを除外します。 " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $qcld_article_text . $intro_text;
            $myconclusion = $qcld_article_text . $conclusion_text;
            $mytagline = $qcld_article_text . $tagline_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = $qcld_article_text . " についてのコール・トゥ・アクションを書き、hrefタグリンクを作成します。 " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "zh" ) {
            $prompt_text = " 关于 ";
            $of_text = " 的 ";
            $piece_text = " 个博客创意";
            $intro_text = "写一篇关于 ";
            $intro_last = " 的介绍";
            $conclusion_text = "写一篇关于 ";
            // write a tagline about
            $tagline_text = "写一个标语关于 ";
            $conclusion_last = " 的结论";
            $introduction = "介绍";
            $conclusion = "结论";
            $faq_text = $qcld_article_text . " 的 " . strval( $qcld_article_number_of_heading ) . " 个问题和答案.";
            $faq_heading = "常见问题";
            $style_text = "写作风格: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = $prompt_text . $qcld_article_text . $of_text . strval( $qcld_article_number_of_heading ) . $piece_text . ".";
            } else {
                $keyword_text = ". 关键字: " . $qcld_article_label_keywords . ".";
                $myprompt = $prompt_text . $qcld_article_text . $of_text . strval( $qcld_article_number_of_heading ) . $piece_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " 排除以下关键字：" . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text . $intro_last;
            $myconclusion = $conclusion_text . $qcld_article_text . $conclusion_last;
            $mytagline = $tagline_text . $qcld_article_text;
            // 写一个关于 123 的号召性用语并创建一个 <a href> html 标签链接到：
            $mycta = "写一个关于 " . $qcld_article_text . " 的号召性用语并创建一个 <a href> html 标签链接到： " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "ko" ) {
            $prompt_text = " 다음과 관련된 ";
            $prompt_last = "가지 블로그 아이디어: ";
            $intro_text = "블로그 토픽에 대한 소개를 작성하십시오 ";
            $conclusion_text = "블로그 토픽에 대한 결론을 작성하십시오 ";
            $introduction = "소개";
            $conclusion = "결론";
            $faq_text = $qcld_article_text . "에 대한 " . strval( $qcld_article_number_of_heading ) . "개의 질문과 답변.";
            $faq_heading = "자주 묻는 질문";
            // write a tagline about
            $tagline_text = "에 대한 태그라인 작성 ";
            $style_text = "작성 스타일: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . $qcld_article_text . ".";
            } else {
                $keyword_text = ". 키워드: " . $qcld_article_label_keywords . ".";
                $myprompt = $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " 다음 키워드를 제외하십시오. " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $qcld_article_text . $tagline_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = $qcld_article_text . "에 대한 호출 행동을 작성하고 href 태그 링크를 만듭니다. " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "id" ) {
            $prompt_text = " topik blog tentang ";
            $intro_text = "Tulis pengantar tentang ";
            $conclusion_text = "Tulis kesimpulan tentang ";
            $introduction = "Pengantar";
            $conclusion = "Kesimpulan";
            $faq_text = strval( $qcld_article_number_of_heading ) . " pertanyaan dan jawaban tentang " . $qcld_article_text . ".";
            $faq_heading = "Pertanyaan dan jawaban";
            // write a tagline about
            $tagline_text = "Tulis tagline tentang ";
            $style_text = "Gaya penulisan: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Kata kunci: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Hindari kata kunci berikut: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = "Tulis panggilan tindakan tentang " . $qcld_article_text . " dan buat tautan tag href ke: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "tr" ) {
            $prompt_text = " hakkında ";
            $prompt_last = " blog başlığı listele.";
            $intro_text = " ile ilgili bir giriş yazısı yaz.";
            $conclusion_text = " ile ilgili bir sonuç yazısı yaz.";
            $introduction = "Giriş";
            $conclusion = "Sonuç";
            $faq_text = $qcld_article_text . " hakkında " . strval( $qcld_article_number_of_heading ) . " soru ve cevap.";
            $faq_heading = "SSS";
            // write a tagline about
            $tagline_text = " ile ilgili bir slogan yaz.";
            $style_text = "Yazı stili: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = $qcld_article_text . $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . ".";
            } else {
                $keyword_text = ". Anahtar kelimeler: " . $qcld_article_label_keywords . ".";
                $myprompt = $qcld_article_text . $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Bu anahtar kelimeleri kullanma: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $qcld_article_text . $intro_text;
            $myconclusion = $qcld_article_text . $conclusion_text;
            $mytagline = $qcld_article_text . $tagline_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = $qcld_article_text . " hakkında bir çağrıyı harekete geçir ve bir href etiketi bağlantısı oluştur: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "hi" ) {
            $prompt_text = " के बारे में ";
            $prompt_last = " ब्लॉग विषय सूचीबद्ध करें.";
            $intro_text = "का परिचय लिखिए ";
            $conclusion_text = "के बारे में निष्कर्ष लिखिए ";
            $introduction = "प्रस्तावना";
            $conclusion = "निष्कर्ष";
            $faq_text = $qcld_article_text . " के बारे में " . strval( $qcld_article_number_of_heading ) . " प्रश्न और उत्तर.";
            $faq_heading = "सामान्य प्रश्न";
            // write a tagline about
            $tagline_text = " के बारे में एक नारा लिखिए";
            $style_text = "लेखन शैली: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = $qcld_article_text . $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . ".";
            } else {
                $keyword_text = ". कीवर्ड: " . $qcld_article_label_keywords . ".";
                $myprompt = $qcld_article_text . $prompt_text . strval( $qcld_article_number_of_heading ) . $prompt_last . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " निम्नलिखित खोजशब्दों को बाहर करें: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $qcld_article_text . $tagline_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = $qcld_article_text . " के बारे में कोई कॉल एक्शन लिखें और एक href टैग लिंक बनाएं: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "pl" ) {
            $prompt_text = " tematów blogów o ";
            $intro_text = "Napisz wprowadzenie o ";
            $conclusion_text = "Napisz konkluzja o ";
            $introduction = "Wstęp";
            $conclusion = "Konkluzja";
            $faq_text = "Napisz " . strval( $qcld_article_number_of_heading ) . " pytania i odpowiedzi o " . $qcld_article_text . ".";
            $faq_heading = "Pytania i odpowiedzi";
            // write a tagline about
            $tagline_text = "Napisz slogan o ";
            $style_text = "Styl pisania: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Słowa kluczowe:: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text . ".";
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Wyklucz następujące słowa kluczowe: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            $mycta = "Napisz wezwanie do działania dotyczące " . $qcld_article_text . " i utwórz link tagu HTML <a href> do: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "uk" ) {
            $prompt_text = " теми блогів про ";
            $intro_text = "Напишіть вступ про ";
            $conclusion_text = "Напишіть висновок про ";
            $introduction = "Вступ";
            $conclusion = "Висновок";
            $faq_text = "Напишіть " . strval( $qcld_article_number_of_heading ) . " питання та відповіді про " . $qcld_article_text . ".";
            $faq_heading = "Питання та відповіді";
            // write a tagline about
            $tagline_text = "Напишіть слоган про ";
            $style_text = "Стиль письма: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Ключові слова: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Виключіть такі ключові слова: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Напишіть заклик до дії про Google і створіть посилання на тег html <a href> для:
            $mycta = "Напишіть заклик до дії про " . $qcld_article_text . " і створіть посилання на тег html <a href> для: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "ar" ) {
            $prompt_text = " موضوعات المدونات على ";
            $intro_text = "اكتب مقدمة عن: ";
            $conclusion_text = "اكتب استنتاجًا عن: ";
            $introduction = "مقدمة";
            $conclusion = "استنتاج";
            $faq_text = "اكتب " . strval( $qcld_article_number_of_heading ) . " أسئلة وأجوبة عن " . $qcld_article_text . ".";
            $faq_heading = "الأسئلة الشائعة";
            // write a tagline about اكتب شعارًا عن
            $tagline_text = " اكتب شعارًا عن ";
            $style_text = "نمط الكتابة: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". الكلمات الدالة: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " تجنب الكلمات التالية: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $qcld_article_text . $tagline_text;
            $mycta = "اكتب عبارة تحث المستخدم على اتخاذ إجراء بشأن " . $qcld_article_text . " وأنشئ <a href> رابط وسم html من أجل: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "ro" ) {
            $prompt_text = " subiecte de blog despre ";
            $intro_text = "Scrieți o introducere despre ";
            $conclusion_text = "Scrieți o concluzie despre ";
            $introduction = "Introducere";
            $conclusion = "Concluzie";
            $faq_text = "Scrieți " . strval( $qcld_article_number_of_heading ) . " întrebări și răspunsuri despre " . $qcld_article_text . ".";
            $faq_heading = "Întrebări și răspunsuri";
            // write a tagline about
            $tagline_text = "Scrieți un slogan despre ";
            $style_text = "Stilul de scriere: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Cuvinte cheie: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Evitați cuvintele: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Scrieți un îndemn despre Google și creați o etichetă html <a href> link către:
            $mycta = "Scrieți un îndemn despre " . $qcld_article_text . " și creați o etichetă html <a href> link către: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "hu" ) {
            // Írj 5 blogtémát a Google-ról
            $prompt_text = " blog témákat a következő témában: ";
            $intro_text = "Írj bevezetést ";
            $conclusion_text = "Írj következtetést ";
            $introduction = "Bevezetés";
            $conclusion = "Következtetés";
            $faq_text = "Írj " . strval( $qcld_article_number_of_heading ) . " kérdést és választ a következő témában: " . $qcld_article_text . ".";
            $faq_heading = "GYIK";
            // write a tagline about
            $tagline_text = "Írj egy tagline-t ";
            $style_text = "Írásmód: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Kulcsszavak: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Kerülje a következő szavakat: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Írjon cselekvésre ösztönzést a 123-ról, és hozzon létre egy <a href> html címke hivatkozást:
            $mycta = "Írjon cselekvésre ösztönzést a  " . $qcld_article_text . "-rol, témában, és hozzon létre egy <a href> html címke hivatkozást: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "cs" ) {
            $prompt_text = " blog témata o ";
            $intro_text = "Napi úvodní zprávy o ";
            $conclusion_text = "Napi závěrečná zpráva o ";
            $introduction = "Úvodní zpráva";
            $conclusion = "Závěrečná zpráva";
            $faq_text = "Napi " . strval( $qcld_article_number_of_heading ) . " otázky a odpovědi o " . $qcld_article_text . ".";
            $faq_heading = "Často kladené otázky";
            // write a tagline about
            $tagline_text = "Napi tagline o ";
            $style_text = "Styl psaní: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Klíčová slova: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Vyhněte se slovům: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = "Napi hovor k akci o " . $qcld_article_text . " a vytvořte href tag link na: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "el" ) {
            $prompt_text = " θέματα ιστολογίου για ";
            $intro_text = "Γράψτε μια εισαγωγή για ";
            $conclusion_text = "Γράψτε μια συμπέραση για ";
            $introduction = "Εισαγωγή";
            $conclusion = "Συμπέραση";
            $faq_text = "Γράψτε " . strval( $qcld_article_number_of_heading ) . " ερωτήσεις και απαντήσεις για " . $qcld_article_text . ".";
            $faq_heading = "Συχνές ερωτήσεις";
            // write a tagline about
            $tagline_text = "Γράψτε μια tagline για ";
            $style_text = "Στυλ συγγραφής: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Λέξεις-κλειδιά: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Αποφύγετε τις εξής λέξεις: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = "Γράψτε μια κλήση σε ενέργεια για " . $qcld_article_text . " και δημιουργήστε έναν σύνδεσμο href tag στο: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "bg" ) {
            $prompt_text = " блог теми за ";
            $intro_text = "Напишете въведение за ";
            $conclusion_text = "Напишете заключение за ";
            $introduction = "Въведение";
            $conclusion = "Заключение";
            $faq_text = "Напишете " . strval( $qcld_article_number_of_heading ) . " въпроси и отговори за " . $qcld_article_text . ".";
            $faq_heading = "Често задавани въпроси";
            // write a tagline about
            $tagline_text = "Напишете tagline за ";
            $style_text = "Стил на писане: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Ключови думи: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Избягвайте думите: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = "Напишете действие за " . $qcld_article_text . " и създайте връзка href tag към: " . $qcld_article_target_label_cta . ".";

        } else if ( $qcld_article_language == "sv" ) {
            $prompt_text = " bloggämnen om ";
            $intro_text = "Skriv en introduktion om ";
            $conclusion_text = "Skriv en slutsats om ";
            $introduction = "Introduktion";
            $conclusion = "Slutsats";
            $faq_text = "Skriv " . strval( $qcld_article_number_of_heading ) . " frågor och svar om " . $qcld_article_text . ".";
            $faq_heading = "FAQ";
            // write a tagline about
            $tagline_text = "Skriv en tagline om ";
            $style_text = "Skrivstil: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Nyckelord: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Undvik ord: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = "Skriv ett åtgärdsförslag om " . $qcld_article_text . " och skapa en href tag-länk till: " . $qcld_article_target_label_cta . ".";

        } else {
            $prompt_text = " blog topics about ";
            $intro_text = "Write an introduction about ";
            $conclusion_text = "Write a conclusion about ";
            $introduction = "Introduction";
            $conclusion = "Conclusion";
            $faq_text = "Write " . strval( $qcld_article_number_of_heading ) . " questions and answers about " . $qcld_article_text . ".";
            $faq_heading = "Q&A";
            // write a tagline about
            $tagline_text = "Write a tagline about ";
            $style_text = "Writing style: " . $writing_style . ".";
            
            if ( empty($qcld_article_label_keywords) ) {
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . ".";
            } else {
                $keyword_text = ". Keywords: " . $qcld_article_label_keywords . ".";
                $myprompt = strval( $qcld_article_number_of_heading ) . $prompt_text . $qcld_article_text . $keyword_text;
            }
            
            // if $qcld_article_label_word_to_avoid is not empty, add it to the prompt
            
            if ( !empty($qcld_article_label_word_to_avoid) ) {
                $avoid_text = " Exclude the following keywords: " . $qcld_article_label_word_to_avoid . ".";
                $myprompt = $myprompt . $avoid_text;
            }
            
            $myintro = $intro_text . $qcld_article_text;
            $myconclusion = $conclusion_text . $qcld_article_text;
            $mytagline = $tagline_text . $qcld_article_text;
            // Write a call to action about $qcld_article_text and create a href tag link to: $qcld_article_target_label_cta.
            $mycta = "Write a call to action about " . $qcld_article_text . " and create a href tag link to: " . $qcld_article_target_label_cta . ".";
            
        }



        $result_data = '';

        if(!empty($qcld_article_text)){

            $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

            if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                $gemini_api_key     = get_option('qcld_gemini_api_key');
                $gemini_model       = get_option('qcld_gemini_model');
                $gemini_version     = get_option('qcld_gemini_api_version');

                $url = "https://generativelanguage.googleapis.com/{$gemini_version}/models/{$gemini_model}:generateContent?key={$gemini_api_key}";

                $generation_config = array();

                if( null !== get_option('qcld_gemini_max_token') && get_option('qcld_gemini_max_token') != "" ){
                    $generation_config["max_output_tokens"] = (int) esc_attr( get_option( 'qcld_gemini_max_token' ) );
                }

                if( null !== get_option('qcld_gemini_ai_temperature') && get_option('qcld_gemini_ai_temperature') != "" ){
                    $generation_config["temperature"] = (float) esc_attr( get_option( 'qcld_gemini_ai_temperature' ) );
                }

                if( null !== get_option('qcld_gemini_ai_top_p') && get_option('qcld_gemini_ai_top_p') != "" ){
                    $generation_config["top_p"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_p' ) );
                }

                if( null !== get_option('qcld_gemini_ai_top_k') && get_option('qcld_gemini_ai_top_k') != "" ){
                    $generation_config["top_k"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_k' ) );
                }

                //return $generation_config;

                //Build the input array to feed as Gemini API input
                $data = array(

                    "contents" => array(
                        array(
                            "role" => "user",
                            "parts" => array(
                                array(
                                    "text" => $myprompt
                                )
                            )
                        )
                    ),
                    "generation_config" => $generation_config,
                    
                );

                $json_data = json_encode($data);

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);

                curl_close($ch);

                if(curl_errno($ch)){

                    echo esc_html( 'Curl error: ' . curl_error($ch) );

                }

                $returnedData = json_decode( $response );

                $result_data = isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';
            
                $complete = preg_replace('/[\*]+/', '', $result_data);
                

            }else if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini' ){
                $gptkeyword = [];
                $ch = curl_init();
                $url = 'https://api.openai.com/v1/chat/completions';

                array_push($gptkeyword, array(
                           "role"       => "user",
                           "content"    =>  $myprompt
                        ));

                $post_fields = array(
                    "model"         => $ai_engines,
                    "messages"      => $gptkeyword,
                    "max_tokens"    => 200,
                    "temperature"   => 0
                );
                $header  = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $OPENAI_API_KEY
                ];
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error: ' . curl_error($ch);
                }
                curl_close($ch);
                $complete = json_decode( $result );
                // we need to catch the error here
                
                if ( isset( $complete->error ) ) {
                    $complete = $complete->error->message;
                    // exit
                    echo  esc_html( $complete ) ;
                    exit;
                } else {
                    //$complete = $complete->choices[0]->message->content;
                    $complete = isset( $complete->choices[0]->message->content ) ? trim( $complete->choices[0]->message->content ) : '';
                }

            }else{

                $request_body = [
                    "prompt"            => $myprompt,
                    "model"             => $ai_engines,
                    "max_tokens"        => (int)$max_token,
                    "temperature"       => (float)$temperature,
                    "presence_penalty"  => (float)$ppenalty,
                    "frequency_penalty" => (float)$fpenalty,
                    "top_p"             => 1,
                    "best_of"           => 1,
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
               // $results    = json_decode($result);

               // $result_data = isset( $results->choices[0]->text ) ? trim( $results->choices[0]->text ) : '';


                $complete = json_decode( $result );
                // we need to catch the error here
                
                if ( isset( $complete->error ) ) {
                    $complete = $complete->error->message;
                    // exit
                    echo  esc_html( $complete ) ;
                    exit;
                } else {
                    //$complete = $complete->choices[0]->text;
                    $complete = isset( $complete->choices[0]->text ) ? trim( $complete->choices[0]->text ) : '';
                }

            }
        
            // trim the text
            $complete = !empty( $complete ) ? trim( $complete ) : '';
            $mylist = array();
            $mylist = preg_split( "/\r\n|\n|\r/", $complete );
            // delete 1. 2. 3. etc from beginning of the line
            $mylist = preg_replace( '/^\\d+\\.\\s/', '', $mylist );
            $allresults = "";
            $qcld_article_heading_tag = sanitize_text_field( $_REQUEST["qcld_article_heading_tag"] );

           /* $hfHeadings = sanitize_text_field( $_REQUEST["hfHeadings"] );
            $hfHeadings2 = explode( ",", $hfHeadings );
            
            if ( $wpai_modify_headings == 1 && $is_generate_continue == 0 ) {
                foreach ( $mylist as $key => $value ) {
                    echo  '<' . $qcld_article_heading_tag . '>' . $value . '</' . $qcld_article_heading_tag . '>' ;
                }
                die;
            } else {
                
                if ( $wpai_modify_headings == 1 && $is_generate_continue == 1 ) {
                    $mylist = $hfHeadings2;
                } else {
                }
            
            }*/


        
            foreach ( $mylist as $key => $value ) {
                $withstyle = $value . '. ' . $style_text . ', ' . $tone_text . '.';
                // if avoid is not empty add it to the prompt
                if ( !empty(${$wpai_words_to_avoid}) ) {
                    $withstyle = $value . '. ' . $style_text . ', ' . $tone_text . ', ' . $avoid_text . '.';
                }

                $gptkeyword = [];
                

                $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

                if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                    $gemini_api_key     = get_option('qcld_gemini_api_key');
                    $gemini_model       = get_option('qcld_gemini_model');
                    $gemini_version     = get_option('qcld_gemini_api_version');

                    $url = "https://generativelanguage.googleapis.com/{$gemini_version}/models/{$gemini_model}:generateContent?key={$gemini_api_key}";

                    $generation_config = array();

                    if( null !== get_option('qcld_gemini_max_token') && get_option('qcld_gemini_max_token') != "" ){
                        $generation_config["max_output_tokens"] = (int) esc_attr( get_option( 'qcld_gemini_max_token' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_temperature') && get_option('qcld_gemini_ai_temperature') != "" ){
                        $generation_config["temperature"] = (float) esc_attr( get_option( 'qcld_gemini_ai_temperature' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_p') && get_option('qcld_gemini_ai_top_p') != "" ){
                        $generation_config["top_p"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_p' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_k') && get_option('qcld_gemini_ai_top_k') != "" ){
                        $generation_config["top_k"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_k' ) );
                    }

                    //return $generation_config;

                    //Build the input array to feed as Gemini API input
                    $data = array(

                        "contents" => array(
                            array(
                                "role" => "user",
                                "parts" => array(
                                    array(
                                        "text" => $myprompt
                                    )
                                )
                            )
                        ),
                        "generation_config" => $generation_config,
                        
                    );

                    $json_data = json_encode($data);

                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);

                    curl_close($ch);

                    if(curl_errno($ch)){

                        echo esc_html( 'Curl error: ' . curl_error($ch) );

                    }

                    $returnedData = json_decode( $response );

                    $result_data = isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';
                
                    $complete = preg_replace('/[\*]+/', '', $result_data);
                    

                }else if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini'){
                    $ch     = curl_init();
                    $url    = 'https://api.openai.com/v1/chat/completions';

                    array_push($gptkeyword, array(
                               "role"       => "user",
                               "content"    =>  $myprompt
                            ));

                    $post_fields = array(
                        "model"         => $ai_engines,
                        "messages"      => $gptkeyword,
                        "max_tokens"    => 200,
                        "temperature"   => 0
                    );
                    $header  = [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $OPENAI_API_KEY
                    ];
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error: ' . curl_error($ch);
                    }
                    curl_close($ch);
                    $complete = json_decode( $result );
                    $complete = isset($complete->choices[0]->message->content) ? $complete->choices[0]->message->content : '';

                }else{

                    $request_body = [
                        "prompt"            => $myprompt,
                        "model"             => $ai_engines,
                        "max_tokens"        => (int)$max_token,
                        "temperature"       => (float)$temperature,
                        "presence_penalty"  => (float)$ppenalty,
                        "frequency_penalty" => (float)$fpenalty,
                        "top_p"             => 1,
                        "best_of"           => 1,
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

                    $complete = json_decode( $result );
                    $complete = isset($complete->choices[0]->text) ? $complete->choices[0]->text : '';

                }
                // trim the text
                $complete = !empty($complete) ? trim( $complete ) : '';
                $value = str_replace( '\\/', '', $value );
                $value = str_replace( '\\', '', $value );
                // trim value
                $value = trim( $value );
                // we will add h tag if the user wants to

                if ( $qcld_article_heading_tag == "h1" ) {
                    $result = "\n"."<h1>" . $value . "</h1>" ."\n". $complete;
                } elseif ( $qcld_article_heading_tag == "h2" ) {
                    $result = "\n"."<h2>" . $value . "</h2>" ."\n". $complete;
                } elseif ( $qcld_article_heading_tag == "h3" ) {
                    $result = "\n"."<h3>" . $value . "</h3>" ."\n". $complete;
                } elseif ( $qcld_article_heading_tag == "h4" ) {
                    $result = "\n"."<h4>" . $value . "</h4>" ."\n". $complete;
                } elseif ( $qcld_article_heading_tag == "h5" ) {
                    $result = "\n"."<h5>" . $value . "</h5>" ."\n". $complete;
                } elseif ( $qcld_article_heading_tag == "h6" ) {
                    $result = "\n"."<h6>" . $value . "</h6>" ."\n". $complete;
                } else {
                    $result = "\n"."<h2>" . $value . "</h2>" ."\n". $complete;
                }



                $result = preg_replace('/[\*]+/', '', $result);
                
                $allresults = $allresults . $result;
            }



            
            if ( $qcld_article_heading_intro == "1" ) {
                // we need to catch the error here


                $gptkeyword = [];

                $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

                if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                    $gemini_api_key     = get_option('qcld_gemini_api_key');
                    $gemini_model       = get_option('qcld_gemini_model');
                    $gemini_version     = get_option('qcld_gemini_api_version');

                    $url = "https://generativelanguage.googleapis.com/{$gemini_version}/models/{$gemini_model}:generateContent?key={$gemini_api_key}";

                    $generation_config = array();

                    if( null !== get_option('qcld_gemini_max_token') && get_option('qcld_gemini_max_token') != "" ){
                        $generation_config["max_output_tokens"] = (int) esc_attr( get_option( 'qcld_gemini_max_token' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_temperature') && get_option('qcld_gemini_ai_temperature') != "" ){
                        $generation_config["temperature"] = (float) esc_attr( get_option( 'qcld_gemini_ai_temperature' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_p') && get_option('qcld_gemini_ai_top_p') != "" ){
                        $generation_config["top_p"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_p' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_k') && get_option('qcld_gemini_ai_top_k') != "" ){
                        $generation_config["top_k"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_k' ) );
                    }

                    //return $generation_config;

                    //Build the input array to feed as Gemini API input
                    $data = array(

                        "contents" => array(
                            array(
                                "role" => "user",
                                "parts" => array(
                                    array(
                                        "text" => $myintro
                                    )
                                )
                            )
                        ),
                        "generation_config" => $generation_config,
                        
                    );

                    $json_data = json_encode($data);

                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);

                    curl_close($ch);

                    if(curl_errno($ch)){

                        echo esc_html( 'Curl error: ' . curl_error($ch) );

                    }

                    $returnedData = json_decode( $response );

                    $result_data = isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';
                
                    $completeintro = preg_replace('/[\*]+/', '', $result_data);
                    
                    // trim the text
                    $completeintro = !empty( $completeintro ) ? trim( $completeintro ) : '';
                    // add <h1>Introuction</h1> to the beginning of the text
                    $completeintro = "\n"."<h1>" . $introduction . "</h1>" ."\n". $completeintro;
                    // add intro to the beginning of the text
                    $completeintro = preg_replace('/[\*]+/', '', $completeintro);
                    $allresults = $completeintro . $allresults;
                    
                    

                }else if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini'){
                    $ch     = curl_init();
                    $url    = 'https://api.openai.com/v1/chat/completions';

                    array_push($gptkeyword, array(
                               "role"       => "user",
                               "content"    =>  $myintro
                            ));

                    $post_fields = array(
                        "model"         => $ai_engines,
                        "messages"      => $gptkeyword,
                        "max_tokens"    => 200,
                        "temperature"   => 0
                    );
                    $header  = [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $OPENAI_API_KEY
                    ];
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error: ' . curl_error($ch);
                    }
                    curl_close($ch);
                    //$complete = json_decode( $result );
                   // $complete = isset($complete->choices[0]->message->content) ? $complete->choices[0]->message->content : '';

                    $completeintro = json_decode( $result );
                    
                    if ( isset( $completeintro->error ) ) {
                        $completeintro = $completeintro->error->message;
                        // exit
                        echo  esc_html( $completeintro ) ;
                        exit;
                    } else {
                        //$completeintro = $completeintro->choices[0]->message->content;
                        $completeintro = isset( $completeintro->choices[0]->message->content ) ? trim( $completeintro->choices[0]->message->content ) : '';
                        // trim the text
                        $completeintro = !empty( $completeintro ) ? trim( $completeintro ) : '';
                        // add <h1>Introuction</h1> to the beginning of the text
                        $completeintro = "\n"."<h1>" . $introduction . "</h1>" ."\n". $completeintro;
                        // add intro to the beginning of the text
                        $allresults = $completeintro . $allresults;
                    }

                }else{

                    $request_body = [
                        "prompt"            => $myintro,
                        "model"             => $ai_engines,
                        "max_tokens"        => (int)$max_token,
                        "temperature"       => (float)$temperature,
                        "presence_penalty"  => (float)$ppenalty,
                        "frequency_penalty" => (float)$fpenalty,
                        "top_p"             => 1,
                        "best_of"           => 1,
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

                    $completeintro = json_decode( $result );
                    
                    if ( isset( $completeintro->error ) ) {
                        $completeintro = $completeintro->error->message;
                        // exit
                        echo  esc_html( $completeintro ) ;
                        exit;
                    } else {
                        //$completeintro = $completeintro->choices[0]->text;
                        $completeintro = isset( $completeintro->choices[0]->text ) ? trim( $completeintro->choices[0]->text ) : '';
                        // trim the text
                        $completeintro = !empty( $completeintro ) ? trim( $completeintro ) : '';
                        // add <h1>Introuction</h1> to the beginning of the text
                        $completeintro = "\n"."<h1>" . $introduction . "</h1>" ."\n". $completeintro;
                        // add intro to the beginning of the text
                        $allresults = $completeintro . $allresults;
                    }

                }
            
            }
            
            // if wpai_add_faq is checked then call api with faq prompt
            
            if ( $qcld_article_heading_faq == "1" ) {
                // we need to catch the error here

                $gptkeyword = [];

                $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

                if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                    $gemini_api_key     = get_option('qcld_gemini_api_key');
                    $gemini_model       = get_option('qcld_gemini_model');
                    $gemini_version     = get_option('qcld_gemini_api_version');

                    $url = "https://generativelanguage.googleapis.com/{$gemini_version}/models/{$gemini_model}:generateContent?key={$gemini_api_key}";

                    $generation_config = array();

                    if( null !== get_option('qcld_gemini_max_token') && get_option('qcld_gemini_max_token') != "" ){
                        $generation_config["max_output_tokens"] = (int) esc_attr( get_option( 'qcld_gemini_max_token' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_temperature') && get_option('qcld_gemini_ai_temperature') != "" ){
                        $generation_config["temperature"] = (float) esc_attr( get_option( 'qcld_gemini_ai_temperature' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_p') && get_option('qcld_gemini_ai_top_p') != "" ){
                        $generation_config["top_p"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_p' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_k') && get_option('qcld_gemini_ai_top_k') != "" ){
                        $generation_config["top_k"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_k' ) );
                    }

                    //return $generation_config;

                    //Build the input array to feed as Gemini API input
                    $data = array(

                        "contents" => array(
                            array(
                                "role" => "user",
                                "parts" => array(
                                    array(
                                        "text" => $faq_text
                                    )
                                )
                            )
                        ),
                        "generation_config" => $generation_config,
                        
                    );

                    $json_data = json_encode($data);

                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);

                    curl_close($ch);

                    if(curl_errno($ch)){

                        echo esc_html( 'Curl error: ' . curl_error($ch) );

                    }

                    $returnedData = json_decode( $response );

                    $completefaq = isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';
                    
                    // trim the text
                    $completefaq = !empty( $completefaq ) ? trim( $completefaq ) : '';
                    // add <h1>FAQ</h1> to the beginning of the text
                    $completefaq = "\n"."<h2>" . $faq_heading . "</h2>" ."\n". $completefaq;
                    // add intro to the beginning of the text
                    $completefaq = preg_replace('/[\*]+/', '', $completefaq);
                    $allresults = $allresults . $completefaq;
                
                    

                }else if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini'){
                    $ch     = curl_init();
                    $url    = 'https://api.openai.com/v1/chat/completions';

                    array_push($gptkeyword, array(
                               "role"       => "user",
                               "content"    =>  $faq_text
                            ));

                    $post_fields = array(
                        "model"         => $ai_engines,
                        "messages"      => $gptkeyword,
                        "max_tokens"    => 200,
                        "temperature"   => 0
                    );
                    $header  = [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $OPENAI_API_KEY
                    ];
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error: ' . curl_error($ch);
                    }
                    curl_close($ch);

                    $completeintro = json_decode( $result );
                    
                    if ( isset( $completefaq->error ) ) {
                        $completefaq = $completefaq->error->message;
                        // exit
                        echo  esc_html( $completefaq ) ;
                        exit;
                    } else {
                        //$completefaq = $complete->choices[0]->message->content;
                        $completefaq = isset( $complete->choices[0]->message->content ) ? trim( $complete->choices[0]->message->content ) : '';
                        // trim the text
                        $completefaq = !empty( $completefaq ) ? trim( $completefaq ) : '';
                        // add <h1>FAQ</h1> to the beginning of the text
                        $completefaq = "\n"."<h2>" . $faq_heading . "</h2>" ."\n". $completefaq;
                        // add intro to the beginning of the text
                        $allresults = $allresults . $completefaq;
                    }

                }else{

                    $request_body = [
                        "prompt"            => $faq_text,
                        "model"             => $ai_engines,
                        "max_tokens"        => (int)$max_token,
                        "temperature"       => (float)$temperature,
                        "presence_penalty"  => (float)$ppenalty,
                        "frequency_penalty" => (float)$fpenalty,
                        "top_p"             => 1,
                        "best_of"           => 1,
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

                    $completefaq = json_decode( $result );
                    
                    if ( isset( $completefaq->error ) ) {
                        $completefaq = $completefaq->error->message;
                        // exit
                        echo  esc_html( $completefaq ) ;
                        exit;
                    } else {
                        //$completefaq = $completefaq->choices[0]->text;
                        $completefaq = isset( $completefaq->choices[0]->text ) ? trim( $completefaq->choices[0]->text ) : '';
                        // trim the text
                        $completefaq = !empty( $completefaq ) ? trim( $completefaq ) : '';
                        // add <h1>FAQ</h1> to the beginning of the text
                        $completefaq = "\n"."<h2>" . $faq_heading . "</h2>" ."\n". $completefaq;
                        // add intro to the beginning of the text
                        $allresults = $allresults . $completefaq;
                    }
                
                }
            
            }
            
            //if myconclusion is not empty,calls the openai api
            
            if ( $qcld_article_heading_conclusion == "1" ) {



                $gptkeyword = [];

                $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

                if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                    $gemini_api_key     = get_option('qcld_gemini_api_key');
                    $gemini_model       = get_option('qcld_gemini_model');
                    $gemini_version     = get_option('qcld_gemini_api_version');

                    $url = "https://generativelanguage.googleapis.com/{$gemini_version}/models/{$gemini_model}:generateContent?key={$gemini_api_key}";

                    $generation_config = array();

                    if( null !== get_option('qcld_gemini_max_token') && get_option('qcld_gemini_max_token') != "" ){
                        $generation_config["max_output_tokens"] = (int) esc_attr( get_option( 'qcld_gemini_max_token' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_temperature') && get_option('qcld_gemini_ai_temperature') != "" ){
                        $generation_config["temperature"] = (float) esc_attr( get_option( 'qcld_gemini_ai_temperature' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_p') && get_option('qcld_gemini_ai_top_p') != "" ){
                        $generation_config["top_p"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_p' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_k') && get_option('qcld_gemini_ai_top_k') != "" ){
                        $generation_config["top_k"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_k' ) );
                    }

                    //return $generation_config;

                    //Build the input array to feed as Gemini API input
                    $data = array(

                        "contents" => array(
                            array(
                                "role" => "user",
                                "parts" => array(
                                    array(
                                        "text" => $myconclusion
                                    )
                                )
                            )
                        ),
                        "generation_config" => $generation_config,
                        
                    );

                    $json_data = json_encode($data);

                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);

                    curl_close($ch);

                    if(curl_errno($ch)){

                        echo esc_html( 'Curl error: ' . curl_error($ch) );

                    }

                    $returnedData = json_decode( $response );

                    $completeconclusion = isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';
                    // trim the text
                    $completeconclusion = !empty( $completeconclusion ) ? trim( $completeconclusion ) : '';
                    // add <h1>Conclusion</h1> to the beginning of the text
                    $completeconclusion = "\n"."<h1>" . $conclusion . "</h1>" ."\n". $completeconclusion;
                    // add intro to the beginning of the text
                    $completeconclusion = preg_replace('/[\*]+/', '', $completeconclusion);
                    $allresults = $allresults . $completeconclusion;
                    
                    

                }else if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini'){
                    $ch     = curl_init();
                    $url    = 'https://api.openai.com/v1/chat/completions';

                    array_push($gptkeyword, array(
                               "role"       => "user",
                               "content"    =>  $myconclusion
                            ));

                    $post_fields = array(
                        "model"         => $ai_engines,
                        "messages"      => $gptkeyword,
                        "max_tokens"    => 200,
                        "temperature"   => 0
                    );
                    $header  = [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $OPENAI_API_KEY
                    ];
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error: ' . curl_error($ch);
                    }
                    curl_close($ch);
                    //$complete = json_decode( $result );
                   // $complete = isset($complete->choices[0]->message->content) ? $complete->choices[0]->message->content : '';

                    $completeconclusion = json_decode( $result );
                    
                    if ( isset( $completeconclusion->error ) ) {
                        $completeconclusion = $completeconclusion->error->message;
                        // exit
                        echo  esc_html( $completeconclusion ) ;
                        exit;
                    } else {
                        //$completeconclusion = $complete->choices[0]->message->content;
                        $completeconclusion = isset( $completeconclusion->choices[0]->message->content ) ? trim( $completeconclusion->choices[0]->message->content ) : '';
                        // trim the text
                        $completeconclusion = !empty( $completeconclusion ) ? trim( $completeconclusion ) : '';
                        // add <h1>Conclusion</h1> to the beginning of the text
                        $completeconclusion = "\n"."<h1>" . $conclusion . "</h1>" ."\n". $completeconclusion;
                        // add intro to the beginning of the text
                        $allresults = $allresults . $completeconclusion;
                    }

                }else{
         
                    // we need to catch the error here
                    $request_body = [
                        "prompt"            => $myconclusion,
                        "model"             => $ai_engines,
                        "max_tokens"        => (int)$max_token,
                        "temperature"       => (float)$temperature,
                        "presence_penalty"  => (float)$ppenalty,
                        "frequency_penalty" => (float)$fpenalty,
                        "top_p"             => 1,
                        "best_of"           => 1,
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

                    $completeconclusion = json_decode( $result );
                    
                    if ( isset( $completeconclusion->error ) ) {
                        $completeconclusion = $completeconclusion->error->message;
                        // exit
                        echo  esc_html( $completeconclusion ) ;
                        exit;
                    } else {
                        //$completeconclusion = $completeconclusion->choices[0]->text;
                        $completeconclusion = isset( $completeconclusion->choices[0]->text ) ? trim( $completeconclusion->choices[0]->text ) : '';
                        // trim the text
                        $completeconclusion = !empty( $completeconclusion ) ? trim( $completeconclusion ) : '';
                        // add <h1>Conclusion</h1> to the beginning of the text
                        $completeconclusion = "\n"."<h1>" . $conclusion . "</h1>" ."\n". $completeconclusion;
                        // add intro to the beginning of the text
                        $allresults = $allresults . $completeconclusion;
                    }
                }
            
            }
            
            // qcld_article_heading_tagline is checked then call the openai api
            
            if ( $qcld_article_heading_tagline == "1" ) {

                $gptkeyword = [];

                $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

                if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                    $gemini_api_key     = get_option('qcld_gemini_api_key');
                    $gemini_model       = get_option('qcld_gemini_model');
                    $gemini_version     = get_option('qcld_gemini_api_version');

                    $url = "https://generativelanguage.googleapis.com/{$gemini_version}/models/{$gemini_model}:generateContent?key={$gemini_api_key}";

                    $generation_config = array();

                    if( null !== get_option('qcld_gemini_max_token') && get_option('qcld_gemini_max_token') != "" ){
                        $generation_config["max_output_tokens"] = (int) esc_attr( get_option( 'qcld_gemini_max_token' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_temperature') && get_option('qcld_gemini_ai_temperature') != "" ){
                        $generation_config["temperature"] = (float) esc_attr( get_option( 'qcld_gemini_ai_temperature' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_p') && get_option('qcld_gemini_ai_top_p') != "" ){
                        $generation_config["top_p"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_p' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_k') && get_option('qcld_gemini_ai_top_k') != "" ){
                        $generation_config["top_k"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_k' ) );
                    }

                    //return $generation_config;

                    //Build the input array to feed as Gemini API input
                    $data = array(

                        "contents" => array(
                            array(
                                "role" => "user",
                                "parts" => array(
                                    array(
                                        "text" => $mytagline
                                    )
                                )
                            )
                        ),
                        "generation_config" => $generation_config,
                        
                    );

                    $json_data = json_encode($data);

                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);

                    curl_close($ch);

                    if(curl_errno($ch)){

                        echo esc_html( 'Curl error: ' . curl_error($ch) );

                    }

                    $returnedData = json_decode( $response );

                    $completetagline = isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';
            
                    // trim the text
                    $completetagline = !empty( $completetagline ) ? trim( $completetagline ) : '';
                    // add <p> to the beginning of the text
                    $completetagline = "\n"."<p>" . $completetagline . "</p>"."\n";
                    // add intro to the beginning of the text
                    $completetagline = preg_replace('/[\*]+/', '', $completetagline);
                    $allresults = $completetagline . $allresults;
                
                    

                }else if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini'){
                    $ch     = curl_init();
                    $url    = 'https://api.openai.com/v1/chat/completions';

                    array_push($gptkeyword, array(
                               "role"       => "user",
                               "content"    =>  $mytagline
                            ));

                    $post_fields = array(
                        "model"         => $ai_engines,
                        "messages"      => $gptkeyword,
                        "max_tokens"    => 200,
                        "temperature"   => 0
                    );
                    $header  = [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $OPENAI_API_KEY
                    ];
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error: ' . curl_error($ch);
                    }
                    curl_close($ch);
                    //$complete = json_decode( $result );
                   // $complete = isset($complete->choices[0]->message->content) ? $complete->choices[0]->message->content : '';

                    $completetagline = json_decode( $result );
                    
                    if ( isset( $completetagline->error ) ) {
                        $completetagline = $completetagline->error->message;
                        // exit
                        echo  esc_html( $completetagline ) ;
                        exit;
                    } else {
                        $completetagline = isset( $completetagline->choices[0]->message->content ) ? trim( $completetagline->choices[0]->message->content ) : '';
                        // trim the text
                        $completetagline = !empty($completetagline) ? trim( $completetagline ) : '';
                        // add <p> to the beginning of the text
                        $completetagline = "\n"."<p>" . $completetagline . "</p>"."\n";
                        // add intro to the beginning of the text
                        $allresults = $completetagline . $allresults;
                    }

                }else{

                    // we need to catch the error here
                    $request_body = [
                        "prompt"            => $mytagline,
                        "model"             => $ai_engines,
                        "max_tokens"        => (int)$max_token,
                        "temperature"       => (float)$temperature,
                        "presence_penalty"  => (float)$ppenalty,
                        "frequency_penalty" => (float)$fpenalty,
                        "top_p"             => 1,
                        "best_of"           => 1,
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

                    $completetagline = json_decode( $result );
                    
                    if ( isset( $completetagline->error ) ) {
                        $completetagline = $completetagline->error->message;
                        // exit
                        echo  esc_html( $completetagline ) ;
                        exit;
                    } else {
                        //$completetagline = $completetagline->choices[0]->text;
                        $completetagline = isset( $completetagline->choices[0]->text ) ? trim( $completetagline->choices[0]->text ) : '';
                        // trim the text
                        $completetagline = !empty($completetagline) ? trim( $completetagline ) : '';
                        // add <p> to the beginning of the text
                        $completetagline = "\n"."<p>" . $completetagline . "</p>"."\n";
                        // add intro to the beginning of the text
                        $allresults = $completetagline . $allresults;
                    }

                }
            
            }
            
            // if qcld_article_label_keywords_bold is checked then then find all keywords and bold them. keywords are separated by comma
            if ( $qcld_article_label_keywords_bold == "1" ) {
                // check to see at least one keyword is entered
                
                if ( $qcld_article_label_keywords != "" ) {
                    // split keywords by comma if there are more than one but if there is only one then it will not split
                    
                    if ( strpos( $qcld_article_label_keywords, ',' ) !== false ) {
                        $keywords = explode( ",", $qcld_article_label_keywords );
                    } else {
                        $keywords = array( $qcld_article_label_keywords );
                    }
                    
                    // loop through keywords and bold them
                    foreach ( $keywords as $keyword ) {
                        $keyword = trim( $keyword );
                        // replace keyword with bold keyword but make sure exact match is found. for example if the keyword is "the" then it should not replace "there" with "there".. capital dont matter
                        $allresults = preg_replace( '/\\b' . $keyword . '\\b/', '<strong>' . $keyword . '</strong>', $allresults );
                    }
                }
            
            }
            // if qcld_article_target_url and qcld_article_label_anchor_text is not empty then find qcld_article_label_anchor_text in the text and create a link using qcld_article_target_url
            if ( $qcld_article_target_url != "" && $qcld_article_label_anchor_text != "" ) {
                // create a link if anchor text found.. rules: 1. only for first occurance 2. exact match 3. case insensitive 4. if anchor text found inside any h1,h2,h3,h4,h5,h6, a then skip it. 5. use anchor text to create link dont replace it with existing text
                $allresults = preg_replace(
                    '/(?<!<h[1-6]><a href=")(?<!<a href=")(?<!<h[1-6]>)(?<!<h[1-6]><strong>)(?<!<strong>)(?<!<h[1-6]><em>)(?<!<em>)(?<!<h[1-6]><strong><em>)(?<!<strong><em>)(?<!<h[1-6]><em><strong>)(?<!<em><strong>)\\b' . $qcld_article_label_anchor_text . '\\b(?![^<]*<\\/a>)(?![^<]*<\\/h[1-6]>)(?![^<]*<\\/strong>)(?![^<]*<\\/em>)(?![^<]*<\\/strong><\\/em>)(?![^<]*<\\/em><\\/strong>)/i',
                    '<a href="' . $qcld_article_target_url . '">' . $qcld_article_label_anchor_text . '</a>',
                    $allresults,
                    1
                );
            }


            // if qcld_article_target_label_cta is not empty then call api to get cta text and create a link using qcld_article_target_label_cta
            
            if ( $qcld_article_target_label_cta != "" ) {


                $gptkeyword = [];

                $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

                if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                    $gemini_api_key     = get_option('qcld_gemini_api_key');
                    $gemini_model       = get_option('qcld_gemini_model');
                    $gemini_version     = get_option('qcld_gemini_api_version');

                    $url = "https://generativelanguage.googleapis.com/{$gemini_version}/models/{$gemini_model}:generateContent?key={$gemini_api_key}";

                    $generation_config = array();

                    if( null !== get_option('qcld_gemini_max_token') && get_option('qcld_gemini_max_token') != "" ){
                        $generation_config["max_output_tokens"] = (int) esc_attr( get_option( 'qcld_gemini_max_token' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_temperature') && get_option('qcld_gemini_ai_temperature') != "" ){
                        $generation_config["temperature"] = (float) esc_attr( get_option( 'qcld_gemini_ai_temperature' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_p') && get_option('qcld_gemini_ai_top_p') != "" ){
                        $generation_config["top_p"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_p' ) );
                    }

                    if( null !== get_option('qcld_gemini_ai_top_k') && get_option('qcld_gemini_ai_top_k') != "" ){
                        $generation_config["top_k"] = (float) esc_attr( get_option( 'qcld_gemini_ai_top_k' ) );
                    }

                    //return $generation_config;

                    //Build the input array to feed as Gemini API input
                    $data = array(

                        "contents" => array(
                            array(
                                "role" => "user",
                                "parts" => array(
                                    array(
                                        "text" => $mycta
                                    )
                                )
                            )
                        ),
                        "generation_config" => $generation_config,
                        
                    );

                    $json_data = json_encode($data);

                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);

                    curl_close($ch);

                    if(curl_errno($ch)){

                        echo esc_html( 'Curl error: ' . curl_error($ch) );

                    }

                    $returnedData = json_decode( $response );

                    $completecta = isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';
                
                    $result_data = preg_replace('/[\*]+/', '', $result_data);
                        
 
                    $completecta = isset( $completecta->candidates[0]->output ) ? trim( $completecta->candidates[0]->output ) : '';
                    // trim the text
                    $completecta = !empty($completecta) ? trim( $completecta ) : '';
                    // add <p> to the beginning of the text
                    $completecta = "<p>" . $completecta . "</p>"."\n";
                    $completecta = preg_replace('/[\*]+/', '', $completecta);
                    
                    if ( $wpai_cta_pos == "beg" ) {
                        $allresults = preg_replace(
                            '/(<h[1-6]>)/',
                            $completecta . ' $1',
                            $allresults,
                            1
                        );
                    } else {
                        $allresults = $allresults . $completecta;
                    }
                    
                    
                    

                }else if( $ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini'){
                    $ch = curl_init();
                    $url = 'https://api.openai.com/v1/chat/completions';

                    array_push($gptkeyword, array(
                               "role"       => "user",
                               "content"    =>  $mycta
                            ));

                    $post_fields = array(
                        "model"         => $ai_engines,
                        "messages"      => $gptkeyword,
                        "max_tokens"    => 200,
                        "temperature"   => 0
                    );
                    $header  = [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $OPENAI_API_KEY
                    ];
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error: ' . curl_error($ch);
                    }
                    curl_close($ch);
                    //$complete = json_decode( $result );
                   // $complete = isset($complete->choices[0]->message->content) ? $complete->choices[0]->message->content : '';

                    // we need to catch the error here
                    $completecta = json_decode( $result );
                    
                    if ( isset( $completecta->error ) ) {
                        $completecta = $completecta->error->message;
                        // exit
                        echo  esc_html( $completecta ) ;
                        exit;
                    } else {
                        //$completecta = $completecta->choices[0]->message->content;
                        $completecta = isset( $completecta->choices[0]->message->content ) ? trim( $completecta->choices[0]->message->content ) : '';
                        // trim the text
                        $completecta = !empty($completecta) ? trim( $completecta ) : '';
                        // add <p> to the beginning of the text
                        $completecta = "<p>" . $completecta . "</p>"."\n";
                        
                        if ( $wpai_cta_pos == "beg" ) {
                            $allresults = preg_replace(
                                '/(<h[1-6]>)/',
                                $completecta . ' $1',
                                $allresults,
                                1
                            );
                        } else {
                            $allresults = $allresults . $completecta;
                        }
                    
                    }

                }else{

                    // call api to get cta text
                    $request_body = [
                        "prompt"            => $mycta,
                        "model"             => $ai_engines,
                        "max_tokens"        => (int)$max_token,
                        "temperature"       => (float)$temperature,
                        "presence_penalty"  => (float)$ppenalty,
                        "frequency_penalty" => (float)$fpenalty,
                        "top_p"             => 1,
                        "best_of"           => 1,
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

                    // we need to catch the error here
                    $completecta = json_decode( $result );
                    
                    if ( isset( $completecta->error ) ) {
                        $completecta = $completecta->error->message;
                        // exit
                        echo  esc_html( $completecta ) ;
                        exit;
                    } else {
                        //$completecta = $completecta->choices[0]->text;
                        $completecta = isset( $completecta->choices[0]->text ) ? trim( $completecta->choices[0]->text ) : '';
                        // trim the text
                        $completecta = !empty($completecta) ? trim( $completecta ) : '';
                        // add <p> to the beginning of the text
                        $completecta = "<p>" . $completecta . "</p>"."\n";
                        
                        if ( $wpai_cta_pos == "beg" ) {
                            $allresults = preg_replace(
                                '/(<h[1-6]>)/',
                                $completecta . ' $1',
                                $allresults,
                                1
                            );
                        } else {
                            $allresults = $allresults . $completecta;
                        }
                    
                    }

                }
            
            }
            
            // if add image is checked then we should send api request to get image

            $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');
            
            if ( $qcld_article_heading_img == "1" && $qcld_ai_settings_open_ai !== 'gemini' ) {

                $request_body = [
                    "prompt"            => $qcld_article_text,
                    "model"             => 'dall-e-3',
                    "n"                 => 1,
                    "size"              => $img_size,
                    "response_format"   => "url",
                ];
                $data    = json_encode($request_body);
                $url     = "https://api.openai.com/v1/images/generations";
                $apt_key = "Authorization: Bearer ". $OPENAI_API_KEY;

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers    = array(
                   "Content-Type: application/json",
                   $apt_key,
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $result     = curl_exec($curl);
                curl_close($curl);

                // we need to catch the error here
                $imgresult = json_decode( $result );


                $imgresult = $imgresult->data[0]->url;


                $array              = explode('/', getimagesize($imgresult)['mime']);
                $imagetype          = end($array);
                $uniq_name          = md5($imgresult);
                $filename           = $uniq_name . '.' . $imagetype;

                $uploaddir          = wp_upload_dir();
                $target_file_name   = $uploaddir['path'] . '/' . $filename;

                $contents           = file_get_contents( $imgresult );
                $savefile           = fopen($target_file_name, 'w');
                fwrite($savefile, $contents);
                fclose($savefile);

                /* add the image title */
                $image_title        = ucwords( $uniq_name );

                $qcld_seo_openai_images_attribution = 'gpt openai';

                /* add the caption */
                $attachment_caption = '';
                if (! isset($qcld_seo_openai_images_attribution['attribution']) | isset($qcld_seo_openai_images_attribution['attribution']) == 'true')
                    $attachment_caption = '<a href="' . esc_url( $imgresult ) . '" target="_blank" rel="noopener">' . esc_attr( $filename ) . '</a>';

                unset($imgresult);

                /* insert the attachment */
                $wp_filetype = wp_check_filetype(basename($target_file_name), null);
                $attachment  = array(
                    'guid'              => $uploaddir['url'] . '/' . basename($target_file_name),
                    'post_mime_type'    => $wp_filetype['type'],
                    'post_title'        => $image_title,
                    'post_status'       => 'inherit'
                );
                $post_id     = isset($_REQUEST['post_id']) ? absint($_REQUEST['post_id']): '';
                $attach_id   = wp_insert_attachment($attachment, $target_file_name, $post_id);
                if ($attach_id == 0)
                    die('Error: File attachment error');

                $attach_data = wp_generate_attachment_metadata($attach_id, $target_file_name);
                $result      = wp_update_attachment_metadata($attach_id, $attach_data);

                $image_data                 = array();
                $image_data['ID']           = $attach_id;
                $image_data['post_excerpt'] = $attachment_caption;
                wp_update_post($image_data);

                $parsed = wp_get_attachment_image_src( $attach_id, 'full' )[0];

                if(!empty($parsed)){
                    $attach_id = $parsed;
                }



                $imgresult = "\n"."<img src='" . $attach_id . "' alt='" . $qcld_article_text . "' />"."\n";
                // get half of qcld_article_number_of_heading and insert image in the middle
                $half = intval( $qcld_article_number_of_heading ) / 2;
                $half = round( $half );
                $half = $half - 1;
                // use qcld_article_heading_tag to add heading tag to image
                $allresults = explode( "</" . $qcld_article_heading_tag . ">", $allresults );
                $allresults[$half] = $allresults[$half] . $imgresult;
                $allresults = implode( "</" . $qcld_article_heading_tag . ">", $allresults );
                //print_r( $allresults );
               // die;

                wp_send_json( [ 'status' => 'success', 'keywords' => $allresults ] );
                wp_die();

            } else {
                //print_r( $allresults );
                //die;

                wp_send_json( [ 'status' => 'success', 'keywords' => $allresults ] );
                wp_die();
            }


        }
    
        wp_send_json( [ 'status' => 'success', 'keywords' => $result_data ] );
        wp_die();
        
        // var_dump($dataresponse);wp_die();

    }
}


add_action( 'wp_ajax_qcldseohelp_keyword_suggestion_tag', 'qcldseohelp_keyword_suggestion_tag' );
add_action( 'wp_ajax_nopriv_qcldseohelp_keyword_suggestion_tag', 'qcldseohelp_keyword_suggestion_tag' );

if ( ! function_exists( 'qcldseohelp_keyword_suggestion_tag' ) ) {
    function qcldseohelp_keyword_suggestion_tag(){

    	check_ajax_referer( 'seo-help-pro', 'security');

        $keyword        = isset($_POST['keyword'])          ? sanitize_text_field($_POST['keyword']) : '';
        $toolLanguage   = isset($_POST['selectedlanguage']) ? sanitize_text_field($_POST['selectedlanguage']) : '';
        $targetLanguage = isset($_POST['selectedlanguage']) ? sanitize_text_field($_POST['selectedlanguage']) : '';
        $toolCountry    = isset($_POST['selectedCountry'])  ? sanitize_text_field($_POST['selectedCountry']) : '';
        // $security    = sanitize_text_field($_POST['security']);

        $source="en";

        session_start();
        if(isset($_SESSION['qcld_targetlanguage'])){
            unset($_SESSION['qcld_targetlanguage']);
        }
        $_SESSION['qcld_targetlanguage'] = $targetLanguage;
 
        $target_text = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" .$source. "&tl=" .$targetLanguage. "&dt=t&q=". rawurlencode($keyword);

        $response = file_get_contents($target_text);
        $obj =json_decode($response,true);

        $target_keyword = ( isset($obj[0][0][0]) && !empty($obj[0][0][0]) ) ? $obj[0][0][0] : $keyword;


        $url = sprintf("http://suggestqueries.google.com/complete/search?client=chrome&hl=%s&gl=%s&q=%s", $toolLanguage, $toolCountry, urlencode($target_keyword));
        $json = file_get_contents($url);
        $results = json_decode(utf8_encode($json));

        $results_data = '';
        foreach ($results[1] as $key => $keywordResultss){
          
            $results_data.= '<br><input type="checkbox" id="keywordsuggetions_'.esc_attr($key).'" class="form-check-input"  value="'.esc_attr($keywordResultss).'"/><label for="keywordsuggetions_'.esc_attr($key).'" class="form-check-label">'.$keywordResultss.'</label>';
            
        }

        
        wp_send_json(['status'=> 'success','keywords'=> $results_data]);
        wp_die();
        // var_dump($dataresponse); wp_die();

    }
}

if ( ! function_exists( 'qcld_linkbait_add_tags_to_pages' ) ) {
    function qcld_linkbait_add_tags_to_pages() {
        register_taxonomy_for_object_type( 'post_tag', 'page' );
    }
}
add_action( 'init', 'qcld_linkbait_add_tags_to_pages');

add_action('wp_ajax_qcld_linkbait_add_ontag', 'qcld_linkbait_add_ontag' );
if ( ! function_exists( 'qcld_linkbait_add_ontag' ) ) {
    function qcld_linkbait_add_ontag (){

    	check_ajax_referer( 'seo-help-pro', 'security');
       
        $post_id = sanitize_text_field( $_POST['postid'] );
        $keywordkeys = $_POST['keywordkeys'];
        wp_set_post_tags( $post_id, $keywordkeys, true);
        //var_dump($keywordkeys);
        $result_data = esc_html('Data Successfully Submitted');
        wp_send_json( [ 'status' => 'success', 'keywords' => $result_data ] );
        wp_die();
    }
}

add_action( 'wp_ajax_qcld_seo_save_draft_post_extra', 'qcld_seo_save_draft_post' );
add_action( 'wp_ajax_nopriv_qcld_seo_save_draft_post_extra', 'qcld_seo_save_draft_post' );
if ( ! function_exists( 'qcld_seo_save_draft_post' ) ) {
    function qcld_seo_save_draft_post(){

        check_ajax_referer( 'seo-help-pro', 'security');

        $qcld_seo_result = array(
            'status' => 'error',
            'msg'    => 'Something went wrong',
        );
        
        if ( isset( $_POST['title'] ) && !empty($_POST['title']) && isset( $_POST['content'] ) && !empty($_POST['content']) ) {

            $qcld_seo_allowed_html_content_post = wp_kses_allowed_html( 'post' );
            $qcld_seo_title     = sanitize_text_field( $_POST['title'] );
            $qcld_seo_content   = wp_kses( $_POST['content'], $qcld_seo_allowed_html_content_post );
            $qcld_seo_post_id   = wp_insert_post( array(
                'post_title'    => $qcld_seo_title,
                'post_content'  => $qcld_seo_content,
                ) 
            );
            
            if ( !is_wp_error( $qcld_seo_post_id ) ) {
                if ( array_key_exists( 'qcld_seo_settings', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_meta_key', $_POST['qcld_seo_settings'] );
                }
                if ( array_key_exists( 'qcld_seo_language', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_language', $_POST['qcld_seo_language'] );
                }
                if ( array_key_exists( 'qcld_seo_preview_title', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_preview_title', $_POST['qcld_seo_preview_title'] );
                }
                if ( array_key_exists( 'qcld_seo_number_of_heading', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_number_of_heading', $_POST['qcld_seo_number_of_heading'] );
                }
                if ( array_key_exists( 'qcld_seo_heading_tag', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_heading_tag', $_POST['qcld_seo_heading_tag'] );
                }
                if ( array_key_exists( 'qcld_seo_writing_style', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_writing_style', $_POST['qcld_seo_writing_style'] );
                }
                if ( array_key_exists( 'qcld_seo_writing_tone', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_writing_tone', $_POST['qcld_seo_writing_tone'] );
                }
                if ( array_key_exists( 'qcld_seo_modify_headings', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_modify_headings', $_POST['qcld_seo_modify_headings'] );
                }
                if ( array_key_exists( 'qcld_seo_add_img', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_add_img', $_POST['qcld_seo_add_img'] );
                }
                if ( array_key_exists( 'qcld_seo_add_tagline', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_add_tagline', $_POST['qcld_seo_add_tagline'] );
                }
                if ( array_key_exists( 'qcld_seo_add_intro', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_add_intro', $_POST['qcld_seo_add_intro'] );
                }
                if ( array_key_exists( 'qcld_seo_add_conclusion', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_add_conclusion', $_POST['qcld_seo_add_conclusion'] );
                }
                if ( array_key_exists( 'qcld_seo_anchor_text', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_anchor_text', $_POST['qcld_seo_anchor_text'] );
                }
                if ( array_key_exists( 'qcld_seo_target_url', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_target_url', $_POST['qcld_seo_target_url'] );
                }
                if ( array_key_exists( 'qcld_seo_generated_text', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_generated_text', $_POST['qcld_seo_generated_text'] );
                }
                // qcld_seo_cta_pos
                if ( array_key_exists( 'qcld_seo_cta_pos', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_cta_pos', $_POST['qcld_seo_cta_pos'] );
                }
                // qcld_seo_target_url_cta
                if ( array_key_exists( 'qcld_seo_target_url_cta', $_POST ) ) {
                    update_post_meta( $qcld_seo_post_id, 'qcld_seo_target_url_cta', $_POST['qcld_seo_target_url_cta'] );
                }
                $qcld_seo_result['status']  	= 'success';
                $qcld_seo_result['msg']     	= 'Data Successfully Submitted.';
                $qcld_seo_result['id']      	= $qcld_seo_post_id;
                $qcld_seo_result['post_link'] 	= esc_url( admin_url('edit.php') );
            }
        
        }
        
        wp_send_json( $qcld_seo_result );
    }
}