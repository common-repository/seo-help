<?php 


if ( ! function_exists( 'qc_open_ai_bulk_content_generator_page' ) ) {
    function qc_open_ai_bulk_content_generator_page() {


    if(isset($_POST['qcld_seo_confirmed_cron'])){
        update_option('_qcld_seo_crojob_bulk_confirm','true');
    }
    $qcld_seo_track_id              = isset($_GET['qcld_seo_track']) && !empty($_GET['qcld_seo_track']) ? sanitize_text_field($_GET['qcld_seo_track']) : false;
    $qcld_seo_bulk_action           = isset($_GET['qcld_seo_action']) && !empty($_GET['qcld_seo_action']) ? sanitize_text_field($_GET['qcld_seo_action']) : false;
    $qcld_seo_track                 = false;
    if($qcld_seo_track_id){
        $qcld_seo_track             = get_post($qcld_seo_track_id);
    }
    $qcld_seo_cron_job_last_time    = get_option('_qcld_seo_crojob_bulk_last_time','');
    $qcld_seo_cron_job_confirm      = get_option('_qcld_seo_crojob_bulk_confirm','');
    $qcld_seo_number_title          = 5;
    $qcld_seo_cron_added            = get_option('_qcld_seo_cron_added','');


    ?>
    <div class="wrap fs-section">
       
        <div id="poststuff">
            

            <div id="qcld_seo-bulk-generator">
                <div class="qcld_seo-row">
                    <div class="qcld_seo-col">
                           
                           
                            <div class="qcld_seo_ai_single_contents">

                                <div class="qcld_form_wrap qcld_tab_container">
                                    <div class="qcld_form_body">
             
                                        <div id="qcld_tab_area">
                                          <div class="qcld_tab_content">
                                            <div class="qcld_form_tab_pan qcld_tab_active">

                                                <?php 

                                                $pro_url        = esc_url("https://www.dna88.com/product/seo-help-pro/");
                                                $data_msg       = '<div class="qcld_seohelp_msg qcld_error">'.esc_html('You have already generated 10 Blog Posts. Please upgrade to '). '<a href="'. $pro_url .'" target="_blank">'. esc_html('SEO Help Pro ').'</a>'. esc_html(' to generate unlimited Blog posts').'</div>'; 
                                                //update_option('update_openai_crawler_alert', 'off' );
                                                if( get_option('update_openai_crawler_alert') == 'on' ){
                                                ?>
                                               
                                                <div class="qcld-seohelp-input_wrap">
                                                    <h3 class="choose_topic_head"><?php esc_html_e('Turbo Content Generator Wizard', 'qcld-seo-help'); ?></h3>
                                                    
                                                    <?php echo $data_msg; ?>
                                                </div>  
                                               <?php }else { ?>

                                               
                                                <div class="qcld-seohelp-input_wrap">
                                                    <h3 class="choose_topic_head"><?php esc_html_e('Turbo Content Generator Wizard', 'qcld-seo-help'); ?></h3>
                                                    <p><?php esc_html_e('Use this Wizard if you have a brand new website and want to start with some SEO friendly contents quickly until the website is ready to go live with actual contents.', 'qcld-seo-help'); ?></p>
                                                    <p><?php esc_html_e('We will use OpenAI to generate SEO friendly contents for your new website.', 'qcld-seo-help'); ?></p>
                                                    <p><?php esc_html_e('Make sure that you have added a ', 'qcld-seo-help'); ?> <a href="<?php echo esc_url('https://platform.openai.com/account/billing/payment-methods'); ?>" target="_blank"><?php esc_html_e('Credit card', 'qcld-seo-help'); ?></a> <?php esc_html_e('to your OpenAI API account.', 'qcld-seo-help'); ?></p>
                                                    
                                                </div>  
                                              
                                                <?php 

                                                    $OPENAI_API_KEY = get_option('qcld_seohelp_api_key');
                                                    $newstring      = substr( $OPENAI_API_KEY, -5 );
                                                    $api_key_val    = ( isset( $OPENAI_API_KEY ) && !empty( $OPENAI_API_KEY ) ) ? '***************************'.$newstring : '';

                                                if( empty( $OPENAI_API_KEY ) ) {
                                                ?>
                                                <div class="qcld-seohelp-input">
                                                    <div class="qcld-seohelp-input-field">
                                                        <label for="qcld_seohelp_api_key" class="choose_topic_head"><?php esc_html_e('API key', 'qcld-seo-help'); ?></label>
                                                        <input type="text" id="qcld_seohelp_api_key" class="form-control" placeholder="Write API key" value="<?php echo $api_key_val; ?>"><br>
                                                    </div>
                                                </div>  
                                                <?php } ?>

                                                <?php } ?>

                                            </div>
                           
                                            <div class="qcld_form_tab_pan"> 
                                                <h3 class="choose_topic_head"><?php esc_html_e('Choose Topic','qcld-seo-help') ?></h3>
                                                <div class="qcld_form_tab_topic_wrap">
                                       
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" checked="checked" value="Stocks" >
                                                        <span><?php esc_html_e('Stocks','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Comics" >
                                                        <span><?php esc_html_e('Comics','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Global News" >
                                                        <span><?php esc_html_e('Global News','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Sports" >
                                                        <span><?php esc_html_e('Sports','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Students Science" >
                                                        <span><?php esc_html_e('Students Science','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Cryptocurrency" >
                                                        <span><?php esc_html_e('Cryptocurrency','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Children Book" >
                                                        <span><?php esc_html_e('Children Book','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Startup" >
                                                        <span><?php esc_html_e('Startup','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Technology" >
                                                        <span><?php esc_html_e('Technology','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="Finance" >
                                                        <span><?php esc_html_e('Finance','qcld-seo-help') ?></span> 
                                                    </label>
                                                    <label class="radio-inline qcld_form_check_label">
                                                        <input id="qcld_seohelp_topic" type="radio" name="qcld_seohelp_topic" value="English" >
                                                        <span><?php esc_html_e('English','qcld-seo-help') ?></span> 
                                                    </label>

                                                </div>

                                            </div>
                                          </div>
                                          <?php if( get_option('update_openai_crawler_alert') !== 'on' ){ ?>
                                          <div class="qcld_form_tab_nav">
                                            <span class="qcld_form_tab_prev qcld_form_tab_hide"><?php esc_html_e('Prev', 'qcld-seo-help'); ?></span>
                                            <span class="qcld_form_tab_next"><?php esc_html_e('Next', 'qcld-seo-help'); ?></span>
                                            <button class="qcld_form_submit qcld_form_tab_hide"><?php esc_html_e('Generate SEO Friendly Contents', 'qcld-seo-help'); ?></button>
                                          </div>
                                            <?php } ?>
                                        </div>
                                                
                                    </div>
                                </div>
                                
                            </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

<?php 

    }
}



if ( ! function_exists( 'qcld_seo_content_generate' ) ) {
    add_action('wp_ajax_nopriv_qcld_seo_content_generate',  'qcld_seo_content_generate' );
    add_action('wp_ajax_qcld_seo_content_generate', 'qcld_seo_content_generate' );
    function qcld_seo_content_generate() { 

        check_ajax_referer( 'seo-help-pro', 'security');

        $api_key    = isset( $_POST['api_key'] ) ? sanitize_text_field(trim($_POST['api_key'])) : '';
        $title      = isset( $_POST['title'] ) ? sanitize_text_field(trim($_POST['title'])) : '';
        $topic      = isset( $_POST['topic'] ) ? sanitize_text_field(trim($_POST['topic'])) : $title;

        set_time_limit(600);

        $result_data    = '';

        if( !get_option('update_openai_crawler') ) update_option('update_openai_crawler', 0 );
        if( !get_option('update_openai_crawler_alert') ) update_option('update_openai_crawler_alert', 'off' );

        if( get_option('update_openai_crawler_alert') == 'on' ){

            $status         = 'success';
            $pro_url        = esc_url("https://www.dna88.com/product/seo-help-pro/");
            $data_msg       = '<div class="qcld_seohelp_msg qcld_error">'.esc_html('You have already generated 10 Blog Posts. Please upgrade to '). '<a href="'. $pro_url .'" target="_blank">'. esc_html('SEO Help Pro ').'</a>'. esc_html(' to generate unlimited Blog posts').'</div>'; 
            $response = array( 'html' => $data_msg, 'status' => $status );
            echo wp_send_json($response);

        }

        if($topic) {

                $meta_desc_prompt = ( isset( $topic ) && !empty( $topic ) ) ? $topic  : $title;

                // for ($x = 0; $x <= 9; $x++) {

                $meta_desc_prompt   = apply_filters( 'qcld_seo_get_prompt_title', $meta_desc_prompt, get_option('update_openai_crawler') );

                $meta_desc_prompt = sprintf( "You are a professional Blog article writer for a new website. Your writing style is friendly amd informative. Write at least 1000-word SEO-optimized article about the broad topic '%s'. The sub topic can be anything relevant to the broad topic. Begin with a catchy, SEO-optimized level 1 heading (#) that captivates the reader. Follow with SEO optimized introductory paragraphs. Then organize the rest of the article into detailed heading tags (##) and lower-level heading tags (###, ####, etc.). Include detailed paragraphs under each heading and subheading that provide in-depth information about the topic. Use bullet points, unordered lists, bold text, underlined text, code blocks etc to enhance readability and engagement.", $meta_desc_prompt );


            /*$OPENAI_API_KEY = !empty($api_key) ? $api_key : get_option('qcld_seohelp_api_key');
            $ai_engines     = "text-davinci-003";
            $max_token      = "3000";
            $temperature    = "0.3";
            $ppenalty       = "0";
            $fpenalty       = "0";*/

            $OPENAI_API_KEY = !empty($api_key) ? $api_key : get_option('qcld_seohelp_api_key');
            $ai_engines     = get_option('qcld_seohelp_ai_engines')     ? get_option('qcld_seohelp_ai_engines') : 'gpt-4o';
            $max_token      = get_option('qcld_seohelp_max_token')      ? get_option('qcld_seohelp_max_token') : '3000';
            $temperature    = get_option('qcld_seohelp_ai_temperature') ? get_option('qcld_seohelp_ai_temperature') : "0.3";
            $ppenalty       = get_option('qcld_seohelp_ai_ppenalty')    ? get_option('qcld_seohelp_ai_ppenalty') : "0";
            $fpenalty       = get_option('qcld_seohelp_ai_fpenalty')    ? get_option('qcld_seohelp_ai_fpenalty') : "0";


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
                                    "text" => $meta_desc_prompt
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

                $result_data .= isset( $returnedData->candidates[0]->content->parts[0]->text ) ? $returnedData->candidates[0]->content->parts[0]->text : '';

                // Extract the title from the first level 1 heading
                preg_match('/^#\s+(.*)$/m', $result_data, $matches);
                $title = isset( $matches[1] ) ? $matches[1] : '';

                // Check if the title contains asterisks at the beginning or end, and remove them if found
                if (strpos($title, '*') !== false) {
                    $title = preg_replace('/^\*+|\*+$/', '', $title);
                }

                $result_data = preg_replace('/^#\s+.*$\n/m', '', $result_data);

                /* string #### to replace h3 tag */
                $result_data = preg_replace( '/^####\s+(.*)$/m',"<h4>$1</h4>", $result_data);

                /* string ### to replace h3 tag */
                $result_data = preg_replace( '/^###\s+(.*)$/m',"<h3>$1</h3>", $result_data);

                /* string ## to replace h2 tag */
                $result_data = preg_replace( '/^##\s+(.*)$/m',"<h2>$1</h2>", $result_data);

                $post_id   = isset( $postId ) ? absint( $postId ): '';

                if( empty( $result_data ) ){
                    $status         = 'success';
                    $data_msg = '<div class="qcld_seohelp_msg qcld_error">'.esc_html('Something Went Wrong. Please check Your Api Key.').'</div>';

                    $response = array( 'html' => $data_msg, 'status' => $status );
                    echo wp_send_json($response);
                    wp_die();
                }

                $title = ( isset( $title ) && !empty( $title ) ) ? $title : $topic;
                // Create post object $topic
                $my_post = array(
                  'post_title'      => wp_strip_all_tags( $title ),
                  'post_content'    => $result_data,
                  'post_status'     => 'publish',
                  'post_author'     => get_current_user_id(),
                  // 'post_category'   => array( 8,39 )
                );

                // Insert the post into the database
                $postId     = wp_insert_post( $my_post );


            }else if($ai_engines == 'gpt-3.5-turbo' || $ai_engines == 'gpt-4' || $ai_engines == 'gpt-4o' || $ai_engines == 'gpt-4o-mini'){
                $gptkeyword = [];
                $ch     = curl_init();
                $url    = 'https://api.openai.com/v1/chat/completions';

                array_push($gptkeyword, array(
                           "role" => "user",
                           "content" =>  $meta_desc_prompt
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

                $results    = json_decode($result);

                $result_data = isset( $results->choices[0]->message->content ) ? trim( $results->choices[0]->message->content ) : '';

                // Extract the title from the first level 1 heading
                preg_match('/^#\s+(.*)$/m', $result_data, $matches);
                $title = isset( $matches[1] ) ? $matches[1] : '';

                // Check if the title contains asterisks at the beginning or end, and remove them if found
                if (strpos($title, '*') !== false) {
                    $title = preg_replace('/^\*+|\*+$/', '', $title);
                }

                $result_data = preg_replace('/^#\s+.*$\n/m', '', $result_data);

                /* string #### to replace h3 tag */
                $result_data = preg_replace( '/^####\s+(.*)$/m',"<h4>$1</h4>", $result_data);

                /* string ### to replace h3 tag */
                $result_data = preg_replace( '/^###\s+(.*)$/m',"<h3>$1</h3>", $result_data);

                /* string ## to replace h2 tag */
                $result_data = preg_replace( '/^##\s+(.*)$/m',"<h2>$1</h2>", $result_data);

                if( empty( $result_data ) ){
                    $status         = 'success';
                    $data_msg = '<div class="qcld_seohelp_msg qcld_error">'.esc_html('Something Went Wrong. Please check Your OpenAI Api Key.').'</div>';

                    $response = array( 'html' => $data_msg, 'status' => $status );
                    echo wp_send_json($response);
                    wp_die();
                }

            }else{

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

                // Extract the title from the first level 1 heading
                preg_match('/^#\s+(.*)$/m', $result_data, $matches);
                $title = isset( $matches[1] ) ? $matches[1] : '';

                // Check if the title contains asterisks at the beginning or end, and remove them if found
                if (strpos($title, '*') !== false) {
                    $title = preg_replace('/^\*+|\*+$/', '', $title);
                }

                $result_data = preg_replace('/^#\s+.*$\n/m', '', $result_data);

                /* string #### to replace h3 tag */
                $result_data = preg_replace( '/^####\s+(.*)$/m',"<h4>$1</h4>", $result_data);

                /* string ### to replace h3 tag */
                $result_data = preg_replace( '/^###\s+(.*)$/m',"<h3>$1</h3>", $result_data);

                /* string ## to replace h2 tag */
                $result_data = preg_replace( '/^##\s+(.*)$/m',"<h2>$1</h2>", $result_data);

                if( empty( $result_data ) ){
                    $status         = 'success';
                    $data_msg = '<div class="qcld_seohelp_msg qcld_error">'.esc_html('Something Went Wrong. Please check Your OpenAI Api Key.').'</div>';

                    $response = array( 'html' => $data_msg, 'status' => $status );
                    echo wp_send_json($response);
                    wp_die();
                }

            }
            



            if ( $qcld_ai_settings_open_ai !== 'gemini' ) {

                // Get the prompt from the form
                $prompt         = $title;
                $img_size       = '1024x1024';


                // Send the request to OpenAI
               
                $request_body = [
                    "prompt"            => $prompt,
                    "model"             => 'dall-e-3',
                    "n"                 => 1,
                    "size"              => '1024x1024',
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
                $img_result = json_decode( $result );

                // Create post object
                $my_post = array(
                  'post_title'      => wp_strip_all_tags( $title ),
                  'post_content'    => $result_data,
                  'post_status'     => 'publish',
                  'post_author'     => get_current_user_id(),
                  // 'post_category'   => array( 8,39 )
                );


                // Insert the post into the database
                $postId = wp_insert_post( $my_post );

                $post_id                            = isset( $postId ) ? absint( $postId ): '';
                $imageurl                           = isset( $img_result->data[0]->url ) ? sanitize_url( $img_result->data[0]->url ) : '';
                $image_user                         = isset($_POST['image_user']) ? sanitize_url( $_POST['image_user'] ) : '';
                $image_src_page                     = isset($_POST['image_src_page']) ? esc_url( $_POST['image_src_page'] ) : '';
                $qcld_seo_openai_images_attribution = 'gpt openai';

                if( !empty( $imageurl ) ){

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

                    $size           = 'large';
                    $attachment_id  = $attach_id;

                    set_post_thumbnail( $post_id, $attach_id );
                }


            }


        }

        add_post_meta( $post_id, 'qcld_openai_post', 1 );
        update_option('update_openai_crawler', get_option('update_openai_crawler') + 1 );
        
        $status = 'pending';
        $data_msg   = '<div class="qcld_seohelp_msg">'. get_option('update_openai_crawler') . esc_html(' Blog Posts Created. Working on more. Please stand by.').'</div>';



        if( get_option('update_openai_crawler') > 9 ){

            update_option('update_openai_crawler_alert', 'on');

            $status         = 'success';
            $data_msg       = '<div class="qcld_seohelp_msg">'.esc_html('We have generated 10 Blog Posts!').'</div>';

        }
        
        $response = array( 'html' => $data_msg, 'status' => $status );
        echo wp_send_json($response);

        // echo wp_send_json($data);
        wp_die();
    }
}



if(!function_exists('qcld_seo_get_prompt_title')){
    add_filter('qcld_seo_get_prompt_title', 'qcld_seo_get_prompt_title', 10, 2);
    function qcld_seo_get_prompt_title( $title, $length ) {

        if( isset( $title ) && $title == 'Stocks' ){

            $qcld_title_array_value = array( 
                                    "Understanding stock markets", 
                                    "Fundamental analysis of Stocks", 
                                    "Common stocks vs. preferred stocks", 
                                    "Buy and hold Stocks", 
                                    "Diversification of Stocks", 
                                    "Exchange-Traded Funds (ETFs)", 
                                    "Historical stock market crashes", 
                                    "Securities and Exchange Commission (SEC)", 
                                    "Individual Retirement Accounts (IRAs)", 
                                    "International stock exchanges", 
                                    "Investor psychology", 
                                    "Financial news outlets for stocks",
                                    "ESG criteria (Environmental, Social, Governance) for stocks", 
                                    "Stock options", 
                                    "Online brokerage platforms" 
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Comics' ){

            $qcld_title_array_value = array( 
                                    "Comic Book History", 
                                    "Comic Book Genres", 
                                    "Iconic superheroes (e.g., Superman, Spider-Man, Batman)", 
                                    "Antiheroes (e.g., Wolverine, Deadpool)", 
                                    "Graphic Novels", 
                                    "Pioneers in the Comic industry", 
                                    "Marvel Comics", 
                                    "DC Comics", 
                                    "Comic Book Crossovers", 
                                    "Comic Book Adaptations", 
                                    "Fandom and conventions for comic", 
                                    "Diversity in Comics",
                                    "Japanese manga", 
                                    "Stock options", 
                                    "Comic book conventions" 
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Global News' ){

            $qcld_title_array_value = array( 
                                    "Geopolitical Events", 
                                    "International conflicts and crises", 
                                    "Diplomatic relations between countries", 
                                    "Peace negotiations and treaties", 
                                    "Regions of ongoing conflict (e.g., Middle East, Ukraine)", 
                                    "Political instability (e.g., Venezuela, Hong Kong)", 
                                    "International Organizations", 
                                    "United Nations (UN)", 
                                    "World Health Organization (WHO)", 
                                    "International Monetary Fund (IMF)", 
                                    "Global stock markets", 
                                    "Trade agreements and disputes",
                                    "Currency exchange rates", 
                                    "Climate and Environmental Issues", 
                                    "Climate change and global warming",
                                    "Natural disasters and their impact",
                                    "Environmental policy and agreements",
                                    "Elections and political transitions",
                                    "International leaders and their policies",
                                    "Political movements and activism",
                                    "Human Rights and Social Issues",
                                    "Human rights violations",
                                    "Social justice movements",
                                    "Refugee and migration crises",
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Sports' ){

            $qcld_title_array_value = array( 
                                    "Sports by Type", 
                                    "Olympic Games", 
                                    "Major sporting events (e.g., Super Bowl, World Cup)", 
                                    "Sports and Health", 
                                    "Sports injuries and prevention", 
                                    "Sports Training and Techniques", 
                                    "Sports History", 
                                    "Sports Culture", 
                                    "Sports and Media", 
                                    "Sports Business and Economics", 
                                    "Sports organizations (e.g., FIFA, IOC)", 
                                    "Youth sports leagues and programs",
                                    "Sports and Social Issues", 
                                    "Sports Technology", 
                                    "Competitive video gaming",
                                    "Sports tourism destinations",
                                    "Sports and Politics"
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Students Science' ){

            $qcld_title_array_value = array( 
                                    "Elementary school science", 
                                    "College and university science", 
                                    "Science, Technology, Engineering, and Mathematics (STEM) programs", 
                                    "Ideas for science fair projects", 
                                    "Involving students in citizen science projects", 
                                    "Environmental monitoring and data collection", 
                                    "School science clubs", 
                                    "Extracurricular science organizations", 
                                    "National and international science competitions", 
                                    "Simple and fun science experiments", 
                                    "Hands-on science activities for students", 
                                    "Science demonstrations and shows",
                                    "Science outreach programs", 
                                    "Engaging students in science communication", 
                                    "Internships and research opportunities", 
                                    "Tools for data analysis and visualization", 
                                    "Online learning in science", 
                                    "Student involvement in environmental projects", 
                                    "Student interest in space exploration",
                                    "Astronomy clubs and activities" 
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Cryptocurrency' ){

            $qcld_title_array_value = array( 
                                    "Blockchain Technology", 
                                    "Popular Cryptocurrencies", 
                                    "Cryptocurrency Exchanges", 
                                    "Cryptocurrency Wallets", 
                                    "Cryptocurrency Investment", 
                                    "Cryptocurrency ICO", 
                                    "Blockchain Development", 
                                    "Legal status of cryptocurrencies in different countries", 
                                    "Cryptocurrency and the Economy", 
                                    "Cryptocurrency theft and hacking incidents", 
                                    "Privacy Coins and Anonymity", 
                                    "Blockchain Scalability",
                                    "Cryptocurrency Use Cases", 
                                    "Crypto Mining"
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Children Book' ){

            $qcld_title_array_value = array( 
                                    "Children Picture Books", 
                                    "Children Book Genres", 
                                    "Early Reader Books", 
                                    "Coming-of-age novels", 
                                    "Young Adult (YA) Novels", 
                                    "Diverse and Inclusive Children's Books", 
                                    "Classic Children's Literature", 
                                    "Non-Fiction Children's Books", 
                                    "Children Book Illustrators and Artists", 
                                    "Award-Winning Children's Books", 
                                    "Interactive and Pop-up Books", 
                                    "Encouraging children to read",
                                    "Children's books adapted into movies or TV shows", 
                                    "Using children's books in the classroom",
                                    "Interactive eBooks for kids",
                                    "Digital storytelling and augmented reality books"
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Startup' ){

            $qcld_title_array_value = array( 
                                    "Startup Business Idea Generation", 
                                    "Startup Market Research and Validation", 
                                    "Startup  Business Models", 
                                    "Startup Funding", 
                                    "Startup Business Planning", 
                                    "Writing a Startup  business plan", 
                                    "Startup Legal and Regulatory Considerations", 
                                    "Startup Product Development", 
                                    "Startup Marketing and Branding", 
                                    "Startup Sales and Customer Acquisition", 
                                    "Startup Scaling and Growth", 
                                    "Startup Culture and Team Building",
                                    "Startup Failure and Pivot", 
                                    "Social Entrepreneurship and Impact Startups",
                                    "Startup Ecosystems and Hubs",
                                    "Startup Exit Strategies"
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Technology' ){


            $qcld_title_array_value = array( 
                                    "Emerging Technologies", 
                                    "Cybersecurity", 
                                    "Information Technology", 
                                    "Software Development", 
                                    "Hardware and Devices", 
                                    "Artificial Intelligence and Machine Learning", 
                                    "Robotics and Automation", 
                                    "Virtual Reality (VR) and Augmented Reality (AR)", 
                                    "Big Data and Data Analytics", 
                                    "E-commerce and Online Business", 
                                    "Internet and Web Technologies", 
                                    "Green Technology and Sustainability",
                                    "Space Technology", 
                                    "Healthcare Technology",
                                    "Educational Technology (EdTech)",
                                    "Tech Policy and Ethics"
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'Finance' ){


            $qcld_title_array_value = array( 
                                    "Personal Finance", 
                                    "Investments", 
                                    "Financial Markets", 
                                    "Banking and Financial Services", 
                                    "Corporate Finance", 
                                    "Financial Planning and Advising", 
                                    "Financial Risk Management", 
                                    "Financial Literacy", 
                                    "Financial Economic Concepts", 
                                    "Financial Regulation and Compliance", 
                                    "Financial Investment Strategies", 
                                    "Real Estate and Property Finance",
                                    "International Finance", 
                                    "Financial Technology (Fintech)",
                                    "Sustainable Finance",
                                    "Investor psychology and biases"
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }else if( isset( $title ) && $title == 'English' ){

            $qcld_title_array_value = array( 
                                    "English Language Skills", 
                                    "English Grammar and Syntax", 
                                    "English Language Proficiency Levels", 
                                    "English Language Courses", 
                                    "English Language Teaching", 
                                    "English Accent Reduction and Pronunciation", 
                                    "English Idioms and Expressions", 
                                    "English Literature", 
                                    "Business English", 
                                    "Cultural immersion and language acquisition", 
                                    "English Language Learning Strategies", 
                                    "Multilingualism",
                                    "Language Assessment and Proficiency Testing", 
                                    "English Learning Resources",
                                    "English Language Challenges"
                                );

            
            $title = ( isset( $qcld_title_array_value[$length] ) && !empty( $qcld_title_array_value[$length] ) ) ? $qcld_title_array_value[$length] : $title;

            return $title;

        }

        return $title;

    }

}