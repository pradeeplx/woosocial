(function($, document) {
    
    var jck_woo_social = {
        
        cache: function() {
            jck_woo_social.els = {};
            jck_woo_social.vars = {};
            
            jck_woo_social.els.$actions_list = $('.jck_woo_social-actions');
            jck_woo_social.els.$tab_links = $('.jck_woo_social-tab-link');
            jck_woo_social.els.$tab_content = $('.jck_woo_social-tab-content');
            jck_woo_social.els.$load_more_button = $('.jck_woo_social-load-more');
            jck_woo_social.els.$action_blocks = $('.jck_woo_social-action');
            
            jck_woo_social.vars.action_offset = 1;
            jck_woo_social.vars.is_hidden_class = "jck_woo_social--is-hidden";
            
        },
 
        on_ready: function() {
            
            jck_woo_social.cache();
            jck_woo_social.setup_follow_actions();
            jck_woo_social.setup_like_action();
            jck_woo_social.setup_tabs();
            jck_woo_social.setup_load_more();
            jck_woo_social.setup_activity_log();
            
        },
        
        on_scroll: function() {
            
            jck_woo_social.scroll_activity_log();
            
        },
     
        setup_follow_actions: function() {
            
            $('.jck_woo_social-follow-action').on('click', function(){
                
                var $button = $(this),
                    user_id = $button.attr('data-user-id'),
                    type = $button.attr('data-type'),
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
            					$button.attr('data-type',data.button.type);
            					
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
            
        },
        
        setup_load_more: function() {
            
            jck_woo_social.els.$load_more_button.on('click', function(){
               
                if( $(this).hasClass('jck_woo_social-load-more--activity') ) {
                    jck_woo_social.load_more_activity( $(this) );
                }
                    
                if( $(this).hasClass('jck_woo_social-load-more--likes') ) {
                    jck_woo_social.load_more_likes( $(this) );
                }
                
            });
            
        },
        
        load_more_activity: function( $load_more_button ) {
            
            var limit = parseInt( $load_more_button.attr('data-limit') ),
                offset = parseInt( $load_more_button.attr('data-offset') ),
                next_offset = limit+offset,
                user_id = $load_more_button.attr('data-user-id'),
                profile_user_id = $load_more_button.attr('data-profile-user-id'),
                $load_more_item = $load_more_button.parent();
                
            $.ajax({
				type: "GET",
				url: jck_woo_social_vars.ajax_url,
				cache: false,
				dataType: "jsonp",				
				crossDomain: true,
				data: {
					action : 'jck_woo_social_load_more_activity',
					nonce : jck_woo_social_vars.nonce,
					limit : limit,
					offset : offset,
					user_id : user_id,
					profile_user_id : profile_user_id
				},
				
				success: function( data ) {
					
					if( data.activity_html ) {
    					
    					$load_more_item.before( data.activity_html );
    					$load_more_button.attr('data-offset', next_offset) ;
    					
					} else {
    					
    					$load_more_item.remove();
    					
					}
					
				},
				
				error: function( data ) {
    				
				}
			});
            
        },
        
        load_more_likes: function( $load_more_button ) {
            
            var limit = parseInt( $load_more_button.attr('data-limit') ),
                offset = parseInt( $load_more_button.attr('data-offset') ),
                next_offset = limit+offset,
                user_id = $load_more_button.attr('data-user-id');
                
            $.ajax({
				type: "GET",
				url: jck_woo_social_vars.ajax_url,
				cache: false,
				dataType: "jsonp",				
				crossDomain: true,
				data: {
					action : 'jck_woo_social_load_more_likes',
					nonce : jck_woo_social_vars.nonce,
					limit : limit,
					offset : offset,
					user_id : user_id
				},
				
				success: function( data ) {
					
					if( data.likes_html ) {
    					
    					$('.products').append( data.likes_html );
    					
					}
					
				},
				
				error: function( data ) {
    				
				}
			});
            
        },
        
        setup_activity_log: function() {
            
            jck_woo_social.hide_actions(jck_woo_social.els.$action_blocks, jck_woo_social.vars.action_offset);
            
        },
        
        hide_actions: function($action_blocks, action_offset) {
    		
    		$action_blocks.each(function(){
    			
    			if( $(this).offset().top > $(window).scrollTop()+$(window).height()*action_offset ) {
        			$(this).find('.jck_woo_social-action__icon, .jck_woo_social-action__wrapper').css({ opacity: 0 }).addClass(jck_woo_social.vars.is_hidden_class);
                }
    			
    		});
    		
    	},
    
    	show_actions: function($action_blocks, action_offset) {
    		
    		$action_blocks.each(function(){
    			
    			if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*action_offset && $(this).find('.jck_woo_social-action__icon').hasClass(jck_woo_social.vars.is_hidden_class) ) {
        			$(this).find('.jck_woo_social-action__icon, .jck_woo_social-action__wrapper')
        			    .removeClass(jck_woo_social.vars.is_hidden_class)
        			    .animate({ opacity: 1 });
                }
    			
    		});
    		
    	},
    	
    	scroll_activity_log: function() {
        	
        	if(!window.requestAnimationFrame) {
            	
			    setTimeout(function(){ jck_woo_social.show_actions(jck_woo_social.els.$action_blocks, jck_woo_social.vars.action_offset); }, 100);
           
            } else {
                
                window.requestAnimationFrame(function(){ jck_woo_social.show_actions(jck_woo_social.els.$action_blocks, jck_woo_social.vars.action_offset); });  
                
            }
            
    	}
     
    };
    
	$(document).ready( jck_woo_social.on_ready() );
	
	$(window).on('scroll', function(){
    	jck_woo_social.on_scroll();
    });

}(jQuery, document));