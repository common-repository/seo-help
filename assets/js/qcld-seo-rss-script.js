(function($){
    'use strict';
   
        $( document ).ready(function() {
            
            $(document).on('click', '.qcld-seo-rss-switch-btn', function (e) {
                //e.preventDefault();

                var $this   = $(this);
                var btntext = $this.text();
                var id      = $this.attr('data-id');

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        'action'    : 'qcld_seo_help_rss_post_status',
                        'id'        : id,
                        'security'  : qcld_seo_ajax_nonce
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $this.closest('.qcld-seo-rss-switch').find('.spinner').addClass('is-active');
                        $this.addClass('spinning');
                        $this.prop("disabled", true);
                    },
                    complete: function() {
        
                    },
                    success: function(response) {
                        $this.prop("disabled", false);
                        $this.removeClass('spinning');
                        $this.closest('.qcld-seo-rss-switch').find('.spinner').removeClass('is-active');
                    },
                    error: function() {
                    	
                        $this.prop("disabled", false);
                        $this.removeClass('spinning');
                        $this.closest('.qcld-seo-rss-switch').find('.spinner').removeClass('is-active');
                        alert( 'Someting went worng' );
                        
                    }
                });
        
            });


            
            $(document).on('click', '.qcld_seo_help_rss_run_now', function (e) {
                e.preventDefault();

                var $this   = $(this);
                var btntext = $this.text();
                var id      = $this.attr('data-id');

                

				var numberRow = $this
					.closest('.column-status')
					.find(".qcld_seo_help-table");
				//numberRow.find("td").hide();

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        'action'    : 'qcld_seo_help_rss_run_now_ajax',
                        'id'        : id,
                        'security'  : qcld_seo_ajax_nonce
                       
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        //$this.text('Checking...');
                        $this.closest('.qcld-seo-rss-status-wrap').find('.spinner').addClass('is-active');
                        $this.addClass('spinning');
                        $this.prop("disabled", true);

                    },
                    complete: function() {
        
                    },
                    success: function(response) {

                        //console.log( response );
        
                        $this.prop("disabled", false);
                        $this.removeClass('spinning');
                        $this.closest('.qcld-seo-rss-status-wrap').find('.spinner').removeClass('is-active');

						//numberRow.hide();
						if ( response.data.import_success ) {
							numberRow.addClass('import_success');
						}else{

							numberRow.addClass('import_error');
						}
						numberRow.html(response.data.msg);

        
                      
                    },
                    error: function() {
        
                        $this.prop("disabled", false);
                        $this.removeClass('spinning');
                        $this.closest('.qcld-seo-rss-status-wrap').find('.spinner').removeClass('is-active');

                        alert( 'Someting went worng' );
                        
                    }
                });
        
            });
            
            
            $(document).on('click', '.qcld_seo_help-found-details', function (e) {
                e.preventDefault();
                var currentDom = $(this);
                $('.qcld_seo_help-duplicates-found').hide();
                currentDom.closest('.column-status').find(".qcld_seo_help-items-found").toggle();
            });
            
            $(document).on('click', '.qcld_seo_help-duplicates-details', function (e) {
                e.preventDefault();
                var currentDom = $(this);
                $('.qcld_seo_help-items-found').hide();
                currentDom.closest('.column-status').find(".qcld_seo_help-duplicates-found").toggle();
            });


    });

})(jQuery)