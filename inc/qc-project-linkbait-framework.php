<?php

add_filter( 'ot_show_pages', '__return_false' );
add_filter( 'ot_show_new_layout', '__return_false' );
add_filter( 'ot_header_version_text', 'qcld_linkbait_ot_version_text_custom' );

if ( ! function_exists( 'qcld_linkbait_ot_version_text_custom' ) ) {
	function qcld_linkbait_ot_version_text_custom(){

		$text = esc_html('Developed by', 'seo-help') .' <a href="'.esc_url('http://www.quantumcloud.com', 'seo-help').'" target="_blank">'.esc_html('Web Design Company - QuantumCloud', 'seo-help').'</a>';
		
		return $text;
	}
}

/**
 * Hook to register admin pages 
 */
add_action( 'init', 'qcld_linkbait_register_options_pages' );

/**
 * Registers all the required admin pages.
 */

if ( ! function_exists( 'qcld_linkbait_register_options_pages' ) ) {
	function qcld_linkbait_register_options_pages() {

	  // Only execute in admin & if OT is installed
	  if ( is_admin() && function_exists( 'ot_register_settings' ) ) {

	    // Register the pages
	    ot_register_settings( 
	      array(
	        array(
	          'id'              => 'option_tree',
	          'pages'           => array(
	            array(
	              'id'              => 'linkbait_options',
				  			'parent_slug'     => 'seo-help',
	              'page_title'      => esc_html__('Settings', 'seo-help'),
	              'menu_title'      => esc_html__('Settings', 'seo-help'),
	              'capability'      => 'edit_theme_options',
	              'menu_slug'       => 'seo-help-page',
	              'icon_url'        => null,
	              'position'        => null,
	              'updated_message' => esc_html__('SEO Help Options Updated.', 'seo-help'),
	              'reset_message'   => esc_html__('SEO Help Options Reset.', 'seo-help'),
	              'button_text'     => esc_html__('Save Changes', 'seo-help'),
	              'show_buttons'    => true,
	              'screen_icon'     => 'options-general',
	              'contextual_help' => null,
				  
						    'sections'        => array( 
						      array(
						        'id'          => 'general',
						        'title'       => esc_html__( 'General', 'seo-help' )
						      ),

						    ),
									  
						    'settings'        => array( 
						      array(
										'label'       => esc_html__('Enable LinkBait Generator', 'seo-help'),
										'id'          => 'qcld_linkbait_generator',
										'type'        => 'on-off',
										'desc'        => esc_html__('If you make this option ON, then Linkbait Generator box will appear.', 'seo-help'),
										'std'         => 'off',
										'rows'        => '',
										'post_type'   => '',
										'taxonomy'    => '',
										'class'       => '',
										'section'     => 'general'
									),
									array(
										'label'       => esc_html__('Enable Content Writing Tips', 'seo-help'),
										'id'          => 'qcld_content_tips',
										'type'        => 'on-off',
										'desc'        => esc_html__('Enable Content Writing Tips to display SEO Content Writing Tips.', 'seo-help'),
										'std'         => 'off',
										'rows'        => '',
										'post_type'   => '',
										'taxonomy'    => '',
										'class'       => '',
										'section'     => 'general'
									),
									array(
										'label'       => esc_html__('Enable CTR Improvement', 'seo-help'),
										'id'          => 'qcld_ctr_improvement',
										'type'        => 'on-off',
										'desc'        => esc_html__('Enable CTR Improvement To view the CTR Improvement.', 'seo-help'),
										'std'         => 'off',
										'rows'        => '',
										'post_type'   => '',
										'taxonomy'    => '',
										'class'       => '',
										'section'     => 'general'
									),
		
	    					)
	            )
	          )
	        )
	      )
	    );

	  }

	}
}

?>