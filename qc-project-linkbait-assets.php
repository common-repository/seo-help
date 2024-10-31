<?php

defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'qcld_linkbait_admin_style_script' ) ) {
	function qcld_linkbait_admin_style_script(){ //Load addition script and css files //

		$screen = get_current_screen();

		// if( ( isset($_GET["page"]) && !empty($_GET["page"] ) && 
		// 	( 	$_GET["page"] == "qc-seo-broken-link-checker" 	|| 
		// 		$_GET["page"] == "qcld-seo-help" 				||
		// 		$_GET["page"] == "qcld-seo-help-new-scan" 		||
		// 		$_GET["page"] == "qc_open_ai_single_content" 	||
		// 		$_GET["page"] == "qcld_seo_img_generator" 		||
		// 		$_GET["page"] == "qcld-seo-help-section" 		||
		// 		$_GET["page"] == "qcld-seo-help-supports" 		||
		// 		$_GET["page"] == "qcld-seo-summarizer" 			||
		// 		$_GET["page"] == "qcld_seo_bulk_content_generate" 			||
		// 		$_GET["page"] == "qcld-seo-help-scan" 				
		// 	) ) || ( isset( $_GET["post"] ) && !empty($_GET["post"]) ) || ( isset( $screen->post_type ) && ( $screen->post_type == 'page' || $screen->post_type == 'post' ) ) ){

			wp_enqueue_script( 'qcld-linkbait-slider-script', qcld_linkbait_assets_url . '/js/qcldseo_bootstrap_slider.min.js', array('jquery'),false,true);
			wp_enqueue_script( 'qcld-linkbait-admin-script', qcld_linkbait_assets_url . '/js/admin_footer.js', array('jquery'),false,true);
			wp_enqueue_style( 'qcld-linkbait_admin_css', qcld_linkbait_assets_url . "/css/style.css");

			wp_enqueue_style( 'qcld-linkbait_admin_css', qcld_linkbait_assets_url . "/css/style.css");

			wp_enqueue_style('qcld-linkbait_bootstarp_css',qcld_linkbait_assets_url. '/css/qcldseo_bootstrape.css', );

			wp_enqueue_script( 'qcld-bootstrap-admin-script', qcld_linkbait_assets_url . '/js/bootstrap.js', array('jquery'),false,true);
			wp_enqueue_script('jquery-ui-core'); // enqueue jQuery UI Core
		    wp_enqueue_script('jquery-ui-tabs'); // enqueue jQuery UI Tabs


	        wp_add_inline_script( 'qcld-linkbait-admin-script', 
	            'var qcld_seo_ajaxurl               = "' . admin_url('admin-ajax.php') . '"; 
	             var qcld_seo_ajax_nonce            = "'. wp_create_nonce( 'seo-help-pro' ).'";   
	             var qcld_seo_bulk_content          = "'. admin_url('admin.php?page=qcld_seo_bulk_content') .'";  
	             var openai_images_security_nonce   = "'. wp_create_nonce('qcld_seo_openai_images_security_nonce') .'";  
	             var qcld_linkbait_img_url          = "'. qcld_linkbait_img_url .'";  
	             ', 'before');

		//}



		if( isset( $screen->post_type ) && ( $screen->post_type == 'qcld_rss_imports' ) ){

	        wp_enqueue_style('qcld-linkbait_rss_css', qcld_linkbait_assets_url. '/css/qcld-seo-rss-script.css' );
	        wp_enqueue_script('qcldseo_rss_script', qcld_linkbait_assets_url. '/js/qcld-seo-rss-script.js', true );  

	        wp_add_inline_script( 'qcldseo_rss_script', 
	            'var ajaxurl                        = "' . admin_url('admin-ajax.php') . '"; 
	             var qcld_seo_ajax_nonce            = "'. wp_create_nonce( 'seo-help-pro' ).'";   
	             ', 'before');

	    }


	}
}
add_action( 'admin_enqueue_scripts', 'qcld_linkbait_admin_style_script' );


?>