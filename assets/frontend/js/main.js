(function($, document) {
    
    var jck_woo_social = {
        
        cache: function() {
            jck_woo_social.els = {};
            jck_woo_social.vars = {};
            
            jck_woo_social.els.$actions_list = $('.jck_woo_social-actions');
            jck_woo_social.els.$tab_links = $('.jck_woo_social-tab-link');
            jck_woo_social.els.$tab_content = $('.jck_woo_social-tab-content');
        },
 
        on_ready: function() {
            
            jck_woo_social.cache();
            jck_woo_social.setup_follow_actions();
            jck_woo_social.setup_like_action();
            jck_woo_social.setup_tabs();
            
        },
     
        setup_follow_actions: function() {
            
            $('.jck_woo_social-follow-action').on('click', function(){
                
                var $button = $(this),
                    user_id = $button.attr('data-user-id'),
                    type = $button.attr('data-follow-type'),
                    loading_class = 'jck_woo_social-follow-action--loading';
                
                if( type === "login" ) {
                    
                } else {
                
                    if( !$button.hasClass(loading_class) ) {
                        
                        $button.addClass(loading_class);
                    
                        $.ajax({
            				type: "GET",
            				url: jck_woo_social_vars.ajax_url,
            				cache: false,
            				dataType: "jsonp",				
            				crossDomain: true,
            				data: {
            					action : 'jck_woo_social_follow_action',
            					nonce : jck_woo_social_vars.nonce,
            					user_id : user_id,
            					type : type
            				},
            				
            				success: function( data ) {
            					
            					$button.text(data.button.text);
            					$button.attr('data-follow-type',data.button.type);
            					
            					$button.removeClass(loading_class);
            					$button.removeClass('jck_woo_social-follow-action--'+type);
            					$button.addClass('jck_woo_social-follow-action--'+data.button.type);
            					
            					if(data.add_follow_html) {
                					jck_woo_social.els.$actions_list.prepend(data.add_follow_html);
            					}
            					
            					if(data.remove_follow_class) {
                					$(data.remove_follow_class).remove();
            					}
            				},
            				
            				error: function( data ) {
                				
            				}
            			});
        			
        			}
    			
    			}
			
			});
            
        },
        
        setup_like_action: function() {
            
            $(document).on('click', '.jck-woo-social-like-action', function(){
                
                var $button = $(this),
                    $current_likes_list = $button.closest('.jck-woo-social-likes'),
                    product_id = $button.attr('data-product-id'),
                    type = $button.attr('data-type'),
                    $count = $button.find('.jck-woo-social-like-button__count'),
                    loading_class = 'jck_woo_social-follow-action--loading';
                    
                if( !$button.hasClass(loading_class) ) {
                        
                    $button.addClass(loading_class);
                    
                    $.ajax({
        				type: "GET",
        				url: jck_woo_social_vars.ajax_url,
        				cache: false,
        				dataType: "jsonp",				
        				crossDomain: true,
        				data: {
        					action : 'jck_woo_social_like_action',
        					nonce : jck_woo_social_vars.nonce,
        					product_id : product_id,
        					type : type
        				},
        				
        				success: function( data ) {
        					
        					if( $count.length > 0 ) {
            					
            					var count = parseInt($count.text()),
            					    new_count = type === "like" ? count+1 : count-1;
            					
            					$count.text( new_count ); 
            					$button.attr('data-type', data.button.type);
            					
            					$button.removeClass(loading_class);
            					$button.removeClass('jck_woo_social-like-action--'+type);
            					$button.addClass('jck_woo_social-like-action--'+data.button.type);
            					
            					if(data.add_like_html) {
                					$current_likes_list.find('.jck-woo-social-likes__item--like-button').after(data.add_like_html);
            					}
            					
            					if(data.remove_like_class) {
                					$current_likes_list.find(data.remove_like_class).remove();
            					}     					
            					
        					}
        				},
        				
        				error: function( data ) {
            				
        				}
        			});
        
                }
                
                return false;
                
            });
            
        },
        
        setup_tabs: function() {
            
            jck_woo_social.els.$tab_links.on('click', function(){
                
                var target = $(this).attr('href'),
                    active_class = 'jck_woo_social-tab-link--active';
                
                jck_woo_social.els.$tab_links.removeClass(active_class);
                $(this).addClass(active_class);
                jck_woo_social.els.$tab_content.hide();
                $(target).show();
                
                return false;
                
            });
            
        }
     
    };
    
	$(document).ready( jck_woo_social.on_ready() );

}(jQuery, document));