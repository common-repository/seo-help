<?php 

defined('ABSPATH') or die("You can't access this file directly.");


if ( ! function_exists( 'qcld_seo_summarizer_page_callback_func' ) ) {
    function qcld_seo_summarizer_page_callback_func() {

    	$qcld_seo_summarizer_enable 		= get_option('qcld_seo_summarizer_enable');
    	$qcld_seo_summarizer_post 			= get_option('qcld_seo_summarizer_post');
    	$qcld_seo_display_summary 			= get_option('qcld_seo_display_summary');
    	$qcld_seo_sentence_length 			= get_option('qcld_seo_sentence_length');
    	$qcld_seo_summary_title 			= get_option('qcld_seo_summary_title');
    	$qcld_seo_summary_post_type_data 	= get_option('qcld_seo_summary_post_type_data');

    	?>
		<div class="wrap fs-section">

		    <div id="poststuff">
		        <div id="fs_account">
		        	<h3><?php esc_html_e("Automatic Posts Summarizer", 'qcld-seo-help'); ?></h3>
                    <div class="qcld_summarizer_notice">
                        <p><?php esc_html_e("Automatic content summarizer can be helpful for both your readers and SEO. It is automatically added to your contents and can make your content look fresh to Search Engines if you enable Generate multiple summaries and use them randomly.", 'qcld-seo-help'); ?></p>

                    	<p><?php esc_html_e("This is a Pro version feature.", 'qcld-seo-help'); ?> <a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>"><?php esc_html_e("Upgrade to SEO Help Pro Now!", 'qcld-seo-help'); ?></a></p>
                    </div>

		        	<form action="" method="post" class="summarize">
			            <table class="form-table">
			                <tbody>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Enable Summarizer", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <input type="checkbox" class="" name="qcld_seo_summarizer_enable" id="qcld_seo_summarizer_enable" value="1" <?php echo(get_option('qcld_seo_summarizer_enable') == 1 ? 'checked' : ''); ?>>
			                    </td>
			                </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e("Generate multiple summaries and use them randomly", 'qcld-seo-help'); ?></th>
                                <td>
                                    <input type="checkbox" class="" name="qcld_seo_summary_generate" id="qcld_seo_summary_generate" value="1" <?php echo(get_option('qcld_seo_summary_generate') == 1 ? 'checked' : ''); ?>>
                                </td>
                            </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Display Summary on All Posts", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <input type="checkbox" class="" name="qcld_seo_summarizer_post" id="qcld_seo_summarizer_post" value="1" <?php echo(get_option('qcld_seo_summarizer_post') == 1 ? 'checked' : ''); ?>>
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Display Summary", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <select name="qcld_seo_display_summary" id="qcld_seo_display_summary">
			                        	<option value="before" 	<?php selected( get_option('qcld_seo_display_summary'), 'before' ); ?> ><?php esc_html_e("Before Post Content", 'qcld-seo-help'); ?></option>
			                        	<option value="after" 	<?php selected( get_option('qcld_seo_display_summary'), 'after' ); ?> ><?php esc_html_e("After Post Content", 'qcld-seo-help'); ?></option>
			                        </select>
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Summary Sentences Length", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <input type="number" class="qcld_seo_sentence_length_custom" name="qcld_seo_sentence_length" id="qcld_seo_sentence_length" value="<?php echo get_option('qcld_seo_sentence_length'); ?>">
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Summary Title", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <input type="text" class="regular-text qcld_seo_sentence_length_custom" name="qcld_seo_summary_title" id="qcld_seo_summary_title" value="<?php echo get_option('qcld_seo_summary_title'); ?>">
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Display Summary Style", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <select name="qcld_seo_display_summary_style" id="qcld_seo_display_summary_style">
			                        	<option value="one" <?php selected( get_option('qcld_seo_display_summary_style'), 'one' ); ?> ><?php esc_html_e("Style One", 'qcld-seo-help'); ?></option>
			                        	<option value="two" <?php selected( get_option('qcld_seo_display_summary_style'), 'two' ); ?> ><?php esc_html_e("Style Two", 'qcld-seo-help'); ?></option>
			                        	<option value="three" <?php selected( get_option('qcld_seo_display_summary_style'), 'three' ); ?> ><?php esc_html_e("Style Three", 'qcld-seo-help'); ?></option>
			                        	<option value="four" <?php selected( get_option('qcld_seo_display_summary_style'), 'four' ); ?> ><?php esc_html_e("Style Four", 'qcld-seo-help'); ?></option>
			                        	<option value="five" <?php selected( get_option('qcld_seo_display_summary_style'), 'five' ); ?> ><?php esc_html_e("Style Five", 'qcld-seo-help'); ?></option>
			                        	<option value="six" <?php selected( get_option('qcld_seo_display_summary_style'), 'six' ); ?> ><?php esc_html_e("Style Six", 'qcld-seo-help'); ?></option>
			                        	<option value="seven" <?php selected( get_option('qcld_seo_display_summary_style'), 'seven' ); ?> ><?php esc_html_e("Style Seven", 'qcld-seo-help'); ?></option>
			                        	<option value="eight" <?php selected( get_option('qcld_seo_display_summary_style'), 'eight' ); ?> ><?php esc_html_e("Style Eight", 'qcld-seo-help'); ?></option>
			                        	<option value="nine" <?php selected( get_option('qcld_seo_display_summary_style'), 'nine' ); ?> ><?php esc_html_e("Style Nine", 'qcld-seo-help'); ?></option>
			                        	<option value="ten" <?php selected( get_option('qcld_seo_display_summary_style'), 'ten' ); ?> ><?php esc_html_e("Style Ten", 'qcld-seo-help'); ?></option>
			                        </select>
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Summary Background Color", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <input type="text" class="qcld-seo-color qcld_seo_sentence_length_custom" name="qcld_seo_summary_bg" id="qcld_seo_summary_bg" value="<?php echo get_option('qcld_seo_summary_bg'); ?>">
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Summary Border Color", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <input type="text" class="qcld-seo-color qcld_seo_sentence_length_custom" name="qcld_seo_summary_border_color" id="qcld_seo_summary_border_color" value="<?php echo get_option('qcld_seo_summary_border_color'); ?>">
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php esc_html_e("Summary Font Color", 'qcld-seo-help'); ?></th>
			                    <td>
			                        <input type="text" class="qcld-seo-color qcld_seo_sentence_length_custom" name="qcld_seo_summary_font_color" id="qcld_seo_summary_font_color" value="<?php echo get_option('qcld_seo_summary_font_color'); ?>">
			                    </td>
			                </tr>
                           	<tr>
                               <th><?php esc_html_e( 'Post types', 'qcld-seo-help' ); ?></th>
                               <td class="qcld-list">
                                   <input type="checkbox" name="qcld_seo_summary_post_type_data[]" id="qcld_seo_summary_post_type_post" value="post" <?php 

                                            if( isset($qcld_seo_summary_post_type_data) && !empty($qcld_seo_summary_post_type_data) ){

                                                foreach(unserialize($qcld_seo_summary_post_type_data) as $post){
                                                    if('post' == $post){
                                                        echo esc_attr('checked');
                                                    }
                                                }  
                                            }else if('post' == 'post'){
                                                echo esc_attr('checked');
                                            }

                                         ?> >
                                   <label for="qcld_seo_summary_post_type_post"><?php echo esc_attr('Post', 'qcld-seo-help') ?> (<code><?php echo esc_attr( 'post', 'qcld-seo-help') ?></code>)</label><br>


                                   <input type="checkbox" name="qcld_seo_summary_post_type_data[]" id="qcld_seo_summary_post_type_page" value="page" <?php 

                                            if( isset($qcld_seo_summary_post_type_data) && !empty($qcld_seo_summary_post_type_data) ){

                                                foreach(unserialize($qcld_seo_summary_post_type_data) as $post){
                                                    if('page' == $post){
                                                        echo esc_attr('checked');
                                                    }
                                                }  
                                            }

                                         ?> >
                                   <label for="qcld_seo_summary_post_type_page"><?php echo esc_attr('Page', 'qcld-seo-help') ?> (<code><?php echo esc_attr( 'post', 'qcld-seo-help') ?></code>)</label><br>

                                   <input type="checkbox" name="qcld_seo_summary_post_type_data[]" id="qcld_seo_summary_post_type_product" value="product" <?php 

                                            if( isset($qcld_seo_summary_post_type_data) && !empty($qcld_seo_summary_post_type_data) ){

                                                foreach(unserialize($qcld_seo_summary_post_type_data) as $post){
                                                    if('product' == $post){
                                                        echo esc_attr('checked');
                                                    }
                                                }  
                                            }

                                         ?> >
                                   <label for="qcld_seo_summary_post_type_product"><?php echo esc_attr('Product', 'qcld-seo-help') ?> (<code><?php echo esc_attr( 'product', 'qcld-seo-help') ?></code>)</label><br>
                               </td>
                           	</tr>
                            <tr>
                                <th scope="row"><?php esc_html_e("Show Summary by Shortcode", 'qcld-seo-help'); ?></th>
                                <td>
                                    <code>[qc_summary] </code> <i style="padding-left: 15px"> <?php esc_html_e(" If you want to show summary individual page or post only.", 'qcld-seo-help'); ?></i>
                                </td>
                            </tr>
			                <tr>
			                    <th scope="row">
			                    	<?php esc_html_e("This is a Pro version feature.", 'qcld-seo-help'); ?> 
			                    </th>
			                    <td>
			                        
			                    	<p><a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>"><?php esc_html_e("Upgrade to SEO Help Pro Now!", 'qcld-seo-help'); ?></a></p>
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row">
			                    	<button type="submit" class="button button-primary" disabled><?php esc_html_e('Save Settings'); ?></button>
			                    </th>
			                    <td>
			                        
			                    	<?php wp_nonce_field('qcld-seo-help'); ?>
			                    </td>
			                </tr>
			               
			                </tbody>
			            </table>
			        </form>
		        </div>
		    </div>
		</div>


    	<?php 

    } // end
} // end

