(function($) {
    'use strict';

	jQuery(document).ready(function($){

		
        // open ai settings
        var qcld_check_temperature = $('#qcld_seo_temperature');
        if(qcld_check_temperature.length > 0){
            var slidertemperature = new Slider("#qcld_seo_temperature");
            slidertemperature.on("slide", function(sliderValue) {
                document.getElementById("temperatureVal").textContent = sliderValue;
            });
        }

        var qcld_presence_penalty = $('#qcld_seo_presence_penalty');
        if(qcld_presence_penalty.length > 0){
            var sliderpp = new Slider("#qcld_seo_presence_penalty");
            sliderpp.on("slide", function(sliderValue) {
                document.getElementById("presence_penaltyVal").textContent = sliderValue;
            });
        }

        var qcld_frequency_penalty = $('#qcld_seo_frequency_penalty');
        if(qcld_frequency_penalty.length > 0){
            var sliderfp = new Slider("#qcld_seo_frequency_penalty");
            sliderfp.on("slide", function(sliderValue) {
                document.getElementById("frequency_penaltyVal").textContent = sliderValue;
            });
        }
		
        // gemini ai settings
        var qcld_gemini_ai_temperature = $('#qcld_gemini_ai_temperature');
        if(qcld_gemini_ai_temperature.length > 0){
            var slidertemperature = new Slider("#qcld_gemini_ai_temperature");
            slidertemperature.on("slide", function(sliderValue) {
                document.getElementById("qcld_gemini_ai_temperature_val").textContent = sliderValue;
            });
        }

        var qcld_gemini_ai_top_k = $('#qcld_gemini_ai_top_k');
        if(qcld_gemini_ai_top_k.length > 0){
            var sliderpp = new Slider("#qcld_gemini_ai_top_k");
            sliderpp.on("slide", function(sliderValue) {
                document.getElementById("qcld_gemini_ai_top_k_val").textContent = sliderValue;
            });
        }

        var qcld_gemini_ai_top_p = $('#qcld_gemini_ai_top_p');
        if(qcld_gemini_ai_top_p.length > 0){
            var sliderfp = new Slider("#qcld_gemini_ai_top_p");
            sliderfp.on("slide", function(sliderValue) {
                document.getElementById("qcld_gemini_ai_top_p_val").textContent = sliderValue;
            });
        }

		//code for title generator
		$(document).on( 'click', '#title_generator', function(event){
	   // $("#title_generator").on("click", function(event){
	       	//console.log('working')
		    var titles = $("#title");
	       	if (titles.length == ''){
	       		var title = $(".editor-post-title").children().children().find('textarea').val();
	       	}else{
	       		var title = $("#title").val();
	       	}

			$.post(
				ajaxurl,
				{
					action 			: 'qcld_linkbait_show_suggestion',
					security 		: qcld_seo_ajax_nonce,
					linkbait_title 	: title
				},
				function(data){
					$('#title_Generator').modal('show');
					$('#title_Generator').addClass('show');
					$('#wpwrap').append(data);
					
				}
			)

	    });
		$(document).on( 'click', '#qcld_keyword_search', function(event){
		   	var qcld_keyword_suggestion = $('#qcld_keyword_suggestion_text').val();
	       
	        var languageSpecifics = $("#qcld-seo-language").find(":selected").val();
	        var languageSpecificsArray = languageSpecifics.split('-');
	        var selectedCountry = languageSpecificsArray[0];					
	        var selectedlanguage = languageSpecificsArray[1];

	        $('#qcld_keyword_search').addClass('spinning');
	        $('#qcld_keyword_search').prop("disabled",true);


	        $.ajax({
	            url: ajaxurl,
	            method: 'POST',
	            data: {
	                'action' 			: 'qcldseohelp_keyword_suggestion_tag',
					'security' 			: qcld_seo_ajax_nonce,
	                'keyword' 			: qcld_keyword_suggestion,
	                'selectedCountry' 	: selectedCountry,
	                'selectedlanguage' 	: selectedlanguage,
	            },
	            dataType: 'json',
	            success: function(response) {
	            	$('#qcld_keyword_search').prop("disabled",false);
	            	$('#qcld_keyword_search').removeClass('spinning');
	              	$('#keywords_result').html(response.keywords);
	              	$('#keywords_resultBackdrop').modal('show');
	              	$('#keywords_resultBackdrop').addClass('show');
	            }
	        });

	    });
		$(document).on( 'click', '#seo_help_createtag', function(event){
		   	
	        var postid = $('.postid').val();
	        var valkeyword = [];
	        $('.form-check-input:checkbox:checked').each(function(){
	         valkeyword.push($(this).val());
	         });
	       	$.post(
	         ajaxurl,
	         {
	           action 		: 'qcld_linkbait_add_ontag',
			   security 	: qcld_seo_ajax_nonce,
	           keywordkeys 	: valkeyword,
	           postid 		: postid
	         },
	         function(data){

	          $('#keywords_resultBackdrop').modal('hide');
	          // $('#wpwrap').append(data);
	          window.location.reload(true);
	           
	         });

	    });
		//code for seo tips
		$(".seo_tips_rand").on("click", function(event){
			var seo_tips_id = this.id;
			$.post( // Ajax request for Content Writting Tips//
					ajaxurl,
					{
						action 			: 'qcld_linkbait_seo_tips',
			   			security 		: qcld_seo_ajax_nonce,
						linkbait_seo_id : seo_tips_id
					},
					function(data){
						$('#wpwrap').append(data);
					}
				)
	    });
		
		// Closing Modal //
		$(document).on( 'click', '.modal-content .close', function(){
	        $(this).parent().parent().remove();
	    });
		
		$(document).on( 'click', '.modal-content #linkbait_add', function(e){ //Adding data //
			e.preventDefault();
			var currentDom = $(this);
	        var $selected_title = $('input[name="linkbait_radio"]:checked').val();
			if($selected_title){
				$("#title-prompt-text").html('');
				var titles = $("#title");
				var props = $("#title");
		       	if (titles.length == ''){

		       		var $temp = $("<input>");
					currentDom.append($temp);
					$temp.val( $selected_title ).select();
					document.execCommand("copy");
					$temp.remove();

            		//navigator.clipboard.writeText($selected_title);
					$('<div class="copy-notification">Copied Title</div>').prependTo('.qcld_seo_copy_msg').delay(2000).fadeOut(600, function() {
				        $('.copy-notification').remove();
				    });
		       		

		       	}else{

		       		$("#title").val($selected_title);


		       		var $temp = $("<input>");
					currentDom.append($temp);
					$temp.val( $selected_title ).select();
					document.execCommand("copy");
					$temp.remove();

            		//navigator.clipboard.writeText($selected_title);
					$('<div class="copy-notification">Copied Title</div>').prependTo('.qcld_seo_copy_msg').delay(2000).fadeOut(600, function() {
				        $('.copy-notification').remove();
				    });
				    
					$('<div class="copy-notification">Copied Title</div>').prependTo('.qcld_seo_copy_msg').delay(2000).fadeOut(600, function() {
				        $('.copy-notification').remove();
				    });

		       	}

				
			}else{

				alert('Could not find any Selected Title!!');

			}
	    });


		$(document).on( 'click', '.modal-content #linkbait_singular', function(){ //Code for Singular //
			$("#linkbait_skip").val("");
			$("#linkbait_skip2").val("");
		});
		$(document).on( 'click', '.modal-content #linkbait_plural', function(){ //Code for Plural
			$("#linkbait_skip").val("");
			$("#linkbait_skip2").val("");
		});
		$(document).on( 'click', '.modal-content #linkbait_generate', function(){ //Linkbait generate Operation //
			
			var filter = '';
			if (document.getElementById('linkbait_singular').checked) {
			  filter = document.getElementById('linkbait_singular').value;
			}
			if (document.getElementById('linkbait_plural').checked) {
			  filter = document.getElementById('linkbait_plural').value;
			}
			if (document.getElementById('linkbait_google').checked) {
			  filter = document.getElementById('linkbait_google').value;
			}
			if (document.getElementById('linkbait_openai').checked) {
			  filter = document.getElementById('linkbait_openai').value;
			}
			
			
			var $skip 		= $("#linkbait_skip").val();
			var $skip2 		= $("#linkbait_skip2").val();
	        var $subject 	= $("#linkbait_subject").val();

			if($subject){
				$('#linkbait_loading').show();
				$.post( //Making Ajax request for data //
					ajaxurl,
					{
						action 			: 'qcld_linkbait_generate_suggestion',
			   			security 		: qcld_seo_ajax_nonce,
						linkbait_title  : $subject,
						linkbait_skip 	: $skip,
						linkbait_skip2 	: $skip2,
						linkbait_filter : filter
					},
					function(data){
						$('#linkbait_ajax_data').html(data).fadeIn('slow');
						$('#linkbait_loading').hide();
					}
				)

			}else{
				alert('Please put subject.');
				$("#linkbait_subject").focus();
			}
	    });

		$(document).on('click','#content_generator', function(){

			//console.log('working');

			$('#content_Generator_modal').modal( { backdrop: 'static', keyboard: false  }, 'show');
			jQuery('#content_Generator_modal').modal('show')
			
		})

		$(document).on('click','#qcld_article_keyword_suggestion', function(){
			
			
	        var qcld_keyword_suggestion         = $('#qcld_article_keyword_suggestion_mf').val();
	        var qcld_keyword_number             = $('#qcld_keyword_number').val();
	        var qcld_article_language           = $('#qcld_article_language').val();
	        var qcld_article_number_of_heading  = $('#qcld_article_number_of_heading').val();
	        var qcld_article_heading_tag        = $('#qcld_article_heading_tag').val();
	        var qcld_article_heading_style      = $('#qcld_article_heading_style').val();
	        var qcld_article_heading_tone       = $('#qcld_article_heading_tone').val();
	        var qcld_article_heading_img        = $('#qcld_article_heading_img').val();
	        var qcld_article_heading_img        = $("input[name=qcld_article_heading_img]:checked").val();
	        var qcld_article_heading_tagline    = $("input[name=qcld_article_heading_tagline]:checked").val();
	        var qcld_article_heading_intro      = $("input[name=qcld_article_heading_intro]:checked").val();
	        var qcld_article_heading_conclusion = $("input[name=qcld_article_heading_conclusion]:checked").val();
	        var qcld_article_label_anchor_text  = $('#qcld_article_label_anchor_text').val();
	        var qcld_article_target_url         = $('#qcld_article_target_url').val();
	        var qcld_article_target_label_cta   = $('#qcld_article_target_label_cta').val();
	        var qcld_article_cta_pos            = $('#qcld_article_cta_pos').val();
	        var qcld_article_label_keywords     = $('#qcld_article_label_keywords').val();
	        var qcld_article_label_word_to_avoid= $('#qcld_article_label_word_to_avoid').val();
	        var qcld_article_label_keywords_bold= $("input[name=qcld_article_label_keywords_bold]:checked").val();
	        var qcld_article_heading_faq        = $("input[name=qcld_article_heading_faq]:checked").val();
	        var qcld_article_img_size           = $('#qcld_article_img_size').val();

	        $('#qcld_article_keyword_suggestion').addClass('spinning');
	        $('#qcld_article_keyword_suggestion').prop("disabled",true);
	        $('#linkbait_article_keyword_data').html('');
        	$('.qcld_seo-playground-buttons').hide();

	        $.ajax({
	          url: ajaxurl,
	          method: 'POST',
	          data: {
	              'action' 			: 'qcldseohelp_keyword_suggestion_content',
			   	  'security' 		: qcld_seo_ajax_nonce,
	              'keyword'        	: qcld_keyword_suggestion,
	              'keyword_number' 	: qcld_keyword_number,
	              'qcld_article_language'           : qcld_article_language,
	              'qcld_article_number_of_heading'  : qcld_article_number_of_heading,
	              'qcld_article_heading_tag'        : qcld_article_heading_tag,
	              'qcld_article_heading_style'      : qcld_article_heading_style,
	              'qcld_article_heading_tone'       : qcld_article_heading_tone,
	              'qcld_article_heading_img'        : qcld_article_heading_img,
	              'qcld_article_heading_tagline'    : qcld_article_heading_tagline,
	              'qcld_article_heading_intro'      : qcld_article_heading_intro,
	              'qcld_article_heading_conclusion' : qcld_article_heading_conclusion,
	              'qcld_article_label_anchor_text'  : qcld_article_label_anchor_text,
	              'qcld_article_target_url'         : qcld_article_target_url,
	              'qcld_article_target_label_cta'   : qcld_article_target_label_cta,
	              'qcld_article_cta_pos'            : qcld_article_cta_pos,
	              'qcld_article_label_keywords'     : qcld_article_label_keywords,
	              'qcld_article_label_word_to_avoid': qcld_article_label_word_to_avoid,
	              'qcld_article_label_keywords_bold': qcld_article_label_keywords_bold,
	              'qcld_article_heading_faq'        : qcld_article_heading_faq,
	              'qcld_article_img_size'           : qcld_article_img_size
	              //'selectedlanguage': selectedlanguage,
	          },
	          dataType: 'json',
	          success: function(response) {
	            //$('#linkbait_keyword_data').append(response.keywords);
	            $('#qcld_article_keyword_suggestion').prop("disabled",false);
	            $('#qcld_article_keyword_suggestion').removeClass('spinning');
            	$('.qcld_seo-playground-buttons').show();
	            $('#linkbait_article_keyword_data').html('<div class="qcld_copied-content-wrap"><div class="qcld_copied-content_text btn d-none link-success">Copied</div><a class="btn btn-sm btn-secondary qcld-copied-content_text"><span class="dashicons dashicons-admin-page"></span></a></div><textarea id="qcld_content_result_msg">' + response.keywords +'</textarea>');
	            $('#linkbait_article_keyword_data').append('<div class="qcld_content_result_wrap"><div class="qcld_rewrite_result_count">' + response.keywords.length +'</div></div>');
	                    
	            $('#qcld_content_result_msg').focus();
	            $('#qcld_content_result_msg').focusout();
	          }
	        });

		});
		
        $(document).on('click', '.qcld_article_playground_save', function (e) {
        // $('.qcld_seo-playground-save').click(function (){
            var qcld_seo_draft_btn = $(this);
            var title = $('#qcld_article_keyword_suggestion_mf').val();
            var content = $('#qcld_content_result_msg').val();

            if(title === ''){
                alert('Please enter title');
            }else if(content === ''){
                alert('Please wait content generated');
            }else{
                $.ajax({
                    url: ajaxurl,
                    data: {title: title, content: content, action: 'qcld_seo_save_draft_post_extra', security: qcld_seo_ajax_nonce },
                    dataType: 'json',
                    type: 'POST',
                    beforeSend: function (){
                        qcld_seo_draft_btn.attr('disabled','disabled');
                        qcld_seo_draft_btn.append('<span class="spinner"></span>');
                        qcld_seo_draft_btn.find('.spinner').css('visibility','unset');
                    },
                    success: function (res){
                        qcld_seo_draft_btn.removeAttr('disabled');
                        qcld_seo_draft_btn.find('.spinner').remove();
                        if(res.status === 'success'){
                            //window.location.href;
                            window.location.replace( res.post_link );
                        }else{
                            alert(res.msg);
                        }
                    },
                    error: function (){
                        qcld_seo_draft_btn.removeAttr('disabled');
                        qcld_seo_draft_btn.find('.spinner').remove();
                        alert('Something went wrong');
                    }
                });
            }
        });
        $(document).on('click', '.qcld_article_playground_clear', function (e) {


            $('#qcld_article_keyword_suggestion_mf').val('');
            $('#qcld_content_result_msg').val('');
            $('.qcld_seo-playground-buttons').hide();
            $('.qcld_rewrite_result_count').hide();

        });


      	$(document).on('click','.qcld-copied-content_text',function(event){
      		var currentDom = $(this);
			var copy_con = currentDom.parent().parent().parent().find('#qcld_content_result_msg').val();
			var copy_text = (copy_con !== '') ? copy_con : currentDom.parent().parent().parent().find('#qcld_content_result_msg').text();

			currentDom.addClass("qcld_copied");

			currentDom.parent().find(".qcld_copied-content_text").removeClass("d-none");
			setTimeout(() => {
				currentDom.parent().find(".qcld_copied-content_text").addClass("d-none");
			}, 1500);
			//console.log( copy_con );
			//console.log( copy_text );

       		var $temp = $("<input>");
			currentDom.append($temp);
			$temp.val( copy_text ).select();
			document.execCommand("copy");
			$temp.remove();

      	});


		jQuery(document).ready(function($){
		    $("#tabs").tabs();
		});

		// post & page link quick search
		$(document).on('click', '.qc_seo_linkcheck', function () {

			var $this = $(this);
	        $.ajax({
	            url: ajaxurl,
	            method: 'POST',
	            data: {
	                'action' 	: 'qcld_seo_help_broken_link_checking_by_ajax',
			   	  	'security' 	: qcld_seo_ajax_nonce,
	               
	            },
	            dataType: 'json',
	            beforeSend: function() {
	                $this.text('Checking...');
		        	$this.addClass('spinning');
	               	$('.qcld_seo_help_link_content').html('');
	            },
	            complete: function() {

	            },
	            success: function(response) {

	            	$this.removeClass('spinning');
			        $this.text('Start Checking');
			        $('.qcld_seo_help_link_content').html(response.html);

		          
	            },
	            error: function() {}
	        });

	    });


		// sld link quick search
		$(document).on('click', '.qc_seo_simple_linkcheck', function () {

			var $this = $(this);
	        $.ajax({
	            url: ajaxurl,
	            method: 'POST',
	            data: {
	                'action'	: 'qcld_seo_help_broken_sld_link_checking_by_ajax',
			   	  	'security' 	: qcld_seo_ajax_nonce,
	            },
	            dataType: 'json',
	            beforeSend: function() {
	                $this.text('Checking...');
		        	$this.addClass('spinning');
	               	$('.qcld_seo_help_sld_link_content').html('');
	            },
	            complete: function() {

	            },
	            success: function(response) {

	            	/*$this.removeClass('spinning');
			        $this.text('Start Link Checking');
			        $('.qcld_seo_help_sld_link_content').html(response.html);*/

		            setTimeout( 
			            function(){  
			                $this.removeClass('spinning');
			                $this.text('Start Link Checking');
			                $('.qcld_seo_help_sld_link_content').html(response.html);
			            }, 5000);
					
	            },
	            error: function() {}
	        });

	    });
		$(document).on('click', '#qcld_keyword_suggestion_btn', function () {
			var qcld_keyword_suggestion = $('#qcld_keyword_suggestion').val();
	       
	        var languageSpecifics = $("#qcld-seo-language").find(":selected").val();
	        var languageSpecificsArray = languageSpecifics.split('-');
	        var selectedCountry = languageSpecificsArray[0];					
	        var selectedlanguage = languageSpecificsArray[1];


	        $('#qcld_keyword_suggestion_btn').addClass('spinning');
	        $('#qcld_keyword_suggestion_btn').prop("disabled",true);

	        $.ajax({
	            url: ajaxurl,
	            method: 'POST',
	            data: {
	                'action': 'qcldseohelp_keyword_suggestion',
			   	  	'security' 	: qcld_seo_ajax_nonce,
	                'keyword': qcld_keyword_suggestion,
	                'selectedCountry': selectedCountry,
	                'selectedlanguage': selectedlanguage,
	            },
	            dataType: 'json',
	            success: function(response) {
	            	$('#qcld_keyword_suggestion_btn').prop("disabled",false);
	            	$('#qcld_keyword_suggestion_btn').removeClass('spinning');
					$('#linkbait_keyword_data').empty();
					var keywordobject =  JSON.parse(JSON.stringify(response.keywords));
					var keywordkeys = Object.keys(keywordobject);
					for(var i= 0; i < keywordkeys.length;i++){
					  //var fileds = '<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input keywordsuggetions" name="keywordsuggetions_'+i+'" value="' + keywordobject[keywordkeys[i]] +'"/> <label class="form-check-label">' + keywordobject[keywordkeys[i]] +'</label></div>';
					  var fileds = '<p>' + keywordobject[keywordkeys[i]] +'</p> ';
					  $('#linkbait_keyword_data').append(fileds);
					}
					//$('#linkbait_keyword_data').append('</br><div id="qcld_custom_keyword_input" class="mt-2"></div></br><button id="qcld_outline_suggestion" class="btn btn-info">Create outline</button>');
	            }
	        });
		});

		$(document).on('click', '#qcld_outline_suggestion', function () {
			var suggesstions = [];
			$('.keywordsuggetions:checkbox:checked').each(function(){
			  suggesstions.push($(this).val())
			});


	        $('#qcld_outline_suggestion').addClass('spinning');
	        $('#qcld_outline_suggestion').prop("disabled",true);


			$.post(
			  ajaxurl,
			  {
				action 		: 'qcld_linkbait_outline_data',
			   	security 	: qcld_seo_ajax_nonce,
				suggesstions: suggesstions
			  },
			  function(data){
	            $('#qcld_outline_suggestion').prop("disabled",false);
	            $('#qcld_outline_suggestion').removeClass('spinning');
				$('#linkbait_outline_data').empty();
				$('#linkbait_keyword_data .words-select').html('');
				if(data.status == 'success'){
					$('#linkbait_outline_data').append(data.results);
				}
			   
			  }
			)
		});
		$('body').on('click','.openai_save_settings',function(){
			var api_key 			= $('#api_key').val();
			var opeai_engines 		= $('#opeai_engines').val();
			var max_token 			= $('#max_token').val();
			var temperature 		= $('#qcld_seo_temperature').val();
			var presence_penalty 	= $('#qcld_seo_presence_penalty').val();
			var frequency_penalty 	= $('#qcld_seo_frequency_penalty').val();
			 $.ajax({
				 url: ajaxurl,
				 method: 'POST',
				 data: {
					 'action' 				: 'open_save_settings',
                     'security'          	: qcld_seo_ajax_nonce,
					 'api_key' 				: api_key,
					 'opeai_engines' 		: opeai_engines,
					 'max_token' 			: max_token,
					 'temperature' 			: temperature,
					 'presence_penalty' 	: presence_penalty,
					 'frequency_penalty' 	: frequency_penalty
				 },
				 dataType: 'json',
				 success: function(response) {
					//console.log(response)

					 if(response.status == 'success'){
					 	alert(response.results);
					 }
				 }
			 });
		});
		$('body').on('click','.gemini_save_settings',function(){
			var qcld_gemini_api_key 			= $('#qcld_gemini_api_key').val();
			var qcld_gemini_model 				= $('#qcld_gemini_model').val();
			var qcld_gemini_api_version 		= $('#qcld_gemini_api_version').val();
			var qcld_gemini_max_token 			= $('#qcld_gemini_max_token').val();
			var qcld_gemini_ai_temperature 		= $('#qcld_gemini_ai_temperature').val();
			var qcld_gemini_ai_top_p 			= $('#qcld_gemini_ai_top_p').val();
			var qcld_gemini_ai_top_k 			= $('#qcld_gemini_ai_top_k').val();
			var qcld_ai_settings_open_ai 	    = $("input[name=qcld_ai_settings_open_ai]:checked").val();
			 $.ajax({
				 url: ajaxurl,
				 method: 'POST',
				 data: {
					 'action' 						: 'gemini_save_settings',
                     'security'          			: qcld_seo_ajax_nonce,
					 'qcld_gemini_api_key' 			: qcld_gemini_api_key,
					 'qcld_gemini_model' 			: qcld_gemini_model,
					 'qcld_gemini_api_version' 		: qcld_gemini_api_version,
					 'qcld_gemini_max_token' 		: qcld_gemini_max_token,
					 'qcld_gemini_ai_temperature' 	: qcld_gemini_ai_temperature,
					 'qcld_gemini_ai_top_p' 		: qcld_gemini_ai_top_p,
					 'qcld_gemini_ai_top_k' 		: qcld_gemini_ai_top_k,
					 'qcld_ai_settings_open_ai' 	: qcld_ai_settings_open_ai
				 },
				 dataType: 'json',
				 success: function(response) {
					//console.log(response)

					 if(response.status == 'success'){
					 	alert(response.results);
					 }
				 }
			 });
		});

		$( document ).on('click','.qcld_seo_pro_feature',function(){
		  $( '.qcld_seo_pro_feature_content' ).toggleClass( "qcld_seo_pro_feature_active" );
		});



        $(document).on('click', '.generate_image', function (e) {
            var qcld_seo_button             = $(this);
            var qcld_seo_prompt             = $('#prompt').val();
            var qcld_seo_artist             = $('#artist').val();
            var qcld_seo_art_style          = $('#art_style').val();
            var qcld_seo_photography_style  = $('#photography_style').val();
            var qcld_seo_lighting           = $('#lighting').val();
            var qcld_seo_subject            = $('#subject').val();
            var qcld_seo_camera_settings    = $('#camera_settings').val();
            var qcld_seo_composition        = $('#composition').val();
            var qcld_seo_resolution         = $('#resolution').val();
            var qcld_seo_color              = $('#color').val();
            var qcld_seo_special_effects    = $('#special_effects').val();
            var qcld_seo_img_size           = $('#img_size').val();
            var qcld_seo_num_images         = $('#num_images').val();

           // console.log(qcld_seo_special_effects)

            if(qcld_seo_prompt === ''){
                alert('Something went wrong');
            }else{
               
                var qcld_seo_post_status = $('.qcld_seo-post-status:checked').val();
                var qcld_seo_schedule = $('.qcld_seo-schedule-post').val();

                var data = { 
                    qcld_seo_prompt 			: qcld_seo_prompt,
                    qcld_seo_artist 			: qcld_seo_artist,
                    qcld_seo_art_style 			: qcld_seo_art_style,
                    qcld_seo_photography_style 	: qcld_seo_photography_style,
                    qcld_seo_lighting 			: qcld_seo_lighting,
                    qcld_seo_subject 			: qcld_seo_subject,
                    qcld_seo_camera_settings 	: qcld_seo_camera_settings,
                    qcld_seo_composition 		: qcld_seo_composition,
                    qcld_seo_resolution 		: qcld_seo_resolution,
                    qcld_seo_color 				: qcld_seo_color,
                    qcld_seo_special_effects 	: qcld_seo_special_effects,
                    qcld_seo_img_size 		 	: qcld_seo_img_size,
                    qcld_seo_num_images 		: qcld_seo_num_images,
			   		security 					: qcld_seo_ajax_nonce,
                    action 						: 'qcld_seo_image_generate' 
                };
             
                $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function(){
                        qcld_seo_button.attr('disabled','disabled');
                        qcld_seo_button.append('<span class="spinner"></span>');
                        qcld_seo_button.find('.spinner').css('visibility','unset');
                    },
                    success: function (res){
                        qcld_seo_button.removeAttr('disabled');
                        qcld_seo_button.find('.spinner').remove();
                        if(res.status === 'success'){
                           // window.location.href = qcld_seo_bulk_content + '&qcld_seo_track='+res.id
                            $('#qcld_seo-tab-generated-text').html( res.html );
                        }
                        else{
                            alert('Something went wrong');
                        }
                    },
                    error: function (){
                        qcld_seo_button.removeAttr('disabled');
                        qcld_seo_button.find('.spinner').remove();
                        alert('Something went wrong');
                    }
                })
            }
        });

        $(document).on('click', '.generate_image_download', function (e) {
            e.preventDefault();

            var url = $(this).find("img").attr('src'); 

            var qcld_seo_button = $(this);
            var image_url       = $(this).find('img').attr('src');
            var image_page_url  = $(this).find('img').attr('src');
            var photographer    = $(this).find('img').data('photographer');

            var q = '';
            var data = { 
                    qcld_seo_openai_images_upload   : "1",
                    image_url                       : image_url,
                    image_src_page                  : image_page_url,
                    image_user                      : photographer,
                    q                               : q,
                    wpnonce                         : openai_images_security_nonce,
			   		security 						: qcld_seo_ajax_nonce,
                    action                          : 'qcld_seo_image_generate_url_functions' 
                };
             
                $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function(){
                    	qcld_seo_button.removeAttr('disabled').removeClass("uploading").find('.qcld_seo_msg').remove();
                        qcld_seo_button.attr('disabled','disabled');
                        //qcld_seo_button.append('<span class="spinner"></span>');
                        //qcld_seo_button.find('.spinner').css('visibility','unset');
                        qcld_seo_button.addClass('uploading').find('.qcld_seo_download img').replaceWith('<img src="'+ qcld_linkbait_img_url +'/loading.svg" >');
                    },
                    success: function ( res ){
                     
                        //qcld_seo_button.find('.spinner').remove();
                        qcld_seo_button.find('.qcld_seo_download img').replaceWith('<img src="'+ qcld_linkbait_img_url +'/download.svg" ><p class="qcld_seo_msg">'+res.html+'<p>' );
                
                        //setTimeout(function(){ 
                            //qcld_seo_button.removeAttr('disabled').removeClass("uploading").find('.qcld_seo_msg').remove();
                        //}, 3500);

                    },
                    error: function (){
                        qcld_seo_button.removeAttr('disabled');
                        //qcld_seo_button.find('.spinner').remove();
                        //alert('Something went wrong');
                    }
                })

          

        });


        $(document).on('click', '.generate_image_text', function (e) {

            var randomIndex = Math.floor(Math.random() * 41);
            document.getElementById("prompt").value = ["A cat playing checkers","Several cute monsters with ice cream cone bodies","A forest with a small cabin nestled among the trees","A cityscape, with tall skyscrapers and a street scene","A majestic elephant in the African savannah","A robot with a human-like expression on its face","An old fashioned steam train","A musician with a bold graphic style","A bowl of fruit on a table with a window in the background","A detailed fantasy castle with turrets and towers","A dragon breathing fire in a medieval-inspired setting","A young girl with flowers in her hair","A science fiction scene with spaceships and planets","A sunset over a seascape with waves crashing on the shore","A male figure sitting on a park bench lost in thought","A group of superheroes in action","A horse galloping in a field","A city skyline","A mermaid sitting on a rock in a fantasy underwater setting","A still life with a vase of flowers and a bowl of lemons","A jungle scene with monkeys swinging from vines","A robot dog with a playful expression","A group of birds flying over a cityscape","A fantasy landscape with a floating castle and a dragon","A sunset over a desert landscape with a camel in the foreground","A car with a lot of details","A group of robots playing instruments in a band","A man sitting in a chair reading a book","A fantasy dragon with golden scales and red eyes","A still life with a bowl of fruit on a tablecloth","A zoo scene with different animals in their enclosures","A robot with a cat-like design","A group of birds flying over a forest","A fantasy landscape with a floating island and a castle","A sunset over a mountain landscape with a cabin in the foreground","An airplane with a lot of details","A group of robots dancing in a club","A woman sitting on a bench with a book","A fantasy dragon with blue scales and yellow eyes","A still life with a vase of flowers on a windowsill","A robot with a dog-like design"] [randomIndex];

        });


        $('.generate_image_text').trigger('click');


       $(document).on('click','.qcld_form_tab_next', function( event ) {
          var cur       = $(this).closest("#qcld_tab_area").find(".qcld_form_tab_pan.qcld_tab_active");
          var input_val = $(this).closest("#qcld_tab_area").find(".qcld_form_tab_pan.qcld_tab_active input[type=text]");

          if (jQuery(cur).next().length > 0) {
            $(".qcld_form_tab_prev").removeClass("qcld_form_tab_hide");
            jQuery(".qcld_form_tab_pan").removeClass("qcld_tab_active");
            jQuery(cur).next().addClass("qcld_tab_active");
          }
          if (jQuery(cur).next().next().length == 0) {
            $(".qcld_form_tab_next").addClass("qcld_form_tab_hide");
            $(".qcld_form_submit").removeClass("qcld_form_tab_hide");
          }
      });

      $(document).on('click','.qcld_form_tab_prev', function( event ) {
          var cur = $(this).closest("#qcld_tab_area").find(".qcld_form_tab_pan.qcld_tab_active");
          if (jQuery(cur).prev().length > 0) {
            $(".qcld_form_submit").addClass("qcld_form_tab_hide");
            $(".qcld_form_tab_next").removeClass("qcld_form_tab_hide");
            jQuery(".qcld_form_tab_pan").removeClass("qcld_tab_active");
            jQuery(cur).prev().addClass("qcld_tab_active");
          }
          if (jQuery(cur).prev().prev().length == 0) {
            $(".qcld_form_tab_prev").addClass("qcld_form_tab_hide");
          }
      });

      	//$(document).on('click','.qcld_generate_content', function( event ) {
      	$(document).on('keyup','#qcld_seohelp_api_key', function( event ) {
          	var value = $(this).val();
          	$('#qcld_valid_api_key').val('');
          	$('#qcld_valid_api_key').val(value);
      	});

      	$(document).on('click','.qcld_form_submit', function( event ) {
          //event.preventDefault();
          var currentDom = $(this);
          var api_key = $("#qcld_valid_api_key").val() ? $("#qcld_valid_api_key").val() : $("#qcld_seohelp_api_key").val();
          var title = $("#qcld_article_keyword").val();
          var topic = $(".qcld_form_tab_topic_wrap input[type=radio][name=qcld_seohelp_topic]:checked").val();
          //$('.qcld_seo_ai_single_contents .qcld_seohelp_msg').remove();
          /*if(title == ''){
            alert('Please Write Website Name');
            return false;
          }*/ 
          
          currentDom.addClass( 'qcld_ai_active' );
          currentDom.attr('disabled','disabled');
          currentDom.append('<span class="spinner"></span>');
          currentDom.find('.spinner').css('visibility','unset');
          $(".qcld_form_tab_prev").attr('disabled','disabled');
          $(".qcld_form_tab_topic_wrap input[type=radio]").attr('disabled','disabled');

          $.post(
            ajaxurl,
            {
              action    : 'qcld_seo_content_generate',
              security  : qcld_seo_ajax_nonce,
              api_key   : api_key,
              title     : title,
              topic     : topic
            },
            function(data){
                  
                  currentDom.removeClass( 'qcld_ai_active' );
                  currentDom.removeAttr('disabled');
                  currentDom.find('.spinner').remove();

                  $(".qcld_form_tab_prev").removeAttr('disabled');
                  $(".qcld_form_tab_topic_wrap input[type=radio]").removeAttr('disabled');


                  if ( data.status == 'success' ) {
                    // do something

                    $('.qcld_seo_ai_single_contents .qcld_seohelp_msg').remove();
                    $('.qcld_seo_ai_single_contents').append( data.html );
                    return false;

                  } else if (data.status == "pending") {
                    // do something
                    $('.qcld_seo_ai_single_contents .qcld_seohelp_msg').remove();
                    $('.qcld_seo_ai_single_contents').append( data.html );

                    setTimeout(() => {
                      $('.qcld_form_submit').trigger('click');

                    }, 1000);

                    return false;

                  }


            });


      });



        if($('input[type=radio][name="qcld_ai_settings_open_ai"]:checked').val() == 'palm' ){
            $(".qcld_ai_settings_wrap.qcld_open_palm_active").css({'display':'block'});
            $(".qcld_ai_settings_wrap.qcld_open_ai_active").css({'display':'none'});
        }
        $('input[type=radio][name="qcld_ai_settings_open_ai"]').change(function() {
            if (this.value == 'ai' ) {

                $(".qcld_ai_settings_wrap.qcld_open_ai_active").css({'display':'block'});
                $(".qcld_ai_settings_wrap.qcld_open_palm_active").css({'display':'none'});

            }else{

                $(".qcld_ai_settings_wrap.qcld_open_palm_active").css({'display':'block'});
                $(".qcld_ai_settings_wrap.qcld_open_ai_active").css({'display':'none'});

            }
        });






		});

		$ (document).ready (function () {
			$ (".modal a").not (".dropdown-toggle").on ("click", function () {
				$ (".modal").modal ("hide");
			});



		});





		
		

})(jQuery);