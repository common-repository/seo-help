<?php 
/*
* Plugin Name: SEO Help
* Plugin URI: https://wordpress.org/plugins/seo-help
* Description: SEO Help provides helpful hints to generate Link Bait titles. Increase your Click Through Rate or CTR. Write better contents with SEO tips while you are writing
* Version: 6.0.5
* Author: QuantumCloud
* Author URI: https://www.quantumcloud.com/
* Requires at least: 4.6
* Tested up to: 6.6.2
* Text Domain: seo-help
* Domain Path: /lang/
* License: GPL2
*/

defined('ABSPATH') or die("No direct script access!");

if ( ! defined( 'qcld_linkbait_url' ) )
  define('qcld_linkbait_url', plugin_dir_url(__FILE__));

if ( ! defined( 'qcld_linkbait_img_url' ) )
  define('qcld_linkbait_img_url', qcld_linkbait_url . "/assets/images");

if ( ! defined( 'qcld_linkbait_assets_url' ) )
  define('qcld_linkbait_assets_url', qcld_linkbait_url . "/assets");

if ( ! defined( 'qcld_Linkbait_dir1' ) )
  define('qcld_Linkbait_dir1', dirname(__FILE__));

if ( ! defined( 'qcld_Linkbait_inc_dir1' ) )
  define('qcld_Linkbait_inc_dir1', qcld_Linkbait_dir1 . "/inc");

if ( ! defined( 'QCLD_SEO_ALLOW_HTTPS' ) ) {
    define( 'QCLD_SEO_ALLOW_HTTPS', true );
}

if ( ! defined( 'qcld_seo_help_NAME' ) ) {
    define( 'qcld_seo_help_NAME', 'Seo Help RSS Feeds' );
}


/**
 * Do not forget about translating your plugin
 */
if ( ! function_exists( 'qcld_seo_help_scan_result_languages' ) ) {
  function qcld_seo_help_scan_result_languages(){
    load_plugin_textdomain( 'seo-help', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
  }
}
add_action('init', 'qcld_seo_help_scan_result_languages');

require_once( 'qc-project-linkbait-assets.php' );
require_once( 'inc/qc-project-linkbait-ajax.php' );
require_once( 'inc/qc-project-linkbait-ajax-seo.php' );
require_once( 'inc/qc-project-linkbait-helper.php' );
require_once( 'inc/qc-project-broken-link-checker.php' );
require_once( 'inc/qc-project-linkbait-scan.php' );
require_once( 'inc/qc-project-linkbait-table.php' );
require_once( 'inc/scan-list-scan-table-data.php' );
require_once( 'inc/scan-list-scan-crawler.php' );
require_once( 'inc/scan-list-scan-table-header-results.php' );
require_once( 'inc/scan-list-scan-table-body-results.php' );
require_once( 'inc/scan-list-scan-table-result-mail.php' );
require_once( 'inc/post-summarizer.php' );
require_once( 'inc/qc-content-generator-page.php' );

include_once('class-qcld-free-plugin-upgrade-notice.php');

require_once( 'rss/qcld-rss.php' );
require_once( 'rss/qcld-rss-class.php' );
require_once( 'rss/qcld-rss-functions.php' );
require_once( 'rss/qcld-rss-cron-job.php' );

// require_once( 'qc-promo-page/promo-page.php' );
require_once('qc-support-promo-page/class-qc-support-promo-page.php');



if ( ! function_exists( 'seo_help_menu_setup' ) ) {
  function seo_help_menu_setup(){

    add_menu_page( 
      esc_html__('SEO Help','seo-help'), 
      esc_html__('SEO Help','seo-help'), 
      'manage_options', 'qcld-seo-help', 
      'qcpromo_seo_help_promo_page_callaback', 
      'dashicons-editor-ol' 
    );

    add_submenu_page( 
      'qcld-seo-help', 
      esc_html__('General and AI Settings','seo-help'), 
      esc_html__('General and AI Settings','seo-help'), 
      'manage_options', 'qcld-seo-help', 
      'qcpromo_seo_help_promo_page_callaback' 
    );

    add_submenu_page( 'qcld-seo-help', 
      esc_html__('Broken Link Scanner','seo-help'), 
      esc_html__('Broken Link Scanner','seo-help'), 
      'manage_options', 
      'qcld-seo-help-scan', 
      'cltd_seo_help_scan_result_view' 
    );
    
    add_submenu_page( 'qcld-seo-help', 
      esc_html__('Broken Link Scan Settings','seo-help'), 
      esc_html__('Broken Link Scan Settings','seo-help'), 
      'manage_options', 
      'qcld-seo-help-new-scan', 
      'qcld_seo_help_scan_page_callback_func' 
    );

    add_submenu_page(
      'qcld-seo-help',
      esc_html__('AI Content Writer', 'seo-help'),
      esc_html__('AI Content Writer', 'seo-help'),
      'manage_options',
      'qc_open_ai_single_content',
      'qc_open_ai_single_content_page'
    );

    add_submenu_page(
      'qcld-seo-help',
      esc_html('AI Turbo Content Generator', 'seo-help'),
      esc_html('AI Turbo Content Generator', 'seo-help'),
      'manage_options',
      'qcld_seo_bulk_content_generate',
      'qc_open_ai_bulk_content_generator_page'
    );

    add_submenu_page(
      'qcld-seo-help',
      esc_html__('AI Image Generator', 'seo-help'),
      esc_html__('AI Image Generator', 'seo-help'),
      'manage_options',
      'qcld_seo_img_generator',
      'qc_open_ai_img_generator_page'
    );

    add_submenu_page( 
      'qcld-seo-help', 
      esc_html__('Automatic Posts Summarizer', 'seo-help'), 
      esc_html__('Automatic Posts Summarizer', 'seo-help'), 
      'manage_options', 
      'qcld-seo-summarizer', 
      'qcld_seo_summarizer_page_callback_func' 
    );


    add_submenu_page( 
      'qcld-seo-help', 
      esc_html('Import and Rewrite from RSS feed', 'seo-help'), 
      esc_html('Import and Rewrite from RSS feed', 'seo-help'), 
      'manage_options', 
      'edit.php?post_type=qcld_rss_imports' 
    );

    add_submenu_page( 
      'qcld-seo-help', 
      esc_html__('Support','seo-help'), 
      esc_html__('Support','seo-help'), 
      'manage_options', 
      'qcld-seo-help-supports', 
      'qcld_seo_promo_support_page_callback_func' 
    );

    add_submenu_page( 
      'qcld-seo-help', 
      esc_html__('Help','seo-help'), 
      esc_html__('Help','seo-help'), 
      'manage_options', 
      'qcld-seo-help-section', 
      'qcpromo_seo_help_section_page_callaback' 
    );

  }
}

add_action('admin_menu','seo_help_menu_setup');



$qcld_disable_floating_icon = get_option('qcld_disable_floating_icon');
if($qcld_disable_floating_icon !=='on'){
add_action('admin_footer', 'qcld_seohelp_content_creation_html');
}
if ( ! function_exists( 'qcld_seohelp_content_creation_html' ) ) {
  function qcld_seohelp_content_creation_html(){

      $screen = get_current_screen();
      //var_dump( $screen->post_type );
      //wp_die();
      //if( isset( $screen->post_type ) && ( $screen->post_type == 'page' || $screen->post_type == 'post' ) ){
      ?>
      <div class="qcld_seohelp_content_wrap">
          <label for="linkbait-post-class"><?php echo esc_html__( "AI", 'seo-help' ); ?></label>
          
          <div class="qcld_seohelp_content_wrap_inn">
          <img src="<?php echo qcld_linkbait_img_url.'/ai.png' ?>" alt="loading">
          <input type="button" class="button" id="content_generator" value="Generate">
          </div>
      </div>
      <div class="qcld-seohelp-outer">
    <div class="qcld-seohelp">
      
        <!-- Sidebar Right -->
        <div class="modal fade right" id="content_Generator_modal" tabindex="-1" role="dialog" data-bs-backdrop="static" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="keywords_resultLabel"><?php echo esc_html__('Content Generator', 'seo-help' ); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-list">  
                          <?php 


                            $qcld_seohelp_api_key = get_option('qcld_seohelp_api_key');
                            $qcld_gemini_api_key  = get_option('qcld_gemini_api_key');
                            if( empty($qcld_seohelp_api_key) && empty($qcld_gemini_api_key) ){ 

                          ?>
                          <p style="color:red;"><b><?php esc_html_e('Please add API key from'); ?> <a href="<?php echo esc_url('https://devel3/ilist-pro/wp-admin/admin.php?page=qcld-seo-help#qcld_seo_tab-2'); ?>" target="_blank"><?php esc_html_e('Settings.'); ?></a> <?php esc_html_e('Otherwise, AI will not work.', 'seo-help'); ?></b></p>
                          <?php } ?>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="article-tab" data-bs-toggle="tab" data-bs-target="#article-tab-pane" type="button" role="tab" aria-controls="article-tab-pane" aria-selected="true"><?php esc_html_e('Generate New Contents', 'seo-help'); ?></button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-tab-pane" type="button" role="tab" aria-controls="content-tab-pane" aria-selected="false"><?php esc_html_e('Rewrite  Article', 'seo-help'); ?></button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link " id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false"><?php esc_html_e('Keyword Suggestion', 'seo-help'); ?></button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link" id="linkbait-tab" data-bs-toggle="tab" data-bs-target="#linkbait-tab-pane" type="button" role="tab" aria-controls="linkbait-tab-pane" aria-selected="false"><?php esc_html_e('LinkBait Generator', 'seo-help'); ?></button>
                          </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="linkbait-tab-pane" role="tabpanel" aria-labelledby="linkbait-tab" tabindex="0">

                              <div class="qcld-seohelp">
                                  
                                  <h5><?php esc_html_e( "LinkBait Title Generator", 'seo-help' ); ?></h5>
                                  <hr/>
                                  <div class="sm_shortcode_list">

                                    <div class="linkbait_single_field">
                                      <label><?php esc_html_e( "Subject", 'seo-help' ); ?></label>
                                      <input type="text" value="<?php echo esc_html(isset($title) ? $title : ''); ?>" id="linkbait_subject" />
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
                                              <input id="linkbait_google" type="radio" name="linkbait_filter" value="google">
                                              <label for="linkbait_google"><span><span></span></span><?php esc_html_e( "Google Suggestion", 'seo-help' ); ?></label>
                                          </div>
                                          <div>
                                              <input id="linkbait_openai" type="radio" name="linkbait_filter" value="openai">
                                              <label for="linkbait_openai"><span><span></span></span><?php esc_html_e( "OpenAI", 'seo-help' ); ?></label>
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
                                        if( isset($title) && $title!=''){ //check if title exists
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
                            <div class="tab-pane fade" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                                <div class="qcld-seohelp">
                                  <div class="qcld-seohelp-input">
                                    <div class="qcld-seohelp-input-field">
                                      <label for="qcld_keyword_suggestion" class="form-label"><?php echo esc_html__('Title and Keyword Suggestion', 'seo-help' ); ?></label><br>
                                      <input type="text" id="qcld_keyword_suggestion" class="form-control" data-press="qcld_keyword_suggestion_btn"><br>
                                    </div>
                                    <div class="qcld-seohelp-input-field">
                                    <label for="qcld-seo-language" class="form-label"><?php echo esc_html__('Country // Language', 'seo-help' ); ?></label><br>
                                    <select value="" name="country" id="qcld-seo-language" class="form-select">
                                            <optgroup label="North america">
                                                <option value="us-en"><?php echo esc_html__('United States', 'seo-help' ); ?></option>
                                                <option value="ca-en"><?php echo esc_html__('Canada', 'seo-help' ); ?></option>
                                            </optgroup>
                                            <optgroup label="Europe">
                                                <option value="uk-en"><?php echo esc_html__('United Kingdom', 'seo-help' ); ?></option>
                                                <option value="nl-nl"><?php echo esc_html__('Netherlands', 'seo-help' ); ?></option>
                                                <option value="be-fr"><?php echo esc_html__('Belgium (FR)', 'seo-help' ); ?></option>
                                                <option value="be-nl"><?php echo esc_html__('Belgium (NL)', 'seo-help' ); ?></option>
                                                <option value="de-de"><?php echo esc_html__('Germany', 'seo-help' ); ?></option>
                                                <option value="fr-fr"><?php echo esc_html__('France', 'seo-help' ); ?></option>
                                                <option value="dk-dk"><?php echo esc_html__('Denmark', 'seo-help' ); ?></option>
                                                <option value="ie-ie"><?php echo esc_html__('Ireland', 'seo-help' ); ?></option>
                                                <option value="it-it"><?php echo esc_html__('Italy', 'seo-help' ); ?></option>   
                                                <option value="es-es"><?php echo esc_html__('Spain', 'seo-help' ); ?></option>
                                                <option value="pt-pt"><?php echo esc_html__('Portugal', 'seo-help' ); ?></option>
                                            </optgroup>
                                            <optgroup label="Other">
                                                <option value="au-en"><?php echo esc_html__('Australia', 'seo-help' ); ?></option>
                                                <option value="nz-en"><?php echo esc_html__('New Zealand (EN)', 'seo-help' ); ?></option>
                                            </optgroup>
                                    </select></div>
                                </div>
                                  <button id="qcld_keyword_suggestion_btn" class="btn btn-info"><?php echo esc_html__('Search', 'seo-help' ); ?></button>
                            
                                  <hr/>
                                  <div class="linkbait_single_field"> 
                                      <h5><?php _e( "Google Suggested Keywords", 'qcld-seo-help' ); ?></h5>
                                      <div id="linkbait_keyword_data">
                                      </div>
                                      <div class="accordion py-3" id="linkbait_outline_data">
                                      </div>
                                  </div>
                              </div>


                            </div>
                            <div class="tab-pane fade show active" id="article-tab-pane" role="tabpanel" aria-labelledby="article-tab" tabindex="0">
                                <div class="qcld-seohelp">
                                    <div class="qcld-seohelp-input">
                                        <div class="qcld-seohelp-input-field">
                                            <label for="qcld_article_keyword_suggestion" class="form-label"><?php esc_html_e('Prompt', 'seo-help'); ?></label><br>
                                            <input type="text" id="qcld_article_keyword_suggestion_mf" class="form-control" data-press="qcld_article_keyword_suggestion" placeholder="<?php esc_html_e( "Write me a long article on how to make money online", "seo-help" ); ?>"><br>
                                            <p><?php esc_html_e( "Ex: Write me a long article on how to make money online", "seo-help" ); ?></p>
                                        </div>
                                       
                                    </div>
                                    <!-- <div class="qcld-seohelp-input">
                                      <div class="qcld_seo_pro_feature"><?php esc_html_e( 'Additional Pro Features', 'seo-help' ); ?></div>
                                    </div> -->
                                    <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                                        <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">
                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_number_of_heading"><?php esc_html_e( "How many headings?", "seo-help" ); ?> </label>
                                                <input type="number" placeholder="e.g. 5" id="qcld_article_number_of_heading" class="qcld_article_number_of_heading" name="qcld_article_number_of_heading" value="">
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_tag"><?php esc_html_e( "Heading Tag", "seo-help" ); ?> </label>
                                                <select name="qcld_article_heading_tag" id="qcld_article_heading_tag">
                                                    <option value="h1"><?php esc_html_e( "h1", "seo-help" ); ?></option>
                                                    <option value="h2"><?php esc_html_e( "h2", "seo-help" ); ?></option>
                                                    <option value="h3"><?php esc_html_e( "h3", "seo-help" ); ?></option>
                                                    <option value="h4"><?php esc_html_e( "h4", "seo-help" ); ?></option>
                                                    <option value="h5"><?php esc_html_e( "h5", "seo-help" ); ?></option>
                                                    <option value="h6"><?php esc_html_e( "h6", "seo-help" ); ?></option>
                                                </select>
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_style"><?php esc_html_e( "Writing Style", "seo-help" ); ?> </label>
                                                <select name="qcld_article_heading_style" id="qcld_article_heading_style">
                                                    <option value="infor"><?php esc_html_e( "Informative", "seo-help" ); ?></option>
                                                    <option value="analy"><?php esc_html_e( "Analytical", "seo-help" ); ?></option>
                                                    <option value="argum"><?php esc_html_e( "Argumentative", "seo-help" ); ?></option>
                                                    <option value="creat"><?php esc_html_e( "Creative", "seo-help" ); ?></option>
                                                    <option value="criti"><?php esc_html_e( "Critical", "seo-help" ); ?></option>
                                                    <option value="descr"><?php esc_html_e( "Descriptive", "seo-help" ); ?></option>
                                                    <option value="evalu"><?php esc_html_e( "Evaluative", "seo-help" ); ?></option>
                                                    <option value="expos"><?php esc_html_e( "Expository", "seo-help" ); ?></option>
                                                    <option value="journ"><?php esc_html_e( "Journalistic", "seo-help" ); ?></option>
                                                    <option value="narra"><?php esc_html_e( "Narrative", "seo-help" ); ?></option>
                                                    <option value="persu"><?php esc_html_e( "Persuasive", "seo-help" ); ?></option>
                                                    <option value="refle"><?php esc_html_e( "Reflective", "seo-help" ); ?></option>
                                                    <option value="simpl"><?php esc_html_e( "Simple", "seo-help" ); ?></option>
                                                    <option value="techn"><?php esc_html_e( "Technical", "seo-help" ); ?></option>
                                                    <option value="repor"><?php esc_html_e( "Report", "seo-help" ); ?></option>
                                                    <option value="resea"><?php esc_html_e( "Research", "seo-help" ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                                        <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_tone"><?php esc_html_e( "Writing Tone", "seo-help" ); ?> </label>
                                                <select name="qcld_article_heading_tone" id="qcld_article_heading_tone">
                                                    <option value="formal"><?php esc_html_e( "Formal", "seo-help" ); ?></option>
                                                    <option value="asser"><?php esc_html_e( "Assertive", "seo-help" ); ?></option>
                                                    <option value="cheer"><?php esc_html_e( "Cheerful", "seo-help" ); ?></option>
                                                    <option value="humor"><?php esc_html_e( "Humorous", "seo-help" ); ?></option>
                                                    <option value="informal"><?php esc_html_e( "Informal", "seo-help" ); ?></option>
                                                    <option value="inspi"><?php esc_html_e( "Inspirational", "seo-help" ); ?></option>
                                                    <option value="neutr"><?php esc_html_e( "Neutral", "seo-help" ); ?></option>
                                                    <option value="profe"><?php esc_html_e( "Professional", "seo-help" ); ?></option>
                                                    <option value="sarca"><?php esc_html_e( "Sarcastic", "seo-help" ); ?></option>
                                                    <option value="skept"><?php esc_html_e( "Skeptical", "seo-help" ); ?></option>
                                                    <option value="curio"><?php esc_html_e( "Curious", "seo-help" ); ?></option>
                                                    <option value="disap"><?php esc_html_e( "Disappointed", "seo-help" ); ?></option>
                                                    <option value="encou"><?php esc_html_e( "Encouraging", "seo-help" ); ?></option>
                                                    <option value="optim"><?php esc_html_e( "Optimistic", "seo-help" ); ?></option>
                                                    <option value="surpr"><?php esc_html_e( "Surprised", "seo-help" ); ?></option>
                                                    <option value="worry"><?php esc_html_e( "Worried", "seo-help" ); ?></option>

                                    
                                                </select>
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_img_size" ><?php esc_html_e('Image Size', 'seo-help'); ?> </label>
                                                <select name="qcld_article_img_size" id="qcld_article_img_size">
                                                    <!-- <option value="256x256"><?php esc_html_e( "256x256", "seo-help" ); ?> </option>
                                                    <option value="512x512"><?php esc_html_e( "512x512", "seo-help" ); ?> </option> -->
                                                  <option value="1024x1024"><?php esc_html_e( "1024x1024", "seo-help" ); ?> </option>
                                                  <option value="1792x1024"><?php esc_html_e('1792x1024', 'qcld-seo-help'); ?></option>
                                                  <option value="1024x1792"><?php esc_html_e('1024x1792', 'qcld-seo-help'); ?></option>
                                                </select>
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_language"><?php esc_html_e( "Language", "seo-help" ); ?> </label>
                                                <select name="qcld_article_language" id="qcld_article_language">
                                                    <option value="en"><?php esc_html_e( "English", "seo-help" ); ?> </option>
                                                    <option value="ar"><?php esc_html_e( "Arabic", "seo-help" ); ?> </option>
                                                    <option value="bg"><?php esc_html_e( "Bulgarian", "seo-help" ); ?> </option>
                                                    <option value="zh"><?php esc_html_e( "Chinese", "seo-help" ); ?> </option>
                                                    <option value="cs"><?php esc_html_e( "Czech", "seo-help" ); ?> </option>
                                                    <option value="nl"><?php esc_html_e( "Dutch", "seo-help" ); ?> </option>
                                                    <option value="fr"> <?php esc_html_e( "French", "seo-help" ); ?> </option>
                                                    <option value="de"> <?php esc_html_e( "German", "seo-help" ); ?> </option>
                                                    <option value="el"> <?php esc_html_e( "Greek", "seo-help" ); ?> </option>
                                                    <option value="hi"> <?php esc_html_e( "Hindi", "seo-help" ); ?> </option>
                                                    <option value="hu"> <?php esc_html_e( "Hungarian", "seo-help" ); ?> </option>
                                                    <option value="id"> <?php esc_html_e( "Indonesian", "seo-help" ); ?> </option>
                                                    <option value="it"> <?php esc_html_e( "Italian", "seo-help" ); ?> </option>
                                                    <option value="ja"> <?php esc_html_e( "Japanese", "seo-help" ); ?> </option>
                                                    <option value="ko"> <?php esc_html_e( "Korean", "seo-help" ); ?> </option>
                                                    <option value="pl"> <?php esc_html_e( "Polish", "seo-help" ); ?> </option>
                                                    <option value="pt"> <?php esc_html_e( "Portuguese", "seo-help" ); ?> </option>
                                                    <option value="ro"> <?php esc_html_e( "Romanian", "seo-help" ); ?> </option>
                                                    <option value="ru"> <?php esc_html_e( "Russian", "seo-help" ); ?> </option>
                                                    <option value="es"> <?php esc_html_e( "Spanish", "seo-help" ); ?> </option>
                                                    <option value="sv"> <?php esc_html_e( "Swedish", "seo-help" ); ?> </option>
                                                    <option value="tr"> <?php esc_html_e( "Turkish", "seo-help" ); ?> </option>
                                                    <option value="uk"> <?php esc_html_e( "Ukranian", "seo-help" ); ?> </option>
                                                </select>
                                            </div>
                                        </div>        
                                    </div>        
                                    <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                                        <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_label_anchor_text"><?php esc_html_e( "Anchor Text", "seo-help" ); ?> </label>
                                                <input type="text" id="qcld_article_label_anchor_text" placeholder="e.g. battery life" class="qcld_article_label_anchor_text" name="qcld_article_label_anchor_text" >
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_target_url"><?php esc_html_e( "Target URL", "seo-help" ); ?> </label>
                                                <input type="url" id="qcld_article_target_url" placeholder="https://..." class="qcld_article_target_url" name="qcld_article_target_url">
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_target_label_cta"><?php esc_html_e( "Add Call-to-Action", "seo-help" ); ?> </label>
                                                <input type="url" id="qcld_article_target_label_cta" placeholder="https://..." class="qcld_article_target_label_cta" name="qcld_article_target_label_cta">
                                            </div>


                                        </div>
                                    </div>
                                    <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                                        <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">


                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_cta_pos"><?php esc_html_e( "Call-to-Action Position", "seo-help" ); ?> </label>
                                                <select name="qcld_article_cta_pos" id="qcld_article_cta_pos">
                                                    <option value="beg"><?php esc_html_e( "Beginning", "seo-help" ); ?></option>
                                                    <option value="end"><?php esc_html_e( "End", "seo-help" ); ?></option>
                                                </select>
                                                <p><i><?php esc_html_e( "Use Call-to-Action Position", "seo-help" ); ?></i></p>
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_label_keywords"><?php esc_html_e( "Add Keywords", "seo-help" ); ?> </label>
                                                <input type="text" id="qcld_article_label_keywords" placeholder="Write Keywords..." class="qcld_article_label_keywords" name="qcld_article_label_keywords">
                                                <p><i><?php esc_html_e( "Use comma to seperate keywords", "seo-help" ); ?></i></p>
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_label_word_to_avoid"><?php esc_html_e( "Keywords to Avoid", "seo-help" ); ?> </label>
                                                <input type="text" id="qcld_article_label_word_to_avoid" placeholder="Write Keywords..." class="qcld_article_label_word_to_avoid" name="qcld_article_label_word_to_avoid" value="">
                                                <p><i><?php esc_html_e( "Use comma to seperate keywords", "seo-help" ); ?></i></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                                        <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_label_keywords_bold"><?php esc_html_e( "Make Keywords Bold", "seo-help" ); ?> </label>
                                                <input type="checkbox" id="qcld_article_label_keywords_bold" class="qcld_article_label_keywords_bold" name="qcld_article_label_keywords_bold" value="1">
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_img"><?php esc_html_e( "Add Image", "seo-help" ); ?> </label>
                                                <input type="checkbox" name="qcld_article_heading_img" id="qcld_article_heading_img" class="qcld_article_heading_img" value="1"/>
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_tagline"><?php esc_html_e( "Add Tagline", "seo-help" ); ?> </label>
                                                <input type="checkbox" id="qcld_article_heading_tagline"  name="qcld_article_heading_tagline" class="qcld_article_heading_tagline" value="1" />
                                            </div>


                                        </div>
                                    </div>
                                    <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                                        <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_intro"><?php esc_html_e( "Add Introduction", "seo-help" ); ?> </label>
                                                <input type="checkbox" id="qcld_article_heading_intro" name="qcld_article_heading_intro" class="qcld_article_heading_intro" value="1"/>
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_conclusion"><?php esc_html_e( "Add Conclusion", "seo-help" ); ?> </label>
                                                <input type="checkbox" id="qcld_article_heading_conclusion" name="qcld_article_heading_conclusion" class="qcld_article_heading_conclusion" value="1" />
                                            </div>

                                            <div class="qcld_seohelp_ai_con">
                                                <label for="qcld_article_heading_faq"><?php esc_html_e( "Add Faq", "seo-help" ); ?> </label>
                                                <input type="checkbox" id="qcld_article_heading_faq" name="qcld_article_heading_faq" class="qcld_article_heading_faq" value="1" />
                                            </div>

                                        </div>
                                    </div>
                                    <button id="qcld_article_keyword_suggestion" class="btn btn-info" ><?php esc_html_e('Generate', 'seo-help'); ?></button>
                                    <p style="color:red;"><b><?php esc_html_e('(Please'); ?> <a href="<?php echo esc_url('https://platform.openai.com/settings/organization/billing/'); ?>" target="_blank"><?php esc_html_e('Pre-purchase credit'); ?></a> <?php esc_html_e('from OpenAI API platform and increase the API usage limit. Otherwise, AI features will not work)'); ?></b></p>
                                    <hr/>
                                    <div class="linkbait_single_field"> 
                                        <div id="linkbait_article_keyword_data">
                                        </div>

                                        <div class="qcld_seo-playground-buttons">
                                            <div class="qcld-seohelp-input">
                                              <div class="qcld_seo_pro_feature"><?php esc_html_e( 'Pro Feature', 'seo-help' ); ?></div>
                                            </div>
                                            <button class="button button-primary qcld_article_playground_save" disabled><?php esc_html_e("Save as Draft", 'qcld-seo-help'); ?></button>
                                            <button class="button qcld_article_playground_clear" disabled><?php esc_html_e("Clear", 'qcld-seo-help'); ?></button>
                                        </div>
                                    </div>
                                </div>

                            </div>  
                            <div class="tab-pane fade" id="content-tab-pane" role="tabpanel" aria-labelledby="content-tab" tabindex="0">
                                
                              
                                <div class="qcld-seohelp">
                                <h5><?php esc_html_e( 'Rewrite article', 'seo-help' ); ?> <span class="qcld_seohelp_pro"><?php esc_html_e( 'Pro Feature', 'seo-help' ); ?></span></h5>
                                <textarea id="qcld_content_rewrite" class="form-control" data-press="qcld_content_rewrite"></textarea>
                                <div class="qcld_content_rewrite_count_wrap"><span class="qcld_content_rewrite_count">0</span></div>
                                <button id="qcld_keyword_rewrite_article" class="btn btn-info" disabled><?php esc_html_e( 'Generate', 'seo-help' ); ?></button>
                                <div id="qcld_content_rewrite_result">
                                    
                                </div>
                                </div>

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
    //}
  }
}


if ( ! function_exists( 'qcld_linkbait_seo_meta_boxes' ) ) {
  function qcld_linkbait_seo_meta_boxes() { //Metabox for Link Content Writting Tips //
    add_meta_box(
      'linkbait-seo-post-class',      // Unique ID
      esc_html__( 'Content Writing Tips', 'seo-help' ),    // Title
      'qcld_linkbait_seo_tips_meta_box',   // Callback function
      array('page','post'),         // Admin page (or post type)
      'side',         // Context
      'high'         // Priority
    );
  }
}

if ( ! function_exists( 'qcld_linkbait_seo_tips_meta_box' ) ) {
  function qcld_linkbait_seo_tips_meta_box($object,$box){ //Callback Function for Linkbait Content Writting Tips //
    
    $data=array(
      'Organize your contents with the APP method - “APP” stands for: Agree, Promise, and Preview.',
      'Add More LSI Keywords With “Searches Related To…” from Google search for your main keywords',
      'Add “Bucket Brigades” To Slash Bounce Rate and Boost Time On Page'
      
    );
    
    $keys = array_rand($data,3);
    foreach($keys as $k=>$v){
      echo '<p class="seo_tips_rand" id="'.str_replace(' ','-',strtolower($data[$v])).'" style="cursor:pointer">'.$data[$v].'</p>';
    }
    
  }
}

if ( ! function_exists( 'qcld_linkbait_seo_tips_meta_box' ) ) {
  function qcld_linkbait_seo_tips_meta_box() { // Metabox for CTR Improvement //
    add_meta_box(
      'linkbait-traffic-post-class',      // Unique ID
      esc_html__( 'CTR Improvement', 'seo-help' ),    // Title
      'qcld_linkbait_get_traffic_meta_box',   // Callback function
      array('page','post'),         // Admin page (or post type)
      'side',         // Context
      'high'         // Priority
    );
  }
}

if ( ! function_exists( 'qcld_linkbait_get_traffic_meta_box' ) ) {
  function qcld_linkbait_get_traffic_meta_box($object,$box){ // Callback Function for CTR Improvement //
    
  ?>
    <div class="linkbait_traffic_meta">
    <p class="linkbait_traffic_top" style="color:red"><?php echo esc_html__( "Coming Soon", 'seo-help' ); ?></p>
    
    </div>
  <?php   
  }
}


if ( ! function_exists( 'qcld_seo_order_index_catalog_menu_page' ) ) {
  function qcld_seo_order_index_catalog_menu_page( $menu_ord ){

    global $submenu;

    // Enable the next line to see a specific menu and it's order positions
    //echo '<pre>'; print_r( $submenu['seo-help'] ); echo '</pre>'; exit();

    // Sort the menu according to your preferences
    //Original order was 5,11,12,13,14,15

    $arr = array();

    if(isset($submenu['seo-help'][1]))
      $arr[] = $submenu['seo-help'][1];

    if(isset($submenu['seo-help'][0]))
      $arr[] = $submenu['seo-help'][0];

    $submenu['seo-help'] = $arr;

    return $menu_ord;

  }
}


if ( ! function_exists( 'qcpromo_seo_help_promo_page_callaback' ) ) {
  function qcpromo_seo_help_promo_page_callaback() {

    $action = 'admin.php?page=qcld-seo-help';
  ?>
    <!-- Create a header 'wrap' container -->
    <div class="wrap qcld-seo-help">
      <div id="icon-themes" class="icon32"></div>

        <div><h3><?php echo esc_html__( "SEO HELP", 'seo-help' ); ?></h3></div>
       <div id="tabs">
        <ul  class="nav-tab-wrapper">
            <li><a class="nav-tab" href="#qcld_seo_tab-1" style="border: 1px solid transparent; text-align:left;"><?php echo esc_html__( "General Settings", 'seo-help' ); ?></a></li>
            <li><a class="nav-tab" href="#qcld_seo_tab-2" style="border: 1px solid transparent; text-align:left;"><?php echo esc_html__( "AI settings", 'seo-help' ); ?></a></li>
            <li><a class="nav-tab" href="#qcld_seo_tab-3" style="border: 1px solid transparent; text-align:left;"><?php esc_html_e( "RSS Settings", 'seo-help' ); ?></a></li>
          
        </ul>
        <div id="qcld_seo_tab-1">
          <form action="<?php echo esc_url('admin.php?page=qcld-seo-help'); ?>" method="POST" enctype="multipart/form-data">
            <h3><?php echo esc_html__( "General Settings", 'seo-help' ); ?></h3>

            <div class="cxsc-settings-blocks">
              <p class="qc-opt-title-font"><?php echo esc_html__('Open external links in a new window', 'seo-help'); ?> </p>
              <fieldset>
                  <input id="qcld_external_links_in_new_windows_force" type="checkbox" name="qcld_external_links_in_new_windows_force"
                         value="<?php echo esc_attr('on', 'seo-help'); ?>" <?php echo(get_option('qcld_external_links_in_new_windows_force') == 'on' ? 'checked' : ''); ?>>
                  <label for="qcld_external_links_in_new_windows_force"><?php echo esc_html__('Enable to open all external links in a new tab or window', 'seo-help'); ?></label>
              </fieldset>
            </div>

            <div class="cxsc-settings-blocks">
              <p class="qc-opt-title-font"><?php echo esc_html__('Floating Icon for AI Assistant', 'seo-help'); ?> </p>
              <fieldset>
                  <input id="qcld_disable_floating_icon" type="checkbox" name="qcld_disable_floating_icon"
                         value="<?php echo esc_attr('on', 'seo-help'); ?>" <?php echo(get_option('qcld_disable_floating_icon') == 'on' ? 'checked' : ''); ?>>
                  <label for="qcld_disable_floating_icon"><?php echo esc_html__('Disable Floating Icon for AI Assistant', 'seo-help'); ?></label>
              </fieldset>
            </div>

            <div class="cxsc-settings-blocks qcld-submit-section">
              <input type="hidden" name="action" value="<?php echo esc_attr('qc_fab-submitted', 'seo-help'); ?>"/>
              <input type="submit" class="btn btn-primary submit-button" name="submit" id="submit" value="<?php echo esc_html__('Save Settings', 'seo-help'); ?>"/>
            </div>
            <?php wp_nonce_field('qcld-seo-help'); ?>
          </form>

        </div>
        <div id="qcld_seo_tab-2">
         <div class="qcld-seohelp qcld-seohelp_ai_wrap">
                
            <h5 class="card-title"><?php esc_html_e('AI settings', 'seo-help'); ?></h5>

            <form class="qcld_ai_settings_form py-0" action="<?php echo esc_url('admin.php?page=qcld-seo-help', 'seo-help'); ?>">
               <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">
                    <div class="qcld_seohelp_ai_con qcld_seohelp_ai_first_init">
                        <input type="radio" name="qcld_ai_settings_open_ai" id="qcld_ai_settings_open_ai" class="qcld_ai_settings_open_ai" value="ai" <?php echo( get_option('qcld_ai_settings_open_ai') !== 'gemini' ? 'checked' : ''); ?>/>
                        <label for="qcld_ai_settings_open_ai"><?php esc_html_e( "Enable OpenAI", "qcld-seo-help" ); ?></label>
                        <input type="radio" name="qcld_ai_settings_open_ai" id="qcld_ai_settings_open_plam" class="qcld_ai_settings_open_ai" value="gemini" <?php echo(get_option('qcld_ai_settings_open_ai') == 'gemini' ? 'checked' : ''); ?> />
                        <label for="qcld_ai_settings_open_plam"><?php esc_html_e( "Enable Gemini AI", "qcld-seo-help" ); ?></label> 
                    </div>
                </div>

                <!-- start ai settings -->
                <div class="qcld_ai_settings_wrap qcld_open_ai_active" <?php echo( get_option('qcld_ai_settings_open_ai') == 'gemini' ? 'style="display:none"' : ''); ?> >
                    <h5 class="card-title"><?php esc_html_e('OpenAI settings', 'seo-help'); ?></h5>
                    <p style="color:red;"><b><?php esc_html_e('(Please'); ?> <a href="<?php echo esc_url('https://platform.openai.com/settings/organization/billing/'); ?>" target="_blank"><?php esc_html_e('Pre-purchase credit'); ?></a> <?php esc_html_e('from OpenAI API platform and increase the API usage limit. Otherwise, AI features will not work)'); ?></b></p>
                    <div class="form-group  mb-3">
                        <label for="api_key"><?php esc_html_e('API KEY', 'seo-help'); ?></label>
                        <input type="password" class="form-control" id="api_key" placeholder="<?php esc_html_e('API KEY', 'seo-help'); ?>" value="<?php echo get_option('qcld_seohelp_api_key');?>">
                        <p><a class="qcld_help_link" href="<?php echo esc_url('https://beta.openai.com/account/api-keys'); ?>" target="_blank"><?php esc_html_e('Get Your Api Key'); ?></a></p>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="opeai_engines"><?php esc_html_e('Engines', 'seo-help'); ?></label>
                        </div>
                        <select class="custom-select" id="opeai_engines" >

                            <option value="<?php esc_attr_e( 'gpt-4o-mini','seo-help'); ?>" <?php echo (( get_option('qcld_seohelp_ai_engines',true) == 'gpt-4o-mini') ? esc_attr('selected') : '') ; ?>><?php esc_html_e( 'GPT-4o mini','seo-help');?></option>
                            <option value="<?php esc_attr_e( 'gpt-4o','seo-help'); ?>" <?php echo (( get_option('qcld_seohelp_ai_engines',true) == 'gpt-4o') ? esc_attr('selected') : ''); ?>><?php esc_html_e( 'GPT-4o','seo-help');?></option>
                            <option value="<?php esc_attr_e( 'gpt-4','seo-help'); ?>" <?php echo (( get_option('qcld_seohelp_ai_engines',true) == 'gpt-4') ? esc_attr('selected') : ''); ?>><?php esc_html_e( 'GPT-4','seo-help');?></option>
                            <option value="<?php esc_attr_e( 'gpt-3.5-turbo','seo-help'); ?>" <?php echo (( get_option('qcld_seohelp_ai_engines',true) == 'gpt-3.5-turbo') ? esc_attr('selected') : ''); ?>><?php esc_html_e( 'GPT-3 turbo','seo-help'); ?></option>
                            <option value="<?php esc_attr_e('gpt-3.5-turbo-instruct-0914', 'seo-help'); ?>" <?php echo (( get_option('qcld_seohelp_ai_engines',true) == 'gpt-3.5-turbo-instruct-0914') ? ' selected' : ' '); ?>><?php esc_html_e('gpt-3.5-turbo-instruct-0914', 'seo-help'); ?></option>
                            <option value="<?php esc_attr_e('gpt-3.5-turbo-instruct', 'seo-help'); ?>" <?php echo (( get_option('qcld_seohelp_ai_engines',true) == 'gpt-3.5-turbo-instruct') ? ' selected' : ' '); ?>><?php esc_html_e('gpt-3.5-turbo-instruct', 'seo-help'); ?></option>
                        </select>
                    </div>
                    <div class="form-group  mb-3">
                        <label for="max_token"><?php esc_html_e('Max token ( Min: 0, Max:4000 )', 'seo-help'); ?></label>
                        <input type="text" class="max_token" id="max_token" placeholder="Max token"  value="<?php echo get_option('qcld_seohelp_max_token',true);?>">
                        <p class="qcld_seohelp_p"><?php esc_html_e('Depending on the model', 'seo-help'); ?></p>
                    </div>
                    <div class="form-group  mb-3">
                        <label for="qcld_seo_temperature"><?php esc_html_e('Temperature', 'seo-help'); ?></label>
                        <input id="qcld_seo_temperature" type="text" data-slider-min="0" data-slider-max="1.0" data-slider-step="0.1" data-slider-value="<?php echo get_option('qcld_seohelp_ai_temperature',true) ? get_option('qcld_seohelp_ai_temperature',true) : '0.5' ;?>"  value="<?php echo get_option('qcld_seohelp_ai_temperature',true); ?>"/>
                        <span id="temperatureSliderValLabel"> <span id="temperatureVal"><?php echo get_option('qcld_seohelp_ai_temperature',true) ? get_option('qcld_seohelp_ai_temperature',true) : '0.5' ;?></span></span>
                        <p class="qcld_seohelp_p"><?php esc_html_e('Temperature is a value between 0 and 1 that essentially lets you control how confident the model should be when making these predictions', 'seo-help'); ?></p>
                    </div>
                    <div class="form-group  mb-3">
                        <label for="qcld_seo_presence_penalty"><?php esc_html_e('Presence Penalty', 'seo-help'); ?></label>
                        <input id="qcld_seo_presence_penalty" type="text" data-slider-min="-2" data-slider-max="2" data-slider-step="0.1" data-slider-value="<?php echo get_option('qcld_seohelp_ai_ppenalty',true) ? get_option('qcld_seohelp_ai_ppenalty',true) : '0' ;?>"  value="<?php echo get_option('qcld_seohelp_ai_ppenalty',true);?>"/>
                        <span id="presence_penaltySliderValLabel"> <span id="presence_penaltyVal"><?php echo get_option('qcld_seohelp_ai_ppenalty',true) ? get_option('qcld_seohelp_ai_ppenalty',true) : '0' ;?></span></span>
                        <p class="qcld_seohelp_p"><?php esc_html_e('Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model’s likelihood to talk about new topics.', 'seo-help'); ?></p>
                    </div>
                    <div class="form-group  mb-3">
                        <label for="qcld_seo_frequency_penalty"> <?php esc_html_e('Frequency Penalty', 'seo-help'); ?></label>
                        <input id="qcld_seo_frequency_penalty" type="text" data-slider-min="-2" data-slider-max="2" data-slider-step="0.1" data-slider-value="<?php echo get_option('qcld_seohelp_ai_fpenalty',true) ? get_option('qcld_seohelp_ai_fpenalty',true) : '0' ;?>"  value="<?php echo get_option('qcld_seohelp_ai_fpenalty',true);?>" />
                        <span id="frequency_penaltySliderValLabel"> <span id="frequency_penaltyVal"><?php echo get_option('qcld_seohelp_ai_fpenalty',true) ? get_option('qcld_seohelp_ai_fpenalty',true) : '0' ;?> </span></span>
                        <p class="qcld_seohelp_p"><?php esc_html_e('Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model’s likelihood to repeat the same line verbatim.', 'seo-help'); ?></p>
                    </div>
                    <a class="btn btn-primary openai_save_settings"> <?php esc_html_e('Save Settings', 'seo-help'); ?></a>
                    <br>
                    <br>
                    <p style="color:indianred;">** <?php esc_html_e('If Auto Generate content with OpenAI is not working, then likely you hit your OpenAI usage limit. Add a', 'seo-help'); ?> <a href="<?php echo esc_url('https://platform.openai.com/account/billing/overview'); ?>" target="_blank"> <?php esc_html_e('billing detail', 'seo-help'); ?> </a> <?php esc_html_e('and increase the Usage limit.', 'seo-help'); ?></p>

                </div>
                <!-- end ai settings -->

                <!-- start ai settings -->
                <div class="qcld_ai_settings_wrap qcld_open_palm_active" <?php echo( get_option('qcld_ai_settings_open_ai') == 'gemini' ? 'style="display:block"' : 'style="display:none"'); ?>>

                    <h5 class="card-title"><?php esc_html_e('Gemini settings', 'seo-help'); ?></h5>
                    <div class="qcld_gemini_ai_infos"><?php esc_html_e( 'You can get your Gemini API Key from', 'seo-help' ); ?> <a href="<?php echo esc_url('https://aistudio.google.com/app/apikey'); ?>" target="_blank"><?php esc_html_e( 'https://aistudio.google.com/app/apikey', 'seo-help' ); ?></a> </div>
                    <br>
                    <br>

                    <div class="form-group mb-3">
                        <label for="qcld_gemini_api_key"><?php esc_html_e('Gemini API Key', 'seo-help'); ?></label>
                        <input type="password" class="form-control" id="qcld_gemini_api_key" placeholder="<?php esc_html_e('API KEY', 'seo-help'); ?>" value="<?php echo get_option('qcld_gemini_api_key');?>">
                        <p><a class="qcld_help_link" href="<?php echo esc_url('https://aistudio.google.com/app/apikey'); ?>" target="_blank"><?php esc_html_e('Get Your Api Key'); ?></a></p>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="qcld_gemini_model"><?php esc_html_e('Select a Model', 'seo-help'); ?></label>
                        </div>
                        <select name="qcld_gemini_model" id="qcld_gemini_model">
                          <option value="<?php esc_attr_e( 'gemini-1.5-pro','seo-help'); ?>" <?php echo (( get_option('qcld_gemini_model',true) == 'gemini-1.5-pro') ? ' selected' : ' '); ?>><?php esc_html_e( 'Gemini 1.5 Pro','seo-help');?></option>
                          <option value="<?php esc_attr_e( 'gemini-1.5-flash','seo-help'); ?>" <?php echo (( get_option('qcld_gemini_model',true) == 'gemini-1.5-flash') ? ' selected' : ' '); ?>><?php esc_html_e( 'Gemini 1.5 Flash','seo-help');?></option>
                          <option value="<?php esc_attr_e( 'gemini-1.0-pro','seo-help'); ?>" <?php echo (( get_option('qcld_gemini_model',true) == 'gemini-1.0-pro') ? ' selected' : ' '); ?>><?php esc_html_e( 'Gemini 1.0 Pro','seo-help');?></option>
                          <option value="<?php esc_attr_e( 'gemini-pro','seo-help'); ?>" <?php echo (( get_option('qcld_gemini_model',true) == 'gemini-pro') ? ' selected' : ' '); ?>><?php esc_html_e( 'Gemini Pro','seo-help');?></option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="qcld_gemini_api_version"><?php esc_html_e('API Version', 'seo-help'); ?></label>
                        </div>
                        <select name="qcld_gemini_api_version" id="qcld_gemini_api_version">
                          <option value="<?php esc_attr_e( 'v1','seo-help'); ?>" <?php echo (( get_option('qcld_gemini_api_version',true) == 'v1' ) ? ' selected' : ' '); ?>><?php esc_html_e( 'v1','seo-help');?></option>
                          <option value="<?php esc_attr_e( 'v1beta','seo-help'); ?>" <?php echo (( get_option('qcld_gemini_api_version',true) == 'v1beta' ) ? ' selected' : ' '); ?>><?php esc_html_e( 'v1beta','seo-help');?></option>
                         
                        </select>
                    </div>
                    <div class="form-group  mb-3">
                        <label for="qcld_gemini_max_token"><?php esc_html_e('Maximum Token', 'seo-help'); ?></label>
                        <input type="text" class="qcld_gemini_max_token" id="qcld_gemini_max_token" placeholder="Max token"  value="<?php echo get_option('qcld_gemini_max_token',true);?>">
                        <p class="qcld_seohelp_p"><?php esc_html_e('Optional. The maximum number of tokens to include in a response. Default: 2024.', 'seo-help'); ?></p>
                        <br>
                    </div>
   
                    <div class="form-group  mb-3 temperature_slider">
                        <label for="qcld_gemini_ai_temperature"><?php esc_html_e('Temparature', 'seo-help'); ?></label>
                        <input id="qcld_gemini_ai_temperature" type="text" data-slider-min="0" data-slider-max="2.0" data-slider-step="0.1" data-slider-value="<?php echo get_option('qcld_gemini_ai_temperature',true) ? get_option('qcld_gemini_ai_temperature',true) : '1' ;?>"  value="<?php echo get_option('qcld_gemini_ai_temperature',true); ?>"/>
                        <span id="temperatureSliderValLabel" class="temperature_slider_count"> <span id="qcld_gemini_ai_temperature_val"><?php echo get_option('qcld_gemini_ai_temperature',true) ? get_option('qcld_gemini_ai_temperature',true) : '1' ;?></span></span>
                        <p class="qcld_seohelp_p"><?php esc_html_e('Optional. Controls the randomness of the output. Values can range from [0.0, 2.0].', 'seo-help'); ?></p>
                        <br>
                    </div>
   
                    <div class="form-group  mb-3 temperature_slider">
                        <label for="qcld_gemini_ai_top_p"><?php esc_html_e('Top-P', 'seo-help'); ?></label>
                        <input id="qcld_gemini_ai_top_p" type="text" data-slider-min="0" data-slider-max="1" data-slider-step="0.01" data-slider-value="<?php echo get_option('qcld_gemini_ai_top_p',true) ? get_option('qcld_gemini_ai_top_p',true) : '0.95' ;?>"  value="<?php echo get_option('qcld_gemini_ai_top_p',true); ?>"/>
                        <span id="temperatureSliderValLabel" class="temperature_slider_count"> <span id="qcld_gemini_ai_top_p_val"><?php echo get_option('qcld_gemini_ai_top_p',true) ? get_option('qcld_gemini_ai_top_p',true) : '0.95' ;?></span></span>

                        <p class="qcld_seohelp_p">
                            <?php esc_html_e('Optional. The maximum cumulative probability of tokens to consider when sampling.', 'seo-help'); ?> <br>
                            <?php esc_html_e('Note: The default value varies by model, see the Model.top_p attribute.', 'seo-help'); ?> <br>
                            <?php esc_html_e('Empty topP field in Model indicates the model doesn\'t apply top-p sampling and doesn\'t allow setting topP on requests.', 'seo-help'); ?>
                        </p>
                        <br>
                    </div>
   
                    <div class="form-group  mb-3 temperature_slider">
                        <label for="qcld_gemini_ai_top_k"><?php esc_html_e('Top-K', 'seo-help'); ?></label>
                        <input id="qcld_gemini_ai_top_k" type="text" data-slider-min="1" data-slider-max="40" data-slider-step="1" data-slider-value="<?php echo get_option('qcld_gemini_ai_top_k',true) ? get_option('qcld_gemini_ai_top_k',true) : '1' ;?>"  value="<?php echo get_option('qcld_gemini_ai_top_k',true); ?>"/>
                        <span id="temperatureSliderValLabel" class="temperature_slider_count"> <span id="qcld_gemini_ai_top_k_val"><?php echo get_option('qcld_gemini_ai_top_k',true) ? get_option('qcld_gemini_ai_top_k',true) : '1' ;?></span></span>
                        <p class="qcld_seohelp_p">
                          <?php esc_html_e('Optional. The maximum number of tokens to consider when sampling.', 'seo-help'); ?> <br>
                          <?php esc_html_e('Note: The default value varies by model, see the Model.top_k attribute.', 'seo-help'); ?> <br>
                          <?php esc_html_e('Empty topK field in Model indicates the model doesn\'t apply top-k sampling and doesn\'t allow setting topK on requests.', 'seo-help'); ?>
                        </p>
                    </div>
                    <a class="btn btn-primary gemini_save_settings"> <?php esc_html_e('Save Settings', 'seo-help'); ?></a>

                    <br>
                    <br>
                    <p>**<?php esc_html_e('If Auto Generate content with Gemini AI is not working, then likely you hit your Gemini AI usage limit. Add a', 'seo-help'); ?> <a href="<?php echo esc_url('https://aistudio.google.com/app/apikey'); ?>" target="_blank"> <?php esc_html_e('billing detail', 'seo-help'); ?> </a> <?php esc_html_e('and increase the Usage limit.', 'seo-help'); ?></p>


                </div>
                <!-- end ai settings -->


            </form>
                                
          </div>

        </div>
        <div id="qcld_seo_tab-3">
         <div class="qcld-seohelp qcld-seohelp_ai_wrap">
                
            

            <form class="qcld_ai_settings_form" action="<?php echo esc_url('admin.php?page=qcld-seo-help', 'seo-help'); ?>">

                <h5 class="card-title"><?php esc_html_e('RSS Settings', 'seo-help'); ?> <div class="qcld_seo_pro_feature_text"><?php esc_html_e('Pro Features', 'seo-help'); ?></div></h5>


                <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">
                    <div class="qcld_seohelp_ai_con">
                        <input type="checkbox" name="qcld_rss_settings_enable" id="qcld_rss_settings_enable" class="qcld_rss_settings_enable form-check-input" value="1" disabled />

                        <label for="qcld_rss_settings_enable"><?php esc_html_e( "Enable automatic CRON job for RSS feed", "seo-help" ); ?></label>
                    </div>
                </div>

                <div class="cxsc-settings-blocks">
                    <p class="qc-opt-title-font"><?php esc_html_e('First cron execution time', 'seo-help'); ?> </p>
                    <fieldset>
                        <input type="datetime-local" class="form-control" name="qcld_seo_help_cron_execution" id="qcld_seo_help_cron_execution" value="" disabled> 
                  
                    </fieldset>
                    <p><?php esc_html_e('When past date will be provided, event will be executed in the next queue.', 'seo-help'); ?></p>
                </div>

                <div class="cxsc-settings-blocks">
                    <p class="qc-opt-title-font"><?php esc_html_e('Schedule', 'qcld-seo-help'); ?> </p>
                    <fieldset>
                        <select class="form-control" name="qcld_seo_help_cron_schedule" id="qcld_seo_help_cron_schedule" disabled>
                            <option value="hourly"><?php esc_html_e('Once Hourly (hourly)', 'seo-help' ); ?></option>
                            <option value="weekly"><?php esc_html_e('Once Weekly (weekly)', 'seo-help' ); ?></option>
                            <option value="every_1_minute"><?php esc_html_e('1 min (every_1_minute)', 'seo-help' ); ?></option>
                            <option value="monthly"><?php esc_html_e('Monthly (monthly)', 'seo-help' ); ?></option>
                            <option value="fifteendays"><?php esc_html_e('Every 15 Days (fifteendays)', 'seo-help' ); ?></option>
                            <option value="wp_1_wc_privacy_cleanup_cron_interval"><?php esc_html_e('Every 5 minutes (wp_1_wc_privacy_cleanup_cron_interval)', 'seo-help' ); ?></option>
                            <option value="twicedaily"><?php esc_html_e('Twice Daily (twicedaily)', 'seo-help' ); ?></option>
                            <option value="daily"><?php esc_html_e('Once Daily (daily)', 'seo-help' ); ?></option>
                        </select>
                  
                    </fieldset>
                    <p><?php esc_html_e('After first execution repeat.', 'seo-help'); ?></p>
                </div>



                <br>
                <br>
                <a class="btn btn-primary rss_save_settings" disabled> <?php esc_html_e('RSS Settings', 'seo-help'); ?></a>
            </form>
                                
          </div>

        </div>
         
    </div>
    <!-- /.wrap -->
  <?php
  } // end qcpromo_seo_help_promo
} // end qcpromo_seo_help_promo

if ( ! function_exists( 'qcpromo_seo_help_broken_link_page_callaback' ) ) {
  function qcpromo_seo_help_broken_link_page_callaback() {

    $action = 'admin.php?page=qc-seo-broken-link-checker';
  ?>
    <!-- Create a header 'wrap' container -->
    <div class="wrap qcld-seo-help ">
      <div id="icon-themes" class="icon32"></div>
      <h3><?php echo esc_html__('Broken Link Quick Scan', 'seo-help'); ?></h3>

       <div id="tabs">
        <ul  class="nav-tab-wrapper">
            <li><a class="nav-tab" href="#qcld_seo_tab-11"><?php echo esc_html__('Pages & Posts', 'seo-help'); ?></a></li>
            <li><a class="nav-tab" href="#qcld_seo_tab-22"><?php echo esc_html__('Simple Link Directory', 'seo-help'); ?></a></li>
        </ul>

        <div id="qcld_seo_tab-11">
          <div class="qc_seo_wrapper">
            <p><?php echo esc_html__('Suitable for checking a relatively small number of posts and pages for broken External Links. Use the scan settings for larger number.', 'seo-help'); ?></p>


            <form method="post" action="<?php echo esc_url($action); ?>">
               <table class="form-table"  >

               <tr><td><?php echo esc_html__('Check for Broken Links', 'seo-help'); ?></td>
               <td>
                <button type="button" name="qc_seo_linkcheck" value="Start Checking" class="button button-primary qc_seo_linkcheck"><?php echo esc_html__('Start Checking', 'seo-help'); ?></button></td>
               </tr></table>
             </form>
             <div class="qcld_seo_help_link_content"></div>
           
          </div>
        </div>

        <div id="qcld_seo_tab-22">
          <div class="qc_seo_wrapper">
            <p><?php echo esc_html__('Check for broken links in Simple Link Directory plugin', 'seo-help'); ?> ( <a href="<?php echo esc_url('https://wordpress.org/plugins/simple-link-directory/'); ?>" target="_blank"><?php echo esc_html__('Free version', 'seo-help'); ?></a> | <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"><?php echo esc_html__('Pro version', 'seo-help'); ?></a> )</p>

            <form method="post" action="admin.php?page=qc-seo-broken-link-checker">
              <table class="form-table"  >
              <tr><td> <?php echo esc_html__('Check for Broken Links', 'seo-help'); ?></td>
              <td><button type="button" name="qc_seo_simple_linkcheck" value="Start Link Checking" class="button button-primary qc_seo_simple_linkcheck"  ><?php echo esc_html__('Start Link Checking', 'seo-help'); ?></button></td>
              </tr></table>
            </form>
            <div class="qcld_seo_help_sld_link_content"></div>

            <?php

            /*echo qc_seo_help_simple_brokenlink_form_show();*/
      
            if (isset($_POST['qc_seo_simple_linkcheck']) ){
             //link checker 
              if ($_POST['qc_seo_simple_linkcheck']=='Start Link Checking'){ 

                qc_seo_help_simple_broken_link_check(); 
              }
            }

            ?>
          </div>
        </div>


      </div>
         
    </div>
    <!-- /.wrap -->
  <?php
  } // end qcpromo_seo_help_promo
} // end qcpromo_seo_help_promo




if ( ! function_exists( 'qcpromo_seo_help_section_page_callaback' ) ) {
  function qcpromo_seo_help_section_page_callaback() {

    $action = 'admin.php?page=seo-help';
  ?>
    <!-- Create a header 'wrap' container -->
    <div class="wrap qcld-seo-help">
      <div id="icon-themes" class="icon32"></div>
      <h3><?php echo esc_html__('SEO HELP', 'seo-help'); ?></h3>

       <div id="tabs">
        <ul  class="nav-tab-wrapper qcld_help_sections">
            <li><a class="nav-tab" href="#qcld_seo_tab-333" style="border: 1px solid transparent; text-align:left;"><?php echo esc_html__('OpenAI', 'seo-help'); ?></a></li>
            <li><a class="nav-tab" href="#qcld_seo_tab-444" style="border: 1px solid transparent; text-align:left;"><?php echo esc_html__('Others', 'seo-help'); ?></a></li>
            <li><a class="nav-tab" href="#qcld_seo_tab-555" style="border: 1px solid transparent; text-align:left;"><?php echo esc_html__('Link Status', 'seo-help'); ?></a></li>
        </ul>
       
       
        <div id="qcld_seo_tab-333">
          <div class="qc_seo_wrappers">

            <div class="qcld-seohelp">
              <div class="accordion" id="qcldopenaiaccordion">
                
                <div class="accordion-item">
                    <h3 class="accordion-header" id="panelsStayOpen-headingZero">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseZero" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne"><?php  esc_html_e('How to Use OpenAI', 'seo-help'); ?></button>
                    </h3>
                    <div id="panelsStayOpen-collapseZero" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingZero" style="">
                    <div class="accordion-body">
                    <p><b><?php  esc_html_e('AI Content Creation', 'seo-help'); ?></b></p>
                    <p> <?php  esc_html_e('Click the Generate Button to Get OpenAI Title Suggestions', 'seo-help'); ?></p>
                    <img class="img-fluid" src="<?php echo qcld_linkbait_img_url.'/openai_img_1.jpg' ?>">
                    <p><b><?php  esc_html_e('Content Generator', 'seo-help'); ?></b></p>
                    <p> <?php  esc_html_e('Title and Keyword suggestion', 'seo-help'); ?></p>
                    <img class="img-fluid" src="<?php echo qcld_linkbait_img_url.'/openai_img_2.jpg' ?>">
                    
                    </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h3 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne"><?php  esc_html_e('How to create OpenAI API', 'seo-help'); ?></button>
                    </h3>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne" style="">
                    <div class="accordion-body">
                    <p> <?php  esc_html_e('The OpenAI API uses API keys for authentication. Visit your API Keys page to retrieve the API key you’ll use in your requests.Remember that your API key is a secret! Do not share it with others or expose it in any client-side code (browsers, apps). Production requests must be routed through your own backend server where your API key can be securely loaded from an environment variable or key management service.', 'seo-help'); ?></p>
                    <p class="text-danger"> ** <?php  esc_html_e('To go live you have to apply to OpenAI. Please follow the instructions ', 'seo-help'); ?> <a class="text-danger test-decoration-none" href="<?php echo esc_url('https://beta.openai.com/docs/going-live', 'seo-help'); ?>" target="_blank"> <?php  esc_html_e('instructions on this page', 'seo-help'); ?> </a></p>
                    <img class="img-fluid" src="<?php echo qcld_linkbait_img_url.'/api_screenshort.png' ?>">
                    
                    </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo"><?php  esc_html_e('Presence Penalty ', 'seo-help'); ?>  </button>
                    </h3>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo" style="">
                    <div class="accordion-body"> <?php  esc_html_e('Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model’s likelihood to talk about new topics.', 'seo-help'); ?>  </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="panelsStayOpen-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree"><?php  esc_html_e('Frequency Penalty', 'seo-help'); ?> </button>
                    </h3>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree" style="">
                        <div class="accordion-body"><?php  esc_html_e('Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model’s likelihood to repeat the same line verbatim.', 'seo-help'); ?>                 
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="panelsStayOpen-headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsefour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour"> <?php  esc_html_e('Tempareture', 'seo-help'); ?> </button>
                    </h3>
                    <div id="panelsStayOpen-collapsefour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour" style="">
                      <div class="accordion-body">
                       <?php  esc_html_e('One of the most important settings is called temperature.When the temperature is above 0, submitting the same prompt results in different completions each time.Remember that the model predicts which text is most likely to follow the text preceding it. Temperature is a value between 0 and 1 that essentially lets you control how confident the model should be when making these predictions. Lowering temperature means it will take fewer risks, and completions will be more accurate and deterministic. Increasing temperature will result in more diverse completions.', 'seo-help'); ?>            
                     </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="panelsStayOpen-headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsesix" aria-expanded="false" aria-controls="panelsStayOpen-collapsesix"><?php  esc_html_e('My OpenAI does not work', 'seo-help'); ?></button>
                    </h3>
                    <div id="panelsStayOpen-collapsesix" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingSix" style="">
                    <div class="accordion-body">
                      <?php  esc_html_e('Please check your settings( i.e: Api keys, Max token etc.). Depending on the model (GPT3 was maximum 4000 other is 1,951 ) used, requests can use up to 4097 tokens shared between prompt and completion. If your prompt is 4000 tokens, your completion can be 97 tokens at most. ', 'seo-help'); ?></div>
                    </div>
                </div>
               
                
              </div>
            </div>
            



          </div>
        </div>
       
       
        <div id="qcld_seo_tab-444">
          <div class="qc_seo_wrapper">
            <p><b><?php echo esc_html__('Click the generate button to open a Lightbox', 'seo-help'); ?></b></p>
            <img src="<?php echo qcld_linkbait_img_url.'/seo-suggest-1.png' ?>" class="img-thumbnail" alt="seo help">
            <p><b><?php echo esc_html__('Write title or subject and click the ReGenerate button to get tips', 'seo-help'); ?></b></p>
            <img src="<?php echo qcld_linkbait_img_url.'/seo-suggest-2.png' ?>" class="img-thumbnail" alt="seo help">
            <p><b><?php echo esc_html__('Select the title you like and click the Copy Title button to copy the new title to clipboard. Paste it in the title area', 'seo-help'); ?></b></p>
            <img src="<?php echo qcld_linkbait_img_url.'/seo-suggest-3.png' ?>" class="img-thumbnail" alt="seo help">

            <br>
            <br>
          </div>
        </div>
       
        <div id="qcld_seo_tab-555">
          <div class="qc_seo_wrapper qcld-links-status">
            <h3><b><?php echo esc_html__('Your Guide To Error Codes', 'seo-help'); ?></b></h3>
            <p><b><?php echo esc_html__('300: Multiple Choices', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The URI refers to more than one file. The server may respond with an error message or a list of options.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('301: Moved Permanently', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The page has been permanently moved. The client will normally perform a redirection to the new URL. References to the old URL should be updated.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('302: Moved Temporarily', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The page has been temporarily moved. The client will normally perform a redirection to the new URL.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('303: See Other', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The server has received the request, and the response can be found using a GET request at another URI.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('305: Use Proxy', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The client should repeat the request using the proxy. Many HTTP clients do not correctly handle responses with this status code, primarily for security reasons.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('307: Temporary Redirect', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request should be repeated with another URI, but future requests should still use the original URI.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('308: Permanent Redirect', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request, and all future requests should be repeated using another URI.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('400: Bad Request', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request contains a syntax error and is denied.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('401: Unauthorized', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request header did not contain the authentication codes required for this resource, and access is denied.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('402: Payment Required', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('Reserved for future use. Intended to indicate that some form of payment is required before access is granted to this resource.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('403: Forbidden', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The client does not have the necessary permission to access the resource. Occasionally this response is issued when the server does not want any more visitors.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('404: Not Found', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The resource could not be found on the server. May be caused by misspelling a URI, or requesting a resource that has been deleted.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('405: Method Not Allowed', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The method used when requesting a resource, is not supported by that resource; for example, using GET on a form which requires POST access.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('406: Not Acceptable', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The requested resource exists but is not acceptable to the client according to the Accept headers sent in the request.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('407: Proxy Authentication Required', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The client must be authenticated with the proxy before making this request.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('408: Request Timeout', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The server timed out waiting for the client to complete the request.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('409: Conflict', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request could not be processed because of a conflict, such as an edit conflict, or too many concurrent requests.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('410: Gone', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The requested file is no longer available at this URI. The client should not request the resource again in the future.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('411: Length Required', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request did not include the required Content-Length header.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('412: Precondition Failed', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request contained a precondition specification which the server does not meet.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('413: Request Entity Too Large', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request contains more information than the server is willing or able to process.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('414: Request-URI Too Long', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The URI provided was too long for the server to process.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('415: Unsupported Media Type', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request has a file type which the server does not support.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('416: Requested Range Not Satisfiable', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The client requested a portion of the file, but the server cannot supply that portion. For example, the client may have asked for data that lies beyond the end of the file.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('417: Expectation Failed', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('An Expect request-header field cannot be satisfied by the server.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('420: Enhance Your Calm', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('Unofficial code returned by Twitter API when the client is being rate limited.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('422: Unprocessable Entity', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request was well-formed but was contained semantic errors which made it unprocessable.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('423: Locked', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The requested resource is locked.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('424: Failed Dependency', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request failed due to the failure of a previous request.', 'seo-help'); ?></p>
            
     
            <p><b><?php echo esc_html__('450: Blocked by Windows Parental Controls', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('A Microsoft extension indicating that Parental Controls are blocking access to the resource.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('500: Internal Server Error', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('A non-specified error occured when generating the response.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('501: Not Implemented', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The server cannot fulfill the request.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('502: Bad Gateway', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The gateway or proxy server received an error response from the upstream server.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('503: Service Unavailable', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The resource is currently unavailable.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('504: Gateway Timeout', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The gateway or proxy server timed out waiting for a response from the upstream server.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('505: HTTP Version Not Supported', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The server does not support the HTTP protocol version used in the request.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('506: Variant Also Negotiates', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('This results from a configuration error on the server.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('507: Insufficient Storage', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The server cannot store the information needed to complete the request.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('509: Bandwidth Limit Exceeded', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('Unofficial error code, indicating bandwidth allocation has been or will soon be exceeded.', 'seo-help'); ?></p>
            
            <p><b><?php echo esc_html__('510: Not Extended', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('The request contains a mandatory extension policy which is not accepted by the server.', 'seo-help'); ?></p>

            <p><b><?php echo esc_html__('999: Non-standard', 'seo-help'); ?></b></p>
            <p><?php echo esc_html__('This non-standard code is returned by some sites (e.g. linkedin) which do not permit scanning.', 'seo-help'); ?></p>

            <br>
            <br>
          </div>
        </div>

      </div>
         
    </div>
    <!-- /.wrap -->
  <?php
  } // end qcpromo_seo_help_promo
} // end qcpromo_seo_help_promo





/**
 * Save Options
 */
if ( ! function_exists( 'qcld_seo_help_action_buttons_save_options' ) ) {
  function qcld_seo_help_action_buttons_save_options(){

    // Check if the form is submitted or not

    if (isset($_POST['submit'])) {

      if (isset($_POST["qcld_external_links_in_new_windows_force"])) {
        $qcld_external_links_in_new_windows_force = sanitize_text_field(($_POST["qcld_external_links_in_new_windows_force"]));
        update_option('qcld_external_links_in_new_windows_force', $qcld_external_links_in_new_windows_force);
      }else{
        update_option('qcld_external_links_in_new_windows_force', '');
      }

      if (isset($_POST["qcld_disable_floating_icon"])) {
        $qcld_disable_floating_icon = sanitize_text_field(($_POST["qcld_disable_floating_icon"]));
        update_option('qcld_disable_floating_icon', $qcld_disable_floating_icon);
      }else{
        update_option('qcld_disable_floating_icon', '');
      }

    }

  }
}
add_action('init','qcld_seo_help_action_buttons_save_options');


if ( ! function_exists( 'qcld_seohelp_activation_redirect' ) ) {
  function qcld_seohelp_activation_redirect( $plugin ) {


    if (get_option('qcld_external_links_in_new_windows_force') == ''){
      update_option('qcld_external_links_in_new_windows_force', 'on');
    }

    if (get_option('qcld_seohelp_ai_engines') == ''){
      update_option('qcld_seohelp_ai_engines', 'gpt-4o');
    }

    qc_seo_help_project_create_tables();



    $screen = get_current_screen();

    if( ( isset( $screen->base ) && $screen->base == 'plugins' ) && $plugin == plugin_basename( __FILE__ ) ) {
    //if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=qcld_seo_bulk_content_generate') ) );
    }
      
  }
}
add_action( 'activated_plugin', 'qcld_seohelp_activation_redirect' );

// Loads the code for the website
if(get_option('qcld_external_links_in_new_windows_force') == 'on'){

  add_action('wp_head', 'qcld_seo_help_external_links_in_new_windows_client');
  if ( ! function_exists( 'qcld_seo_help_external_links_in_new_windows_client' ) ) {
    function qcld_seo_help_external_links_in_new_windows_client(){

      $blogdomain = parse_url(get_option('home'));  
      echo "<script type=\"text/javascript\">//<![CDATA[";
      echo "
      function qcld_external_links_in_new_windows_loop() {
        if (!document.links) {
          document.links = document.getElementsByTagName('a');
        }
        var change_link = false;
        var force = '';
        var ignore = '';

        for (var t=0; t<document.links.length; t++) {
          var all_links = document.links[t];
          change_link = false;
          
          if(document.links[t].hasAttribute('onClick') == false) {
            // forced if the address starts with http (or also https), but does not link to the current domain
            if(all_links.href.search(/^http/) != -1 && all_links.href.search('".$blogdomain['host']."') == -1) {
              // alert('Changeda '+all_links.href);
              change_link = true;
            }
              
            if(force != '' && all_links.href.search(force) != -1) {
              // forced
              // alert('force '+all_links.href);
              change_link = true;
            }
            
            if(ignore != '' && all_links.href.search(ignore) != -1) {
              // alert('ignore '+all_links.href);
              // ignored
              change_link = false;
            }

            if(change_link == true) {
              // alert('Changed '+all_links.href);
              document.links[t].setAttribute('onClick', 'javascript:window.open(\\''+all_links.href+'\\'); return false;');
              document.links[t].removeAttribute('target');
            }
          }
        }
      }
      
      // Load
      function qcld_external_links_in_new_windows_load(func)
      { 
        var oldonload = window.onload;
        if (typeof window.onload != 'function'){
          window.onload = func;
        } else {
          window.onload = function(){
            oldonload();
            func();
          }
        }
      }

      qcld_external_links_in_new_windows_load(qcld_external_links_in_new_windows_loop);
      ";

      echo "//]]></script>\n\n";
    }
  }

}


add_action( 'add_meta_boxes', 'qcld_seohelp_add_keyword_box' );
if ( ! function_exists( 'qcld_seohelp_add_keyword_box' ) ) {
    function qcld_seohelp_add_keyword_box() {
        $screens = array( 'post', 'page' );

        foreach ( $screens as $screen ) {
            add_meta_box(
                'qcld_seohelp_keyword_id',
                esc_html__('Keyword tag creation', 'seo-help' ),   
                'qcld_seohelp_keywordbox_html',  
                $screen,                           
                'side',
                'high' 
            );
        }

    }
}

if ( ! function_exists( 'qcld_seohelp_keywordbox_html' ) ) {
    function qcld_seohelp_keywordbox_html( $post ) {
        ?>
<div class="qcld-seohelp">
    <label for="qcld_keyword_suggestion" class="form-label"><?php esc_html_e('Keyword suggestion', 'seo-help'); ?></label><br>
            <input type="text" id="qcld_keyword_suggestion_text" class="form-control" /><br>
            <label for="qcld-seo-language" class="form-label"><?php esc_html_e('Country // Language', 'seo-help'); ?></label><br>
            <select value="" name="country" id="qcld-seo-language" class="form-select">
                    <optgroup label="North america">
                        <option value="us-en"><?php esc_html_e('United States', 'seo-help'); ?></option>
                        <option value="ca-en"><?php esc_html_e('Canada', 'seo-help'); ?></option>
                    </optgroup>
                    <optgroup label="Europe">
                        <option value="uk-en"><?php esc_html_e('United Kingdom', 'seo-help'); ?></option>
                        <option value="nl-nl"><?php esc_html_e('Netherlands', 'seo-help'); ?></option>
                        <option value="be-fr"><?php esc_html_e('Belgium (FR)', 'seo-help'); ?></option>
                        <option value="be-nl"><?php esc_html_e('Belgium (NL)', 'seo-help'); ?></option>
                        <option value="de-de"><?php esc_html_e('Germany', 'seo-help'); ?></option>
                        <option value="fr-fr"><?php esc_html_e('France', 'seo-help'); ?></option>
                        <option value="dk-dk"><?php esc_html_e('Denmark', 'seo-help'); ?></option>
                        <option value="ie-ie"><?php esc_html_e('Ireland', 'seo-help'); ?></option>
                        <option value="it-it"><?php esc_html_e('Italy', 'seo-help'); ?></option>   
                        <option value="es-es"><?php esc_html_e('Spain', 'seo-help'); ?></option>
                        <option value="pt-pt"><?php esc_html_e('Portugal', 'seo-help'); ?></option>
                    </optgroup> 
                    <optgroup label="Other">
                        <option value="au-en"><?php esc_html_e('Australia', 'seo-help'); ?></option>
                        <option value="nz-en"><?php esc_html_e('New Zealand (EN)', 'seo-help'); ?></option>
                    </optgroup>
            </select>
            <br>
    
    <button id="qcld_keyword_search" class="btn btn-info"><?php esc_html_e('Search', 'seo-help'); ?></button>

    <div class="modal fade" id="keywords_resultBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keywords_resultLabel"><?php esc_html_e('keywords Result', 'seo-help'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="postid" value="<?php esc_attr_e($post->ID, 'seo-help'); ?>"/>
            <div id="keywords_result">
             </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php esc_html_e('Close', 'seo-help'); ?></button>
                <a id="seo_help_createtag" class="btn btn-primary"><?php esc_html_e('Create tag', 'seo-help'); ?></a>
            </div>
            </div>
        </div>
    </div>
</div>
<?php 
    }
}



if ( ! function_exists( 'qc_open_ai_single_content_page' ) ) {
    function qc_open_ai_single_content_page() {

?>
<div class="qcld-seohelp-outer">
  <div class="wrap fs-section qcld-seo-help-custom">

      <div>

           <div class="qcld-seohelp qcld_seo_ai_single_content">
              <div class="qcld-seohelp-input">
                  <div class="qcld-seohelp-input-field">
                      <label for="qcld_article_keyword_suggestion" class="form-label"><?php esc_html_e('Prompt', 'seo-help'); ?></label><br>
                      <input type="text" id="qcld_article_keyword_suggestion_mf" class="form-control" data-press="qcld_article_keyword_suggestion" placeholder="<?php esc_html_e( "Write me a long article on how to make money online", "seo-help" ); ?>"><br>
                      <p><?php esc_html_e( "Ex: Write me a long article on how to make money online", "seo-help" ); ?></p>
                  </div>
              </div>
              <!-- <div class="qcld-seohelp-input">
                <div class="qcld_seo_pro_feature"><?php esc_html_e( 'Additional Pro Features', 'seo-help' ); ?></div>
              </div> -->
              <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                  <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">
                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_number_of_heading"><?php esc_html_e( "How many headings?", "seo-help" ); ?> </label>
                          <input type="number" placeholder="e.g. 5" id="qcld_article_number_of_heading" class="qcld_article_number_of_heading" name="qcld_article_number_of_heading" value="">
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_tag"><?php esc_html_e( "Heading Tag", "seo-help" ); ?> </label>
                          <select name="qcld_article_heading_tag" id="qcld_article_heading_tag">
                              <option value="h1"><?php esc_html_e( "h1", "seo-help" ); ?></option>
                              <option value="h2"><?php esc_html_e( "h2", "seo-help" ); ?></option>
                              <option value="h3"><?php esc_html_e( "h3", "seo-help" ); ?></option>
                              <option value="h4"><?php esc_html_e( "h4", "seo-help" ); ?></option>
                              <option value="h5"><?php esc_html_e( "h5", "seo-help" ); ?></option>
                              <option value="h6"><?php esc_html_e( "h6", "seo-help" ); ?></option>
                          </select>
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_style"><?php esc_html_e( "Writing Style", "seo-help" ); ?> </label>
                          <select name="qcld_article_heading_style" id="qcld_article_heading_style">
                              <option value="infor"><?php esc_html_e( "Informative", "seo-help" ); ?></option>
                              <option value="analy"><?php esc_html_e( "Analytical", "seo-help" ); ?></option>
                              <option value="argum"><?php esc_html_e( "Argumentative", "seo-help" ); ?></option>
                              <option value="creat"><?php esc_html_e( "Creative", "seo-help" ); ?></option>
                              <option value="criti"><?php esc_html_e( "Critical", "seo-help" ); ?></option>
                              <option value="descr"><?php esc_html_e( "Descriptive", "seo-help" ); ?></option>
                              <option value="evalu"><?php esc_html_e( "Evaluative", "seo-help" ); ?></option>
                              <option value="expos"><?php esc_html_e( "Expository", "seo-help" ); ?></option>
                              <option value="journ"><?php esc_html_e( "Journalistic", "seo-help" ); ?></option>
                              <option value="narra"><?php esc_html_e( "Narrative", "seo-help" ); ?></option>
                              <option value="persu"><?php esc_html_e( "Persuasive", "seo-help" ); ?></option>
                              <option value="refle"><?php esc_html_e( "Reflective", "seo-help" ); ?></option>
                              <option value="simpl"><?php esc_html_e( "Simple", "seo-help" ); ?></option>
                              <option value="techn"><?php esc_html_e( "Technical", "seo-help" ); ?></option>
                              <option value="repor"><?php esc_html_e( "Report", "seo-help" ); ?></option>
                              <option value="resea"><?php esc_html_e( "Research", "seo-help" ); ?></option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                  <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_tone"><?php esc_html_e( "Writing Tone", "seo-help" ); ?> </label>
                          <select name="qcld_article_heading_tone" id="qcld_article_heading_tone">
                              <option value="formal"><?php esc_html_e( "Formal", "seo-help" ); ?></option>
                              <option value="asser"><?php esc_html_e( "Assertive", "seo-help" ); ?></option>
                              <option value="cheer"><?php esc_html_e( "Cheerful", "seo-help" ); ?></option>
                              <option value="humor"><?php esc_html_e( "Humorous", "seo-help" ); ?></option>
                              <option value="informal"><?php esc_html_e( "Informal", "seo-help" ); ?></option>
                              <option value="inspi"><?php esc_html_e( "Inspirational", "seo-help" ); ?></option>
                              <option value="neutr"><?php esc_html_e( "Neutral", "seo-help" ); ?></option>
                              <option value="profe"><?php esc_html_e( "Professional", "seo-help" ); ?></option>
                              <option value="sarca"><?php esc_html_e( "Sarcastic", "seo-help" ); ?></option>
                              <option value="skept"><?php esc_html_e( "Skeptical", "seo-help" ); ?></option>
                              <option value="curio"><?php esc_html_e( "Curious", "seo-help" ); ?></option>
                              <option value="disap"><?php esc_html_e( "Disappointed", "seo-help" ); ?></option>
                              <option value="encou"><?php esc_html_e( "Encouraging", "seo-help" ); ?></option>
                              <option value="optim"><?php esc_html_e( "Optimistic", "seo-help" ); ?></option>
                              <option value="surpr"><?php esc_html_e( "Surprised", "seo-help" ); ?></option>
                              <option value="worry"><?php esc_html_e( "Worried", "seo-help" ); ?></option>

              
                          </select>
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_img_size" ><?php esc_html_e('Image Size', 'seo-help'); ?> </label>
                          <select name="qcld_article_img_size" id="qcld_article_img_size">
                              <!-- <option value="256x256"><?php esc_html_e( "256x256", "seo-help" ); ?> </option>
                              <option value="512x512"><?php esc_html_e( "512x512", "seo-help" ); ?> </option> -->
                            <option value="1024x1024"><?php esc_html_e( "1024x1024", "seo-help" ); ?> </option>
                            <option value="1792x1024"><?php esc_html_e('1792x1024', 'qcld-seo-help'); ?></option>
                            <option value="1024x1792"><?php esc_html_e('1024x1792', 'qcld-seo-help'); ?></option>
                          </select>
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_language"><?php esc_html_e( "Language", "seo-help" ); ?> </label>
                          <select name="qcld_article_language" id="qcld_article_language">
                              <option value="en"><?php esc_html_e( "English", "seo-help" ); ?> </option>
                              <option value="ar"><?php esc_html_e( "Arabic", "seo-help" ); ?> </option>
                              <option value="bg"><?php esc_html_e( "Bulgarian", "seo-help" ); ?> </option>
                              <option value="zh"><?php esc_html_e( "Chinese", "seo-help" ); ?> </option>
                              <option value="cs"><?php esc_html_e( "Czech", "seo-help" ); ?> </option>
                              <option value="nl"><?php esc_html_e( "Dutch", "seo-help" ); ?> </option>
                              <option value="fr"> <?php esc_html_e( "French", "seo-help" ); ?> </option>
                              <option value="de"> <?php esc_html_e( "German", "seo-help" ); ?> </option>
                              <option value="el"> <?php esc_html_e( "Greek", "seo-help" ); ?> </option>
                              <option value="hi"> <?php esc_html_e( "Hindi", "seo-help" ); ?> </option>
                              <option value="hu"> <?php esc_html_e( "Hungarian", "seo-help" ); ?> </option>
                              <option value="id"> <?php esc_html_e( "Indonesian", "seo-help" ); ?> </option>
                              <option value="it"> <?php esc_html_e( "Italian", "seo-help" ); ?> </option>
                              <option value="ja"> <?php esc_html_e( "Japanese", "seo-help" ); ?> </option>
                              <option value="ko"> <?php esc_html_e( "Korean", "seo-help" ); ?> </option>
                              <option value="pl"> <?php esc_html_e( "Polish", "seo-help" ); ?> </option>
                              <option value="pt"> <?php esc_html_e( "Portuguese", "seo-help" ); ?> </option>
                              <option value="ro"> <?php esc_html_e( "Romanian", "seo-help" ); ?> </option>
                              <option value="ru"> <?php esc_html_e( "Russian", "seo-help" ); ?> </option>
                              <option value="es"> <?php esc_html_e( "Spanish", "seo-help" ); ?> </option>
                              <option value="sv"> <?php esc_html_e( "Swedish", "seo-help" ); ?> </option>
                              <option value="tr"> <?php esc_html_e( "Turkish", "seo-help" ); ?> </option>
                              <option value="uk"> <?php esc_html_e( "Ukranian", "seo-help" ); ?> </option>
                          </select>
                      </div>


                  </div>
              </div>
              <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                  <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_label_anchor_text"><?php esc_html_e( "Anchor Text", "seo-help" ); ?> </label>
                          <input type="text" id="qcld_article_label_anchor_text" placeholder="e.g. battery life" class="qcld_article_label_anchor_text" name="qcld_article_label_anchor_text" >
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_target_url"><?php esc_html_e( "Target URL", "seo-help" ); ?> </label>
                          <input type="url" id="qcld_article_target_url" placeholder="https://..." class="qcld_article_target_url" name="qcld_article_target_url">
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_target_label_cta"><?php esc_html_e( "Add Call-to-Action", "seo-help" ); ?> </label>
                          <input type="url" id="qcld_article_target_label_cta" placeholder="https://..." class="qcld_article_target_label_cta" name="qcld_article_target_label_cta">
                      </div>

                  </div>
              </div>
              <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                  <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">


                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_cta_pos"><?php esc_html_e( "Call-to-Action Position", "seo-help" ); ?> </label>
                          <select name="qcld_article_cta_pos" id="qcld_article_cta_pos">
                              <option value="beg"><?php esc_html_e( "Beginning", "seo-help" ); ?></option>
                              <option value="end"><?php esc_html_e( "End", "seo-help" ); ?></option>
                          </select>
                          <p><i><?php esc_html_e( "Use Call-to-Action Position", "seo-help" ); ?></i></p>
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_label_keywords"><?php esc_html_e( "Add Keywords", "seo-help" ); ?> </label>
                          <input type="text" id="qcld_article_label_keywords" placeholder="Write Keywords..." class="qcld_article_label_keywords" name="qcld_article_label_keywords">
                          <p><i><?php esc_html_e( "Use comma to seperate keywords", "seo-help" ); ?></i></p>
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_label_word_to_avoid"><?php esc_html_e( "Keywords to Avoid", "seo-help" ); ?> </label>
                          <input type="text" id="qcld_article_label_word_to_avoid" placeholder="Write Keywords..." class="qcld_article_label_word_to_avoid" name="qcld_article_label_word_to_avoid" value="">
                          <p><i><?php esc_html_e( "Use comma to seperate keywords", "seo-help" ); ?></i></p>
                      </div>


                  </div>
              </div>
              <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                  <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_label_keywords_bold"><?php esc_html_e( "Make Keywords Bold", "seo-help" ); ?> </label>
                          <input type="checkbox" id="qcld_article_label_keywords_bold" class="qcld_article_label_keywords_bold" name="qcld_article_label_keywords_bold" value="1">
                      </div>
                    
                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_img"><?php esc_html_e( "Add Image", "seo-help" ); ?> </label>
                          <input type="checkbox" name="qcld_article_heading_img" id="qcld_article_heading_img" class="qcld_article_heading_img" value="1"/>
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_tagline"><?php esc_html_e( "Add Tagline", "seo-help" ); ?> </label>
                          <input type="checkbox" id="qcld_article_heading_tagline"  name="qcld_article_heading_tagline" class="qcld_article_heading_tagline" value="1" />
                      </div>


                  </div>
              </div>
              <div class="qcld-seohelp-input qcld_seo_pro_feature_content">
                  <div class="qcld-seohelp-input-field qcld-seohelp-input-field_ai_wrap">

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_intro"><?php esc_html_e( "Add Introduction", "seo-help" ); ?> </label>
                          <input type="checkbox" id="qcld_article_heading_intro" name="qcld_article_heading_intro" class="qcld_article_heading_intro" value="1"/>
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_conclusion"><?php esc_html_e( "Add Conclusion", "seo-help" ); ?> </label>
                          <input type="checkbox" id="qcld_article_heading_conclusion" name="qcld_article_heading_conclusion" class="qcld_article_heading_conclusion" value="1" />
                      </div>

                      <div class="qcld_seohelp_ai_con">
                          <label for="qcld_article_heading_faq"><?php esc_html_e( "Add Faq", "seo-help" ); ?> </label>
                          <input type="checkbox" id="qcld_article_heading_faq" name="qcld_article_heading_faq" class="qcld_article_heading_faq" value="1" />
                      </div>


                  </div>
              </div>
              <button id="qcld_article_keyword_suggestion" class="btn btn-info"><?php esc_html_e('Generate', 'seo-help'); ?></button>
              <p style="color:red;"><b><?php esc_html_e('(Please'); ?> <a href="<?php echo esc_url('https://platform.openai.com/settings/organization/billing/'); ?>" target="_blank"><?php esc_html_e('Pre-purchase credit'); ?></a> <?php esc_html_e('from OpenAI API platform and increase the API usage limit. Otherwise, AI features will not work)'); ?></b></p>
              <hr/>
              <div class="linkbait_single_field"> 
                  <div id="linkbait_article_keyword_data">
                  </div>

                  <div class="qcld_seo-playground-buttons">
                      <div class="qcld-seohelp-input">
                        <div class="qcld_seo_pro_feature"><?php esc_html_e( 'Pro Feature', 'seo-help' ); ?></div>
                      </div>
                      <button class="button button-primary qcld_article_playground_save" disabled><?php esc_html_e("Save as Draft", 'qcld-seo-help'); ?></button>
                      <button class="button qcld_article_playground_clear" disabled><?php esc_html_e("Clear", 'qcld-seo-help'); ?></button>
                      
                  </div>
              </div>
          </div>
         
          <div class="qcld_seo_lds-ellipsis" style="display: none;">
              <div class="qcld_seo-generating-title">Generating content..</div>
              <div class="qcld_seo-generating-process"></div>
              <div class="qcld_seo-timer"></div>
          </div>

        
      </div>
  </div>
</div>

<?php 

    }
}



if ( ! function_exists( 'qc_open_ai_img_generator_page' ) ) {
    function qc_open_ai_img_generator_page() {

?>

<style>
.qcld_seo_image-box {
    display: flex;
    flex-wrap: wrap;
}

.qcld_seo_image-list {
    margin: 10px;
    width: 256;
    height: 256;
    background-size: cover;
    box-shadow: 0px 0px 10px #ccc;
}

.select-element {
    margin: 10px;
}

.qcld_seo_btn {
    background-color: #007cba;
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.qcld_seo_btn:hover {
    background-color: #046b9f;
    box-shadow: 0px 0px 10px #046b9f;
    transform: translateY(-2px);
}


.qcld_seo-single-content-form textarea {
  width: 100%;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #2271b1;
  border-radius: 4px;
  background-color: #f8f8f8;
  resize: none;
}
.qcld_seo_content_form{
  width:100%;

}

.qcld_seo-content-image-settings {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: row;
    flex-wrap: wrap;
}

.qcld_seo-content-image-settings > div {
  width: 15%;
}
.qcld_seo-single-content-form label,
.qcld_seo-content-image-settings > div label{
  display: block;    
  font-weight: bold;
  margin-top: 10px;
  margin-bottom: 5px;
}
.qcld_seo-content-image-settings > div select{
  width:180px;
}

</style>
   <div class="wrap qcld-seo-help-custom">
      <h3><?php esc_html_e('Image Generator', 'qcld-seo-help'); ?></h3>
      <div class="qcld_seo_content_form" id="qcld_seo-post-form">

          <div class="qcld_seo_content_form_box">
              <div class="qcld_seo-content-image-settings">
                  <div class="mb-5">
                      <label for="artist" class="qcld_seo-form-label"><?php esc_html_e('Artist:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="artist" id="artist">
                          <option value="Salvador Dalí" selected=""><?php esc_html_e('Salvador Dalí', 'qcld-seo-help'); ?></option>
                          <option value="Leonardo da Vinci"><?php esc_html_e('Leonardo da Vinci', 'qcld-seo-help'); ?></option>
                          <option value="Michelangelo"><?php esc_html_e('Michelangelo', 'qcld-seo-help'); ?></option>
                          <option value="Rembrandt"><?php esc_html_e('Rembrandt', 'qcld-seo-help'); ?></option>
                          <option value="Van Gogh"><?php esc_html_e('Van Gogh', 'qcld-seo-help'); ?></option>
                          <option value="Monet"><?php esc_html_e('Monet', 'qcld-seo-help'); ?></option>
                          <option value="Vermeer"><?php esc_html_e('Vermeer', 'qcld-seo-help'); ?></option>
                          <option value="Johannes Vermeer"><?php esc_html_e('Johannes Vermeer', 'qcld-seo-help'); ?></option>
                          <option value="Raphael"><?php esc_html_e('Raphael', 'qcld-seo-help'); ?></option>
                          <option value="Titian"><?php esc_html_e('Titian', 'qcld-seo-help'); ?></option>
                          <option value="Degas"><?php esc_html_e('Degas', 'qcld-seo-help'); ?></option>
                          <option value="Edgar Degas"><?php esc_html_e('Edgar Degas', 'qcld-seo-help'); ?></option>
                          <option value="El Greco"><?php esc_html_e('El Greco', 'qcld-seo-help'); ?></option>
                          <option value="Cézanne"><?php esc_html_e('Cézanne', 'qcld-seo-help'); ?></option>
                          <option value="Paul Cézanne"><?php esc_html_e('Paul Cézanne', 'qcld-seo-help'); ?></option>
                          <option value="Caravaggio"><?php esc_html_e('Caravaggio', 'qcld-seo-help'); ?></option>
                          <option value="Gustav Klimt"><?php esc_html_e('Gustav Klimt', 'qcld-seo-help'); ?></option>
                          <option value="Henri Matisse"><?php esc_html_e('Henri Matisse', 'qcld-seo-help'); ?></option>
                          <option value="Pablo Picasso"><?php esc_html_e('Pablo Picasso', 'qcld-seo-help'); ?></option>
                          <option value="Diego Velázquez"><?php esc_html_e('Diego Velázquez', 'qcld-seo-help'); ?></option>
                          <option value="Sandro Botticelli"><?php esc_html_e('Sandro Botticelli', 'qcld-seo-help'); ?></option>
                          <option value="Jan van Eyck"><?php esc_html_e('Jan van Eyck', 'qcld-seo-help'); ?></option>
                          <option value="Albrecht Dürer"><?php esc_html_e('Albrecht Dürer', 'qcld-seo-help'); ?></option>
                          <option value="Canaletto"><?php esc_html_e('Canaletto', 'qcld-seo-help'); ?></option>
                          <option value="Frida Kahlo"><?php esc_html_e('Frida Kahlo', 'qcld-seo-help'); ?></option>
                          <option value="Eugene Delacroix"><?php esc_html_e('Eugene Delacroix', 'qcld-seo-help'); ?></option>
                          <option value="Gustav Courbet"><?php esc_html_e('Gustav Courbet', 'qcld-seo-help'); ?></option>
                          <option value="John Singer Sargent"><?php esc_html_e('John Singer Sargent', 'qcld-seo-help'); ?></option>
                          <option value="Georges Seurat"><?php esc_html_e('Georges Seurat', 'qcld-seo-help'); ?></option>
                          <option value="Alfred Sisley"><?php esc_html_e('Alfred Sisley', 'qcld-seo-help'); ?></option>
                          <option value="Pierre-Auguste Renoir"><?php esc_html_e('Pierre-Auguste Renoir', 'qcld-seo-help'); ?></option>
                          <option value="Tintoretto"><?php esc_html_e('Tintoretto', 'qcld-seo-help'); ?></option>
                          <option value="Frederic Edwin Church"><?php esc_html_e('Frederic Edwin Church', 'qcld-seo-help'); ?></option>
                          <option value="John Everett Millais"><?php esc_html_e('John Everett Millais', 'qcld-seo-help'); ?></option>
                          <option value="JMW Turner"><?php esc_html_e('JMW Turner', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="art_style" class="qcld_seo-form-label"><?php esc_html_e('Style:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="art_style" id="art_style">
                          <option value="Surrealism" selected=""><?php esc_html_e('Surrealism', 'qcld-seo-help'); ?></option>
                          <option value="Early Renaissance"><?php esc_html_e('Early Renaissance', 'qcld-seo-help'); ?></option>
                          <option value="Abstract"><?php esc_html_e('Abstract', 'qcld-seo-help'); ?></option>
                          <option value="Abstract Expressionism"><?php esc_html_e('Abstract Expressionism', 'qcld-seo-help'); ?></option>
                          <option value="Action Painting"><?php esc_html_e('Action Painting', 'qcld-seo-help'); ?></option>
                          <option value="Art Deco"><?php esc_html_e('Art Deco', 'qcld-seo-help'); ?></option>
                          <option value="Art Nouveau"><?php esc_html_e('Art Nouveau', 'qcld-seo-help'); ?></option>
                          <option value="Baroque"><?php esc_html_e('Baroque', 'qcld-seo-help'); ?></option>
                          <option value="Cubism"><?php esc_html_e('Cubism', 'qcld-seo-help'); ?></option>
                          <option value="Digital Art"><?php esc_html_e('Digital Art', 'qcld-seo-help'); ?></option>
                          <option value="Expressionism"><?php esc_html_e('Expressionism', 'qcld-seo-help'); ?></option>
                          <option value="Fauvism"><?php esc_html_e('Fauvism', 'qcld-seo-help'); ?></option>
                          <option value="High Renaissance"><?php esc_html_e('High Renaissance', 'qcld-seo-help'); ?></option>
                          <option value="Impressionism"><?php esc_html_e('Impressionism', 'qcld-seo-help'); ?></option>
                          <option value="Mannerism"><?php esc_html_e('Mannerism', 'qcld-seo-help'); ?></option>
                          <option value="Minimalism"><?php esc_html_e('Minimalism', 'qcld-seo-help'); ?></option>
                          <option value="Naïve Art"><?php esc_html_e('Naïve Art', 'qcld-seo-help'); ?></option>
                          <option value="Northern Renaissance"><?php esc_html_e('Northern Renaissance', 'qcld-seo-help'); ?></option>
                          <option value="Pop Art"><?php esc_html_e('Pop Art', 'qcld-seo-help'); ?></option>
                          <option value="Post-Impressionism"><?php esc_html_e('Post-Impressionism', 'qcld-seo-help'); ?></option>
                          <option value="Realism"><?php esc_html_e('Realism', 'qcld-seo-help'); ?></option>
                          <option value="Rococo"><?php esc_html_e('Rococo', 'qcld-seo-help'); ?></option>
                          <option value="Romanticism"><?php esc_html_e('Romanticism', 'qcld-seo-help'); ?></option>
                          <option value="Symbolism"><?php esc_html_e('Symbolism', 'qcld-seo-help'); ?></option>
                          <option value="Ukiyo-e"><?php esc_html_e('Ukiyo-e', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="photography_style" class="qcld_seo-form-label"><?php esc_html_e('Photography:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="photography_style" id="photography_style">
                          <option value="Portrait" selected=""><?php esc_html_e('Portrait', 'qcld-seo-help'); ?></option>
                          <option value="Landscape"><?php esc_html_e('Landscape', 'qcld-seo-help'); ?></option>
                          <option value="Street"><?php esc_html_e('Street', 'qcld-seo-help'); ?></option>
                          <option value="Macro"><?php esc_html_e('Macro', 'qcld-seo-help'); ?></option>
                          <option value="Abstract"><?php esc_html_e('Abstract', 'qcld-seo-help'); ?></option>
                          <option value="Fine art"><?php esc_html_e('Fine art', 'qcld-seo-help'); ?></option>
                          <option value="Black and white"><?php esc_html_e('Black and white', 'qcld-seo-help'); ?></option>
                          <option value="Night"><?php esc_html_e('Night', 'qcld-seo-help'); ?></option>
                          <option value="Sports"><?php esc_html_e('Sports', 'qcld-seo-help'); ?></option>
                          <option value="Fashion"><?php esc_html_e('Fashion', 'qcld-seo-help'); ?></option>
                          <option value="Wildlife"><?php esc_html_e('Wildlife', 'qcld-seo-help'); ?></option>
                          <option value="Nature"><?php esc_html_e('Nature', 'qcld-seo-help'); ?></option>
                          <option value="Travel"><?php esc_html_e('Travel', 'qcld-seo-help'); ?></option>
                          <option value="Documentary"><?php esc_html_e('Documentary', 'qcld-seo-help'); ?></option>
                          <option value="Food"><?php esc_html_e('Food', 'qcld-seo-help'); ?></option>
                          <option value="Architecture"><?php esc_html_e('Architecture', 'qcld-seo-help'); ?></option>
                          <option value="Industrial"><?php esc_html_e('Industrial', 'qcld-seo-help'); ?></option>
                          <option value="Conceptual"><?php esc_html_e('Conceptual', 'qcld-seo-help'); ?></option>
                          <option value="Candid"><?php esc_html_e('Candid', 'qcld-seo-help'); ?></option>
                          <option value="Underwater"><?php esc_html_e('Underwater', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="lighting" class="qcld_seo-form-label"><?php esc_html_e('Lighting:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="lighting" id="lighting">
                          <option value="Ambient" selected><?php esc_html_e('Ambient', 'qcld-seo-help'); ?></option>
                          <option value="Artificial light"><?php esc_html_e('Artificial light', 'qcld-seo-help'); ?></option>
                          <option value="Backlight"><?php esc_html_e('Backlight', 'qcld-seo-help'); ?></option>
                          <option value="Black light"><?php esc_html_e('Black light', 'qcld-seo-help'); ?></option>
                          <option value="Blue hour"><?php esc_html_e('Blue hour', 'qcld-seo-help'); ?></option>
                          <option value="Candle light"><?php esc_html_e('Candle light', 'qcld-seo-help'); ?></option>
                          <option value="Chiaroscuro"><?php esc_html_e('Chiaroscuro', 'qcld-seo-help'); ?></option>
                          <option value="Cloudy"><?php esc_html_e('Cloudy', 'qcld-seo-help'); ?></option>
                          <option value="Color gels"><?php esc_html_e('Color gels', 'qcld-seo-help'); ?></option>
                          <option value="Continuous light"><?php esc_html_e('Continuous light', 'qcld-seo-help'); ?></option>
                          <option value="Contre-jour"><?php esc_html_e('Contre-jour', 'qcld-seo-help'); ?></option>
                          <option value="Direct light"><?php esc_html_e('Direct light', 'qcld-seo-help'); ?></option>
                          <option value="Direct sunlight"><?php esc_html_e('Direct sunlight', 'qcld-seo-help'); ?></option>
                          <option value="Diffused light"><?php esc_html_e('Diffused light', 'qcld-seo-help'); ?></option>
                          <option value="Firelight"><?php esc_html_e('Firelight', 'qcld-seo-help'); ?></option>
                          <option value="Flash"><?php esc_html_e('Flash', 'qcld-seo-help'); ?></option>
                          <option value="Flat light"><?php esc_html_e('Flat light', 'qcld-seo-help'); ?></option>
                          <option value="Fluorescent"><?php esc_html_e('Fluorescent', 'qcld-seo-help'); ?></option>
                          <option value="Fog"><?php esc_html_e('Fog', 'qcld-seo-help'); ?></option>
                          <option value="Front light"><?php esc_html_e('Front light', 'qcld-seo-help'); ?></option>
                          <option value="Golden hour"><?php esc_html_e('Golden hour', 'qcld-seo-help'); ?></option>
                          <option value="Hard light"><?php esc_html_e('Hard light', 'qcld-seo-help'); ?></option>
                          <option value="Soft light"><?php esc_html_e('Soft light', 'qcld-seo-help'); ?></option>
                          <option value="Rim light"><?php esc_html_e('Rim light', 'qcld-seo-help'); ?></option>
                          <option value="Backlight"><?php esc_html_e('Backlight', 'qcld-seo-help'); ?></option>
                          <option value="Silhouette"><?php esc_html_e('Silhouette', 'qcld-seo-help'); ?></option>
                          <option value="Natural light"><?php esc_html_e('Natural light', 'qcld-seo-help'); ?></option>
                          <option value="Studio light"><?php esc_html_e('Studio light', 'qcld-seo-help'); ?></option>
                          <option value="Flash"><?php esc_html_e('Flash', 'qcld-seo-help'); ?></option>
                          <option value="Continuous light"><?php esc_html_e('Continuous light', 'qcld-seo-help'); ?></option>
                          <option value="High key"><?php esc_html_e('High key', 'qcld-seo-help'); ?></option>
                          <option value="Low key"><?php esc_html_e('Low key', 'qcld-seo-help'); ?></option>
                          <option value="Golden hour"><?php esc_html_e('Golden hour', 'qcld-seo-help'); ?></option>
                          <option value="Blue hour"><?php esc_html_e('Blue hour', 'qcld-seo-help'); ?></option>
                          <option value="Diffused light"><?php esc_html_e('Diffused light', 'qcld-seo-help'); ?></option>
                          <option value="Reflected light"><?php esc_html_e('Reflected light', 'qcld-seo-help'); ?></option>
                          <option value="Shaded light"><?php esc_html_e('Shaded light', 'qcld-seo-help'); ?></option>
                          <option value="Side light"><?php esc_html_e('Side light', 'qcld-seo-help'); ?></option>
                          <option value="Direct light"><?php esc_html_e('Direct light', 'qcld-seo-help'); ?></option>
                          <option value="Artificial light"><?php esc_html_e('Artificial light', 'qcld-seo-help'); ?></option>
                          <option value="Moonlight"><?php esc_html_e('Moonlight', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>

                  </div>
                  <div class="mb-5">
                      <label for="subject" class="qcld_seo-form-label"><?php esc_html_e('Subject:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="subject" id="subject">
                          <option value="Landscapes" selected><?php esc_html_e('Landscapes', 'qcld-seo-help'); ?></option>
                          <option value="People"><?php esc_html_e('People', 'qcld-seo-help'); ?></option>
                          <option value="Animals"><?php esc_html_e('Animals', 'qcld-seo-help'); ?></option>
                          <option value="Food"><?php esc_html_e('Food', 'qcld-seo-help'); ?></option>
                          <option value="Cars"><?php esc_html_e('Cars', 'qcld-seo-help'); ?></option>
                          <option value="Architecture"><?php esc_html_e('Architecture', 'qcld-seo-help'); ?></option>
                          <option value="Flowers"><?php esc_html_e('Flowers', 'qcld-seo-help'); ?></option>
                          <option value="Still life"><?php esc_html_e('Still life', 'qcld-seo-help'); ?></option>
                          <option value="Portrait"><?php esc_html_e('Portrait', 'qcld-seo-help'); ?></option>
                          <option value="Cityscapes"><?php esc_html_e('Cityscapes', 'qcld-seo-help'); ?></option>
                          <option value="Seascapes"><?php esc_html_e('Seascapes', 'qcld-seo-help'); ?></option>
                          <option value="Nature"><?php esc_html_e('Nature', 'qcld-seo-help'); ?></option>
                          <option value="Action"><?php esc_html_e('Action', 'qcld-seo-help'); ?></option>
                          <option value="Events"><?php esc_html_e('Events', 'qcld-seo-help'); ?></option>
                          <option value="Street"><?php esc_html_e('Street', 'qcld-seo-help'); ?></option>
                          <option value="Abstract"><?php esc_html_e('Abstract', 'qcld-seo-help'); ?></option>
                          <option value="Candid"><?php esc_html_e('Candid', 'qcld-seo-help'); ?></option>
                          <option value="Underwater"><?php esc_html_e('Underwater', 'qcld-seo-help'); ?></option>
                          <option value="Night"><?php esc_html_e('Night', 'qcld-seo-help'); ?></option>
                          <option value="Wildlife"><?php esc_html_e('Wildlife', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="camera_settings" class="qcld_seo-form-label"><?php esc_html_e('Camera:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="camera_settings" id="camera_settings">
                          <option value="Aperture" selected><?php esc_html_e('Aperture', 'qcld-seo-help'); ?></option>
                          <option value="Shutter speed"><?php esc_html_e('Shutter speed', 'qcld-seo-help'); ?></option>
                          <option value="ISO"><?php esc_html_e('ISO', 'qcld-seo-help'); ?></option>
                          <option value="White balance"><?php esc_html_e('White balance', 'qcld-seo-help'); ?></option>
                          <option value="Exposure compensation"><?php esc_html_e('Exposure compensation', 'qcld-seo-help'); ?></option>
                          <option value="Focus mode"><?php esc_html_e('Focus mode', 'qcld-seo-help'); ?></option>
                          <option value="Metering mode"><?php esc_html_e('Metering mode', 'qcld-seo-help'); ?></option>
                          <option value="Drive mode"><?php esc_html_e('Drive mode', 'qcld-seo-help'); ?></option>
                          <option value="Image stabilization"><?php esc_html_e('Image stabilization', 'qcld-seo-help'); ?></option>
                          <option value="Auto-Focus point"><?php esc_html_e('Auto-Focus point', 'qcld-seo-help'); ?></option>
                          <option value="Flash mode"><?php esc_html_e('Flash mode', 'qcld-seo-help'); ?></option>
                          <option value="Flash compensation"><?php esc_html_e('Flash compensation', 'qcld-seo-help'); ?></option>
                          <option value="Picture style/picture control"><?php esc_html_e('Picture style/picture control', 'qcld-seo-help'); ?></option>
                          <option value="Long exposure"><?php esc_html_e('Long exposure', 'qcld-seo-help'); ?></option>
                          <option value="High-speed sync"><?php esc_html_e('High-speed sync', 'qcld-seo-help'); ?></option>
                          <option value="Mirror lock-up"><?php esc_html_e('Mirror lock-up', 'qcld-seo-help'); ?></option>
                          <option value="Bracketing"><?php esc_html_e('Bracketing', 'qcld-seo-help'); ?></option>
                          <option value="Noise reduction"><?php esc_html_e('Noise reduction', 'qcld-seo-help'); ?></option>
                          <option value="Image format"><?php esc_html_e('Image format', 'qcld-seo-help'); ?></option>
                          <option value="Time-lapse"><?php esc_html_e('Time-lapse', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="composition" class="qcld_seo-form-label"><?php esc_html_e('Composition:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="composition" id="composition">
                          <option value="Rule of thirds" selected><?php esc_html_e('Rule of thirds', 'qcld-seo-help'); ?></option>
                          <option value="Symmetry"><?php esc_html_e('Symmetry', 'qcld-seo-help'); ?></option>
                          <option value="Leading lines"><?php esc_html_e('Leading lines', 'qcld-seo-help'); ?></option>
                          <option value="Negative space"><?php esc_html_e('Negative space', 'qcld-seo-help'); ?></option>
                          <option value="Frame within a frame"><?php esc_html_e('Frame within a frame', 'qcld-seo-help'); ?></option>
                          <option value="Diagonal lines"><?php esc_html_e('Diagonal lines', 'qcld-seo-help'); ?></option>
                          <option value="Triangles"><?php esc_html_e('Triangles', 'qcld-seo-help'); ?></option>
                          <option value="S-curves"><?php esc_html_e('S-curves', 'qcld-seo-help'); ?></option>
                          <option value="Golden ratio"><?php esc_html_e('Golden ratio', 'qcld-seo-help'); ?></option>
                          <option value="Radial balance"><?php esc_html_e('Radial balance', 'qcld-seo-help'); ?></option>
                          <option value="Contrast"><?php esc_html_e('Contrast', 'qcld-seo-help'); ?></option>
                          <option value="Repetition"><?php esc_html_e('Repetition', 'qcld-seo-help'); ?></option>
                          <option value="Simplicity"><?php esc_html_e('Simplicity', 'qcld-seo-help'); ?></option>
                          <option value="Viewpoint"><?php esc_html_e('Viewpoint', 'qcld-seo-help'); ?></option>
                          <option value="Foreground, middle ground, background"><?php esc_html_e('Foreground, middle ground, background', 'qcld-seo-help'); ?></option>
                          <option value="Patterns"><?php esc_html_e('Patterns', 'qcld-seo-help'); ?></option>
                          <option value="Texture"><?php esc_html_e('Texture', 'qcld-seo-help'); ?></option>
                          <option value="Balance"><?php esc_html_e('Balance', 'qcld-seo-help'); ?></option>
                          <option value="Color theory"><?php esc_html_e('Color theory', 'qcld-seo-help'); ?></option>
                          <option value="Proportion"><?php esc_html_e('Proportion', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="resolution" class="qcld_seo-form-label"><?php esc_html_e('Resolution:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="resolution" id="resolution">
                          <option value="4K (3840x2160)" selected><?php esc_html_e('4K (3840x2160)', 'qcld-seo-help'); ?></option>
                          <option value="1080p (1920x1080)"><?php esc_html_e('1080p (1920x1080)', 'qcld-seo-help'); ?></option>
                          <option value="720p (1280x720)"><?php esc_html_e('720p (1280x720)', 'qcld-seo-help'); ?></option>
                          <option value="480p (854x480)"><?php esc_html_e('480p (854x480)', 'qcld-seo-help'); ?></option>
                          <option value="2K (2560x1440)"><?php esc_html_e('2K (2560x1440)', 'qcld-seo-help'); ?></option>
                          <option value="1080i (1920x1080)"><?php esc_html_e('1080i (1920x1080)', 'qcld-seo-help'); ?></option>
                          <option value="720i (1280x720)"><?php esc_html_e('720i (1280x720)', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="color" class="qcld_seo-form-label"><?php esc_html_e('Color:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="color" id="color">
                          <option value="RGB" selected><?php esc_html_e('RGB', 'qcld-seo-help'); ?></option>
                          <option value="CMYK"><?php esc_html_e('CMYK', 'qcld-seo-help'); ?></option>
                          <option value="Grayscale"><?php esc_html_e('Grayscale', 'qcld-seo-help'); ?></option>
                          <option value="HEX"><?php esc_html_e('HEX', 'qcld-seo-help'); ?></option>
                          <option value="Pantone"><?php esc_html_e('Pantone', 'qcld-seo-help'); ?></option>
                          <option value="CMY"><?php esc_html_e('CMY', 'qcld-seo-help'); ?></option>
                          <option value="HSL"><?php esc_html_e('HSL', 'qcld-seo-help'); ?></option>
                          <option value="HSV"><?php esc_html_e('HSV', 'qcld-seo-help'); ?></option>
                          <option value="LAB"><?php esc_html_e('LAB', 'qcld-seo-help'); ?></option>
                          <option value="LCH"><?php esc_html_e('LCH', 'qcld-seo-help'); ?></option>
                          <option value="LUV"><?php esc_html_e('LUV', 'qcld-seo-help'); ?></option>
                          <option value="XYZ"><?php esc_html_e('XYZ', 'qcld-seo-help'); ?></option>
                          <option value="YUV"><?php esc_html_e('YUV', 'qcld-seo-help'); ?></option>
                          <option value="YIQ"><?php esc_html_e('YIQ', 'qcld-seo-help'); ?></option>
                          <option value="YCbCr"><?php esc_html_e('YCbCr', 'qcld-seo-help'); ?></option>
                          <option value="YPbPr"><?php esc_html_e('YPbPr', 'qcld-seo-help'); ?></option>
                          <option value="YDbDr"><?php esc_html_e('YDbDr', 'qcld-seo-help'); ?></option>
                          <option value="YCoCg"><?php esc_html_e('YCoCg', 'qcld-seo-help'); ?></option>
                          <option value="YCgCo"><?php esc_html_e('YCgCo', 'qcld-seo-help'); ?></option>
                          <option value="YCC"><?php esc_html_e('YCC', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="special_effects" class="qcld_seo-form-label"><?php esc_html_e('Special Effects:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="special_effects" id="special_effects">
                          <option value="Cinemagraph" selected><?php esc_html_e('Cinemagraph', 'qcld-seo-help'); ?></option>
                          <option value="Bokeh"><?php esc_html_e('Bokeh', 'qcld-seo-help'); ?></option>
                          <option value="Panorama"><?php esc_html_e('Panorama', 'qcld-seo-help'); ?></option>
                          <option value="HDR"><?php esc_html_e('HDR', 'qcld-seo-help'); ?></option>
                          <option value="Long exposure"><?php esc_html_e('Long exposure', 'qcld-seo-help'); ?></option>
                          <option value="Timelapse"><?php esc_html_e('Timelapse', 'qcld-seo-help'); ?></option>
                          <option value="Slow motion"><?php esc_html_e('Slow motion', 'qcld-seo-help'); ?></option>
                          <option value="Stop-motion"><?php esc_html_e('Stop-motion', 'qcld-seo-help'); ?></option>
                          <option value="Tilt-shift"><?php esc_html_e('Tilt-shift', 'qcld-seo-help'); ?></option>
                          <option value="Zoom blur"><?php esc_html_e('Zoom blur', 'qcld-seo-help'); ?></option>
                          <option value="Motion blur"><?php esc_html_e('Motion blur', 'qcld-seo-help'); ?></option>
                          <option value="Lens flare"><?php esc_html_e('Lens flare', 'qcld-seo-help'); ?></option>
                          <option value="Sunburst"><?php esc_html_e('Sunburst', 'qcld-seo-help'); ?></option>
                          <option value="Starburst"><?php esc_html_e('Starburst', 'qcld-seo-help'); ?></option>
                          <option value="Double exposure"><?php esc_html_e('Double exposure', 'qcld-seo-help'); ?></option>
                          <option value="Cross processing"><?php esc_html_e('Cross processing', 'qcld-seo-help'); ?></option>
                          <option value="Fish-eye"><?php esc_html_e('Fish-eye', 'qcld-seo-help'); ?></option>
                          <option value="Vignette"><?php esc_html_e('Vignette', 'qcld-seo-help'); ?></option>
                          <option value="Infrared"><?php esc_html_e('Infrared', 'qcld-seo-help'); ?></option>
                          <option value="3D"><?php esc_html_e('3D', 'qcld-seo-help'); ?></option>
                          <option value="None"><?php esc_html_e('None', 'qcld-seo-help'); ?></option>
                      </select>
                  </div>
                  <div class="mb-5">
                      
                      <label for="img_size" class="qcld_seo-form-label"><?php esc_html_e('Size:', 'qcld-seo-help'); ?></label>
                      <select class="qcld_seo-input" name="img_size" id="img_size">
                          <!-- <option value="256x256"><?php esc_html_e('256x256', 'qcld-seo-help'); ?></option>
                          <option value="512x512" selected><?php esc_html_e('512x512', 'qcld-seo-help'); ?></option> -->
                          <option value="1024x1024"><?php esc_html_e('1024x1024', 'qcld-seo-help'); ?></option>
                          <option value="1792x1024"><?php esc_html_e('1792x1024', 'qcld-seo-help'); ?></option>
                          <option value="1024x1792"><?php esc_html_e('1024x1792', 'qcld-seo-help'); ?></option>
                      </select>
                      
                  </div>
                  <div class="mb-5">
                      
                      <label for="num_images" class="qcld_seo-form-label"><?php esc_html_e('# of:', 'qcld-seo-help'); ?></label>
                      <select name="num_images" id="num_images" class="qcld_seo-input">
                          <option value="1"><?php esc_html_e('1', 'qcld-seo-help'); ?></option>
                          <option value="2"><?php esc_html_e('2', 'qcld-seo-help'); ?></option>
                          <option value="3" selected><?php esc_html_e('3', 'qcld-seo-help'); ?></option>
                          <option value="4"><?php esc_html_e('4', 'qcld-seo-help'); ?></option>
                          <option value="5"><?php esc_html_e('5', 'qcld-seo-help'); ?></option>
                          <option value="6"><?php esc_html_e('6', 'qcld-seo-help'); ?></option>
                          <option value="7"><?php esc_html_e('7', 'qcld-seo-help'); ?></option>
                          <option value="8"><?php esc_html_e('8', 'qcld-seo-help'); ?></option>
                      </select>
                   
                  </div>
              </div>
        
          </div>
          <div class="qcld_seo_content_form">
              <form class="qcld_seo-single-content-form" method="post">

              
                  <div class="mb-5">
                  <p><b><?php esc_html_e( 'AI Image Generator works with OpenAi only', 'qcld-seo-help' ); ?></b></p>
                  <label for="prompt"><?php esc_html_e('Prompt', 'qcld-seo-help'); ?>:</label>
                  <textarea name="prompt" id="prompt" rows="2" cols="50"></textarea>
                  <input class="qcld_seo_btn generate_image_text" type="button" value="<?php esc_html_e( 'Surprise Me', 'qcld-seo-help' ); ?>">
                  <button class="qcld_seo_btn generate_image" name="generate"><?php esc_html_e( 'Generate', 'qcld-seo-help' ); ?></button>
                  <p style="color:red;"><b><?php esc_html_e('(Please'); ?> <a href="<?php echo esc_url('https://platform.openai.com/settings/organization/billing/'); ?>" target="_blank"><?php esc_html_e('Pre-purchase credit'); ?></a> <?php esc_html_e('from OpenAI API platform and increase the API usage limit. Otherwise, AI features will not work)'); ?></b></p>
              </div>
              <div class="mb-5">
                  <div id="qcld_seo-tab-generated-text">
                  </div>
              </div>
          </div>
      </div>
      <script>
          jQuery(document).ready(function ($) {
              $('.qcld_seo-collapse-title').click(function () {
                  if (!$(this).hasClass('qcld_seo-collapse-active')) {
                      $('.qcld_seo-collapse').removeClass('qcld_seo-collapse-active');
                      $('.qcld_seo-collapse-title span').html('+');
                      $(this).find('span').html('-');
                      $(this).parent().addClass('qcld_seo-collapse-active');
                  }
              })
          })
      </script>
     </form>

  </div>


<?php 

    }
}



add_action( 'admin_notices', 'qcld_seohelp_pro_notice',100 );
function qcld_seohelp_pro_notice(){
    global $pagenow, $typenow;

    $screen = get_current_screen();

    // var_dump( $screen->base );
    // wp_die();

    if(isset($screen->base) && (  $screen->base == 'seo-help_page_qcld_seo_bulk_content_generate' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-supports' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-section' || 
                    $screen->base == 'seo-help_page_qcld-seo-summarizer' || 
                    $screen->base == 'seo-help_page_qcld_seo_img_generator' ||
                    $screen->base == 'seo-help_page_qc_open_ai_single_content' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-new-scan' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-scan' ||
                    $screen->base == 'seo-help_page_qc-seo-broken-link-checker' ||
                    $screen->base == 'toplevel_page_qcld-seo-help' ||
                    $screen->base == 'seo-help_page_qcld_seo_bulk_content_generate' ) ){
    ?>
    <div id="message-hero-info" class="notice notice-info is-dismissible" style="padding:4px 0px 0px 4px;background:#C13825;">
        <?php
            printf(
                __('%s  %s  %s', 'qchero'),
                '<a href="'.esc_url('https://www.dna88.com/product/seo-help-pro/').'" target="_blank">',
                '<img src="'.esc_url(qcld_linkbait_img_url).'/halloween-SEO-Help.jpg" >',
                '</a>'
            );

        ?>
    </div>

<?php

  }

}

add_action( 'admin_notices', 'qcld_seohelp_pro_api_key_check_notice',1000 );
function qcld_seohelp_pro_api_key_check_notice(){
    global $pagenow, $typenow;

    $screen = get_current_screen();

    $OPENAI_API_KEY     = get_option('qcld_seohelp_api_key');

    $qcld_gemini_api_key  = get_option('qcld_gemini_api_key');

    if( isset($screen->base) && ( $screen->base == 'seo-help_page_qcld_seo_bulk_content_generate' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-supports' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-section' || 
                    $screen->base == 'seo-help_page_qcld-seo-summarizer' || 
                    $screen->base == 'seo-help_page_qcld_seo_img_generator' ||
                    $screen->base == 'seo-help_page_qc_open_ai_single_content' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-new-scan' ||
                    $screen->base == 'seo-help_page_qcld-seo-help-scan' ||
                    $screen->base == 'seo-help_page_qc-seo-broken-link-checker' ||
                    $screen->base == 'toplevel_page_qcld-seo-help' ||
                    $screen->base == 'seo-help_page_qcld_seo_bulk_content_generate' ) &&  ( empty( $OPENAI_API_KEY ) )  ){
    ?>
    <div id="message-hero" class="notice notice-info is-dismissible" style="background:red;color:#fff;">
        <?php
            printf(
                __('%s  %s  %s', 'qchero'),
                '<p>',
                'You have not connected with any AI services for SEO Help plugin. AI features will not work. Please connect from SEO Help -> Settings',
                '</p>'
            );

        ?>
    </div>

<?php

  }

}







// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, 'https://youtube.googleapis.com/youtube/v3/captions?part=snippet&videoId=M7FIvfx5J10&key=AIzaSyD2eJN9J34MLlh7gmvW0BMtS9MPSqVseLU');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
// curl_setopt($ch,CURLOPT_ENCODING , '');
// curl_setopt($ch, CURLOPT_HTTPHEADER, [
//     //'Authorization: Bearer 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',
//     'Accept: application/json',
//     'Accept-Encoding: gzip',
// ]);

// $response = curl_exec($ch);


// curl_close($ch);

//$results    = json_decode($response);

//curl_setopt($ch, CURLOPT_ENCODING, '');
//echo "<pre>";
//var_dump( $response );
//wp_die();
