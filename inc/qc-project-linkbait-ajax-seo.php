<?php 

defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'qcld_render_linkbait_seo_tips' ) ) {
	function qcld_render_linkbait_seo_tips() { //Function For Content Writting Tips //

        check_ajax_referer( 'seo-help-pro', 'security');

		$title = isset($_POST["linkbait_seo_id"]) ? sanitize_text_field(trim($_POST['linkbait_seo_id'])) : '';
		
		?>

		<div id="sm-modal" class="modal">

			<!-- Modal content -->
			<div class="modal-content" style="width: 60%;">
				<span class="close"><?php esc_html_e( "×", 'seo-help' ); ?></span>
				<h3><?php esc_html_e( "SEO Content Writing Tips", 'seo-help' ); ?></h3>
				<hr/>
				<div class="sm_shortcode_list">
					<h2><span style="color:red"><?php esc_html_e( "Coming Soon", 'seo-help' ); ?></span></h2>
				</div>
			</div>

		</div>
		<?php
		exit;
	}
}
add_action( 'wp_ajax_qcld_linkbait_seo_tips', 'qcld_render_linkbait_seo_tips');

if ( ! function_exists( 'qcldseohelp_keyword_suggestion' ) ) {
	function qcldseohelp_keyword_suggestion(){

        check_ajax_referer( 'seo-help-pro', 'security');

		$keyword 		= isset($_POST["keyword"]) ? sanitize_text_field($_POST['keyword']) : '';
		$toolLanguage 	= isset($_POST["selectedlanguage"]) ? sanitize_text_field($_POST['selectedlanguage']) : '';
		$toolCountry 	= isset($_POST["selectedCountry"]) ? sanitize_text_field($_POST['selectedCountry']) : '';
		//$security = sanitize_text_field($_POST['security']);
		$args = array(
			'user-agent'  => ''
		);
		$srt_engine = 'google';
		$srt_service_option = 'complete';
		$srt_service = 'suggestqueries';
		$srt_browser_option = 'firefox';
		$srt_option = 'search';

		$dataresponse = wp_remote_request( 'http://'.$srt_service.'.'.$srt_engine.'.com/'.$srt_service_option.'/'.$srt_option.'?output='.$srt_browser_option.'&client=psy-ab&gs_rn=64o&hl='.$toolLanguage.'&gl='.$toolCountry.'&q='.urlencode($keyword), $args );
		$data = $dataresponse['body'];	
		
		$responseCode = $dataresponse['response']['code'];
		if (!empty($responseCode) and $responseCode !== 200){
			print_r(json_encode(''));	
			wp_die();
		} 
		
		
		$data = htmlentities($data, ENT_NOQUOTES, "ISO-8859-1");
		
		
		if (($data = json_decode($data, true)) !== null) {
			$keywords = $data[1];
			$keywordsArray = [];
			
			foreach ($keywords as $key => $keywordResults){
				if($key != 0){
					$keywordsArray[$key] = sanitize_text_field($keywordResults[0]);
				}
			}
			$keywords = $keywordsArray;
			
		} else {
			$keywords = '';	
		}
		
		wp_send_json(['status'=> 'success','keywords'=> $keywords]);
		wp_die();
		// var_dump($dataresponse);wp_die();

	}
}
add_action( 'wp_ajax_qcldseohelp_keyword_suggestion', 'qcldseohelp_keyword_suggestion');

if ( ! function_exists( 'qcld_linkbait_outline_data' ) ) {
	function qcld_linkbait_outline_data(){

        check_ajax_referer( 'seo-help-pro', 'security');

		$keyword 		= isset($_POST["suggesstions"]) ? $_POST["suggesstions"] : '';
		$OPENAI_API_KEY = get_option('qcld_seohelp_api_key');
		$ai_engines 	= get_option('qcld_seohelp_ai_engines');
		$max_token 		= get_option('qcld_seohelp_max_token');
		$temperature 	= get_option('qcld_seohelp_ai_temperature');
		$ppenalty 		= get_option('qcld_seohelp_ai_ppenalty');
		$fpenalty 		= get_option('qcld_seohelp_ai_fpenalty');
		$prompt 		= "Create an outline for an about ". $keyword[0];



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
                                    "text" => $prompt
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

               // var_dump( $complete );
                //wp_die();

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

            $result = '';
            if(!empty($complete)){
               $suggesstions = explode("\n", $complete );

                foreach ($suggesstions as $key => $suggesstion) {

                    if( !empty( $suggesstion ) ){

                        $result .= '<div class="toast-container position-relative start-0" width="750"><div class="position-absolute"><div id="toster'.esc_attr($key).'" class="toast" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-body hide-toast-body" id="toster-data-'.esc_attr($key).'"><ul class="list-group list-group-horizontal"><li class="list-group-item"> <a href="#" class="item-para" data-outline="'.esc_html($suggesstion).'" data-outlineresultid="outline-data-'.esc_attr($key).'">Paragraph</a></li><li class="list-group-item" data-classes="rewrite_content" data-id="rewrite_con'.esc_attr($key).'">Rewrite</li><li data-classes="edit_outline" data-id="outline'.esc_attr($key).'" class="list-group-item">Edit outline</li></ul><button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button></div></div></div></div><div class="accordion-item"><h2 class="accordion-header" id="panelsStayOpen-heading'.esc_attr($key).'"><div class="accordion-button"  data-id="toster'.esc_attr($key).'" data-classes="toster_accordion" data-toster="toster-data-'.esc_attr($key).'" id="outline'.esc_attr($key).'">'.esc_html($suggesstion).'</div></h2><div><textarea id="outline-data-'.esc_attr($key).'" class="result-outline form-control" rows="3" style="display: none"></textarea></div></div>';
                    }
                   
                }

                
                    

            }

            //var_dump( $result );
            //wp_die();




		wp_send_json(['status'=> 'success','results'=> $result]);
		wp_die();
	}			
}
add_action( 'wp_ajax_qcld_linkbait_outline_data', 'qcld_linkbait_outline_data');

if ( ! function_exists( 'open_save_settings' ) ) {
	function open_save_settings(){

        check_ajax_referer( 'seo-help-pro', 'security');

		$api_key 			= isset($_POST["api_key"]) 			? sanitize_text_field(($_POST["api_key"])) 			: '';
		$opeai_engines 		= isset($_POST["opeai_engines"]) 	? sanitize_text_field(($_POST["opeai_engines"])) 	: '';
		$max_token 			= isset($_POST["max_token"]) 		? sanitize_text_field(($_POST["max_token"])) 		: '';
		$temperature 		= isset($_POST["temperature"]) 		? sanitize_text_field(($_POST["temperature"])) 		: '';
		$presence_penalty 	= isset($_POST["presence_penalty"]) ? sanitize_text_field(($_POST["presence_penalty"])) : '';
		$frequency_penalty 	= isset($_POST["frequency_penalty"])? sanitize_text_field(($_POST["frequency_penalty"])): '';



		update_option('qcld_seohelp_api_key', $api_key);
		update_option('qcld_seohelp_ai_engines', $opeai_engines);
		update_option('qcld_seohelp_max_token', $max_token);
		update_option('qcld_seohelp_ai_temperature', $temperature);
		update_option('qcld_seohelp_ai_ppenalty', $presence_penalty);
		update_option('qcld_seohelp_ai_fpenalty', $frequency_penalty);


        $frequency_penalty  = isset($_POST["frequency_penalty"])? sanitize_text_field(($_POST["frequency_penalty"])): '';
        update_option('qcld_seohelp_ai_fpenalty', $frequency_penalty);


		$qcld_seohelp_api_key = get_option('qcld_seohelp_api_key');

        $ai_settings_open_ai        = isset($_POST["ai_settings_open_ai"]) ? sanitize_text_field(($_POST["ai_settings_open_ai"])) : '';
        update_option('qcld_ai_settings_open_ai', $ai_settings_open_ai );

		$results = esc_html('Data Updated Successfully', 'seo-help');

		wp_send_json(['status'=> 'success','results'=> $results]);
		wp_die();
	}
}
add_action( 'wp_ajax_open_save_settings', 'open_save_settings');

if ( ! function_exists( 'gemini_save_settings' ) ) {
    function gemini_save_settings(){

        check_ajax_referer( 'seo-help-pro', 'security');

        $qcld_gemini_api_key            = isset($_POST["qcld_gemini_api_key"])          ? sanitize_text_field(($_POST["qcld_gemini_api_key"]))          : '';
        $qcld_gemini_model              = isset($_POST["qcld_gemini_model"])            ? sanitize_text_field(($_POST["qcld_gemini_model"]))            : '';
        $qcld_gemini_api_version        = isset($_POST["qcld_gemini_api_version"])      ? sanitize_text_field(($_POST["qcld_gemini_api_version"]))      : '';
        $qcld_gemini_max_token          = isset($_POST["qcld_gemini_max_token"])        ? sanitize_text_field(($_POST["qcld_gemini_max_token"]))        : '';
        $qcld_gemini_ai_temperature     = isset($_POST["qcld_gemini_ai_temperature"])   ? sanitize_text_field(($_POST["qcld_gemini_ai_temperature"]))   : '';
        $qcld_gemini_ai_top_p           = isset($_POST["qcld_gemini_ai_top_p"])         ? sanitize_text_field(($_POST["qcld_gemini_ai_top_p"]))         : '';
        $qcld_gemini_ai_top_k           = isset($_POST["qcld_gemini_ai_top_k"])         ? sanitize_text_field(($_POST["qcld_gemini_ai_top_k"]))         : '';

        $qcld_ai_settings_open_ai       = isset($_POST["qcld_ai_settings_open_ai"])     ? sanitize_text_field(($_POST["qcld_ai_settings_open_ai"]))     : '';



        update_option('qcld_gemini_api_key', $qcld_gemini_api_key );
        update_option('qcld_gemini_model', $qcld_gemini_model );
        update_option('qcld_gemini_api_version', $qcld_gemini_api_version );
        update_option('qcld_gemini_max_token', $qcld_gemini_max_token );
        update_option('qcld_gemini_ai_temperature', $qcld_gemini_ai_temperature );
        update_option('qcld_gemini_ai_top_p', $qcld_gemini_ai_top_p );
        update_option('qcld_gemini_ai_top_k', $qcld_gemini_ai_top_k );

        update_option('qcld_ai_settings_open_ai', $qcld_ai_settings_open_ai );


        $results = esc_html('Data Updated Successfully', 'seo-help');

        wp_send_json(['status'=> 'success','results'=> $results]);
        wp_die();
    }
}
add_action( 'wp_ajax_gemini_save_settings', 'gemini_save_settings');

add_action( 'wp_ajax_qcld_seo_image_generate', 'qcld_seo_image_generate' );
if ( ! function_exists( 'qcld_seo_image_generate' ) ) {
    function qcld_seo_image_generate () {

        check_ajax_referer( 'seo-help-pro', 'security');

        $qcld_seo_result = array(
            'status' => 'error',
            'msg'    => 'Something went wrong',
        );

        $OPENAI_API_KEY = get_option('qcld_seohelp_api_key');
        
        $qcld_seo_prompt                = isset( $_POST['qcld_seo_prompt'] )                ? sanitize_text_field( $_POST['qcld_seo_prompt'] )              : '';
        $qcld_seo_artist                = isset( $_POST['qcld_seo_artist'] )                ? sanitize_text_field( $_POST['qcld_seo_artist'] )              : 'Painter';
        $qcld_seo_art_style             = isset( $_POST['qcld_seo_art_style'] )             ? sanitize_text_field( $_POST['qcld_seo_art_style'] )           : 'Style';
        $qcld_seo_photography_style     = isset( $_POST['qcld_seo_photography_style'] )     ? sanitize_text_field( $_POST['qcld_seo_photography_style'] )   : 'Photography Style';
        $qcld_seo_lighting              = isset( $_POST['qcld_seo_lighting'] )              ? sanitize_text_field( $_POST['qcld_seo_lighting'] )            : 'Lighting';
        $qcld_seo_subject               = isset( $_POST['qcld_seo_subject'] )               ? sanitize_text_field( $_POST['qcld_seo_subject'] )             : 'Subject';
        $qcld_seo_camera_settings       = isset( $_POST['qcld_seo_camera_settings'] )       ? sanitize_text_field( $_POST['qcld_seo_camera_settings'] )     : 'Camera Settings';
        $qcld_seo_composition           = isset( $_POST['qcld_seo_composition'] )           ? sanitize_text_field( $_POST['qcld_seo_composition'] )         : 'Composition';
        $qcld_seo_resolution            = isset( $_POST['qcld_seo_resolution'] )            ? sanitize_text_field( $_POST['qcld_seo_resolution'] )          : 'Resolution';
        $qcld_seo_color                 = isset( $_POST['qcld_seo_color'] )                 ? sanitize_text_field( $_POST['qcld_seo_color'] )               : 'Color';
        $qcld_seo_special_effects       = isset( $_POST['qcld_seo_special_effects'] )       ? sanitize_text_field( $_POST['qcld_seo_special_effects'] )     : 'Special Effects';
        $qcld_seo_img_size              = isset( $_POST['qcld_seo_img_size'] )              ? sanitize_text_field( $_POST['qcld_seo_img_size'] )            : '512x512';
        $qcld_seo_num_images            = isset( $_POST['qcld_seo_num_images'] )            ? sanitize_text_field( $_POST['qcld_seo_num_images'] )          : 1;
        $qcld_seo_num_images            = isset( $qcld_seo_num_images )                     ? (int) $qcld_seo_num_images                                    : 6;

        if (!empty($qcld_seo_prompt)) {
            // Get the prompt from the form
            $prompt         = $qcld_seo_prompt;
            $img_size       = $qcld_seo_img_size;
            $num_images     = $qcld_seo_num_images;
            // convert num_images to an integer
            $num_images     = (int) $num_images;


            $qcld_ai_settings_open_ai = get_option('qcld_ai_settings_open_ai');

           /* if ( !empty($qcld_ai_settings_open_ai) && $qcld_ai_settings_open_ai == 'gemini' ) {

                $gemini_api_key     = get_option('qcld_gemini_api_key');
                $gemini_model       = get_option('qcld_gemini_model');
                $gemini_version     = get_option('qcld_gemini_api_version');

                $requestBody = json_encode([
                    'model'     => 'models/gemini-pro', // Or the specific model you want to use
                    'prompt'    => [
                        'text'  => 'Generate an image of a cat riding a unicorn.'
                    ]
                ]);

                //$apiUrl = 'https://generativelanguage.googleapis.com/v1beta2/models/gemini-pro:generateContent'; // API endpoint
                $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent'; // API endpoint
                //$url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=AIzaSyDVFAaHZSroH3h2Zo10d_z3PIzEUcTkXQA";
                $accessToken = $gemini_api_key; 

                $ch = curl_init($apiUrl);
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $requestBody );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    //'Authorization: Bearer AIzaSyDwWYV-cGIdDYd-ERgJowq8ogY7tcTCIjY' . $gemini_api_key
                    'Authorization: Bearer AIzaSyDwWYV-cGIdDYd-ERgJowq8ogY7tcTCIjY'
                ]);

                $response = curl_exec($ch);
                curl_close($ch);

                // Handle the response (e.g., decode the JSON and save the generated image)
                $img_result = json_decode($response, true);

                var_dump( $img_result );
                wp_die();



            }else{*/


                $prompt_elements = array(
                    'artist'            => $qcld_seo_artist,
                    'art_style'         => $qcld_seo_art_style,
                    'photography_style' => $qcld_seo_photography_style,
                    'composition'       => $qcld_seo_composition,
                    'resolution'        => $qcld_seo_resolution,
                    'color'             => $qcld_seo_color,
                    'special_effects'   => $qcld_seo_special_effects,
                    'lighting'          => $qcld_seo_lighting,
                    'subject'           => $qcld_seo_subject,
                    'camera_settings'   => $qcld_seo_camera_settings,
                );

                foreach ($prompt_elements as $key => $value) {
                    if ( isset( $_POST[$key] ) && $_POST[$key] != "None") {
                        $prompt = $prompt . ". " . $value . ": " . $_POST[$key];
                    }
                }

                $image_grid = '<div class="qcld_image_grid">';
                for ($i = 0; $i < $num_images; $i++) {

                    $image_grid   .= apply_filters( 'qcld_seo_get_prompt_image_url', $prompt, $img_size );

                }
                $image_grid .= '</div>';

           // }

            $qcld_seo_result['status'] = 'success';
            $qcld_seo_result['html'] = $image_grid;

        }
        
        wp_send_json( $qcld_seo_result );
    }
}




if(!function_exists('qcld_seo_get_prompt_image_url')){
    add_filter('qcld_seo_get_prompt_image_url', 'qcld_seo_get_prompt_image_url', 10, 2);
    function qcld_seo_get_prompt_image_url( $prompt, $img_size ) {

        $OPENAI_API_KEY = get_option('qcld_seohelp_api_key');

        if( isset( $prompt ) && !empty($prompt) ){

            $request_body = [
                "prompt"            => $prompt,
                "model"             => 'dall-e-3',
                "n"                 => 1,
                "size"              => $img_size,
                "response_format"   => "url",
            ];
            $data    = json_encode($request_body);
            $url     = "https://api.openai.com/v1/images/generations";
            //$url     = "https://api.openai.com/v1/images/edits";
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
            $img_result = json_decode( $result );

            $image_grid = '';
            $image_grid .= '<div class="qcld_qcld_seo_image-box_wrap generate_image_download"> ';
            $image_grid .= '<img class="qcld_qcld_seo_image-list" src=' . esc_html( $img_result->data[0]->url ) . '>';

            $image_grid .= '<div class="qcld_seo_download" data-img="' . esc_html( $img_result->data[0]->url ) . '"><img src="'.qcld_linkbait_img_url.'/download.svg"></div>';
            $image_grid .= '</div>';

            return $image_grid;

        }

    }

}


add_action( 'wp_ajax_qcld_seo_image_generate_url_functions', 'qcld_seo_image_generate_url_functions' );
if ( ! function_exists( 'qcld_seo_image_generate_url_functions' ) ) {
    function qcld_seo_image_generate_url_functions () {

        check_ajax_referer( 'seo-help-pro', 'security');

        $qcld_seo_result = array(
            'status' => 'error',
            'msg'    => esc_html('Something went wrong'),
        );

        /* Download and upload the chosen image */
        if (isset($_POST['qcld_seo_openai_images_upload'])) {
            // "pluggable.php" is required for wp_verify_nonce() and other upload related helpers
            if (!function_exists('wp_verify_nonce'))
                require_once(ABSPATH . 'wp-includes/pluggable.php');

            $nonce = $_POST['wpnonce'];
            if (!wp_verify_nonce($nonce, 'qcld_seo_openai_images_security_nonce')) {
                die('Error: Invalid request.');
                exit;
            }
            if(!function_exists('wp_generate_attachment_metadata')){
                include_once( ABSPATH . 'wp-admin/includes/image.php' );
            }
            if(!function_exists('download_url')){
                include_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            if(!function_exists('media_handle_sideload')){
                include_once( ABSPATH . 'wp-admin/includes/media.php' );
            }

            $post_id                            = isset($_REQUEST['post_id']) ? absint($_REQUEST['post_id']): '';
            $imageurl                           = isset($_POST['image_url']) ? sanitize_url( $_POST['image_url'] ) : '';
            $image_user                         = isset($_POST['image_user']) ? sanitize_url( $_POST['image_user'] ) : '';
            $image_src_page                     = isset($_POST['image_src_page']) ? esc_url( $_POST['image_src_page'] ) : '';
            $qcld_seo_openai_images_attribution = 'gpt openai';

            $array              = explode('/', getimagesize($imageurl)['mime']);
            $imagetype          = end($array);
            $uniq_name          = md5($imageurl);
            $filename           = $uniq_name . '.' . $imagetype;

            $uploaddir          = wp_upload_dir();
            $target_file_name   = $uploaddir['path'] . '/' . $filename;

            $contents           = file_get_contents( $imageurl );
            $savefile           = fopen($target_file_name, 'w');
            fwrite($savefile, $contents);
            fclose($savefile);
            unset($imageurl);

            /* add the image title */
            $image_title        = ucwords( $uniq_name );

            /* add the caption */
            $attachment_caption = '';
            if (! isset($qcld_seo_openai_images_attribution['attribution']) | isset($qcld_seo_openai_images_attribution['attribution']) == 'true')
                $attachment_caption = '<a href="' . esc_url( $image_src_page ) . '" target="_blank" rel="noopener">' . esc_attr( $image_user ) . '</a>';

            /* insert the attachment */
            $wp_filetype = wp_check_filetype(basename($target_file_name), null);
            $attachment  = array(
                'guid'              => $uploaddir['url'] . '/' . basename($target_file_name),
                'post_mime_type'    => $wp_filetype['type'],
                'post_title'        => $image_title,
                'post_status'       => 'inherit'
            );

            $attach_id   = wp_insert_attachment($attachment, $target_file_name, $post_id);
            if ($attach_id == 0)
                die('Error: File attachment error');

            $attach_data = wp_generate_attachment_metadata($attach_id, $target_file_name);
            $result      = wp_update_attachment_metadata($attach_id, $attach_data);

            $image_data                 = array();
            $image_data['ID']           = $attach_id;
            $image_data['post_excerpt'] = $attachment_caption;
            wp_update_post($image_data);

            $parsed = wp_get_attachment_image_src( $attach_id, 'medium' )[0];

            if(!empty($parsed)){
                $attach_id = $parsed;
            }

            $qcld_seo_result['status']  = 'success';
            $qcld_seo_result['html']    = esc_html('Image Successfully Added to  Media Library');

          
        }
            
        wp_send_json( $qcld_seo_result );
        exit;

    }

}