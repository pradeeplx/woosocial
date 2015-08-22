(function($, document) {
    
    var jck_woosocial = {
        
        cache: function() {
            jck_woosocial.els = {};
            jck_woosocial.vars = {};
            
            jck_woosocial.els.actions_list = $('.jck-woosocial-actions');
            jck_woosocial.els.tab_links = $('.jck-woosocial-profile-link');
            jck_woosocial.els.tab_content = $('.jck-woosocial-tab-content');
            jck_woosocial.els.load_more_button = $('.jck-woosocial-load-more');
            jck_woosocial.els.action_blocks = $('.jck-woosocial-action');            
            jck_woosocial.els.likes_container = $('.jck-woosocial-user-likes');
            jck_woosocial.els.add_to_cart_buttons = $('.jck-woosocial-add-to-cart-wrapper .add_to_cart_button');
            jck_woosocial.els.tick_icon = $('<i class="jck-woosocial-ic-tick"></i>');
            jck_woosocial.els.loading_icon = $('<i class="jck-woosocial-ic-loading jck-woosocial-spin"></i>');
            
            
            jck_woosocial.vars.action_offset = 1;
            jck_woosocial.vars.is_hidden_class = "jck-woosocial--is-hidden";
            
        },
 
        on_ready: function() {
            
            jck_woosocial.cache();
            jck_woosocial.setup_follow_actions();
            jck_woosocial.setup_like_action();
            jck_woosocial.setup_tabs();
            jck_woosocial.setup_load_more();
            jck_woosocial.setup_activity_log();
            jck_woosocial.add_to_cart();
            
        },
        
        on_scroll: function() {
            
            jck_woosocial.scroll_activity_log();
            
        },
     
        setup_follow_actions: function() {
            
            $('.jck-woosocial-follow-action').on('click', function(){
                
                var $button = $(this),
                    user_id = $button.attr('data-user-id'),
                    type = $button.attr('data-type'),
                    loading_class = 'jck-woosocial-follow-action--loading';
                
                if( type === "login" ) {
                    
                } else {
                
                    if( !$button.hasClass(loading_class) ) {
                        
                        $button.addClass(loading_class);
                    
                        $.ajax({
            				type:        "GET",
            				url:         jck_woosocial_vars.ajax_url,
            				cache:       false,
            				dataType:    "jsonp",				
            				crossDomain: true,
            				data: {
            					action:  'jck_woosocial_follow_action',
            					nonce:   jck_woosocial_vars.nonce,
            					user_id: user_id,
            					type:    type
            				},
            				
            				success: function( data ) {
            					
            					$button.text(data.button.text);
            					$button.attr('data-type',data.button.type);
            					
            					$button.removeClass(loading_class);
            					$button.removeClass('jck-woosocial-follow-action--'+type);
            					$button.addClass('jck-woosocial-follow-action--'+data.button.type);
            					
            					if(data.add_follow_html) {
                					jck_woosocial.els.actions_list.prepend(data.add_follow_html);
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
            
            $(document).on('click', '.jck-woosocial-like-action', function(){
                
                var $button = $(this),
                    $current_likes_list = $button.closest('.jck-woosocial-likes'),
                    product_id = $button.attr('data-product-id'),
                    type = $button.attr('data-type'),
                    $count = $button.find('.jck-woosocial-like-button__count'),
                    loading_class = 'jck-woosocial-follow-action--loading';
                    
                if( !$button.hasClass(loading_class) ) {
                        
                    $button.addClass(loading_class);
                    
                    $.ajax({
        				type: "GET",
        				url: jck_woosocial_vars.ajax_url,
        				cache: false,
        				dataType: "jsonp",				
        				crossDomain: true,
        				data: {
        					action: 'jck_woosocial_like_action',
        					nonce: jck_woosocial_vars.nonce,
        					product_id: product_id,
        					type: type
        				},
        				
        				success: function( data ) {
        					
        					if( $count.length > 0 ) {
            					
            					var count = parseInt($count.text()),
            					    new_count = type === "like" ? count+1 : count-1;
            					
            					$count.text( new_count ); 
            					$button.attr('data-type', data.button.type);
            					
            					$button.removeClass(loading_class);
            					$button.removeClass('jck-woosocial-like-action--'+type);
            					$button.addClass('jck-woosocial-like-action--'+data.button.type);
            					
            					if(data.add_like_html) {
                					$current_likes_list.find('.jck-woosocial-likes__item--like-button').after(data.add_like_html);
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
            
            jck_woosocial.els.tab_links.on('click', function(){
                
                var target = $(this).attr('href'),
                    active_class = 'jck-woosocial-profile-link--active';
                
                jck_woosocial.els.tab_links.removeClass(active_class);
                $(this).addClass(active_class);
                jck_woosocial.els.tab_content.hide();
                $(target).show();
                
                eqjs.refreshNodes();
                eqjs.query();
                
                return false;
                
            });
            
        },
        
        setup_load_more: function() {
            
            jck_woosocial.els.load_more_button.on('click', function(){
               
                if( $(this).hasClass('jck-woosocial-load-more--activity') ) {
                    jck_woosocial.load_more_activity( $(this) );
                }
                    
                if( $(this).hasClass('jck-woosocial-load-more--likes') ) {
                    jck_woosocial.load_more_likes( $(this) );
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
				type:        "GET",
				url:         jck_woosocial_vars.ajax_url,
				cache:       false,
				dataType:    "jsonp",				
				crossDomain: true,
				data: {
					action:          'jck_woosocial_load_more_activity',
					nonce:           jck_woosocial_vars.nonce,
					limit:           limit,
					offset:          offset,
					user_id:         user_id,
					profile_user_id: profile_user_id
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
				type:        "GET",
				url:         jck_woosocial_vars.ajax_url,
				cache:       false,
				dataType:    "jsonp",				
				crossDomain: true,
				data: {
					action:  'jck_woosocial_load_more_likes',
					nonce:   jck_woosocial_vars.nonce,
					limit:   limit,
					offset:  offset,
					user_id: user_id
				},
				
				success: function( data ) {
					
					if( data.likes_html ) {
    					
    					jck_woosocial.els.likes_container.append( data.likes_html );
    					$load_more_button.attr('data-offset', next_offset) ;
    					
					} else {
    					
    					$load_more_button.remove();
    					
					}
					
				},
				
				error: function( data ) {
    				
				}
			});
            
        },
        
        setup_activity_log: function() {
            
            jck_woosocial.hide_actions(jck_woosocial.els.action_blocks, jck_woosocial.vars.action_offset);
            
        },
        
        hide_actions: function($action_blocks, action_offset) {
    		
    		$action_blocks.each(function(){
    			
    			if( $(this).offset().top > $(window).scrollTop()+$(window).height()*action_offset ) {
        			$(this).find('.jck-woosocial-action__icon, .jck-woosocial-action__wrapper').css({ opacity: 0 }).addClass(jck_woosocial.vars.is_hidden_class);
                }
    			
    		});
    		
    	},
    
    	show_actions: function($action_blocks, action_offset) {
    		
    		$action_blocks.each(function(){
    			
    			if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*action_offset && $(this).find('.jck-woosocial-action__icon').hasClass(jck_woosocial.vars.is_hidden_class) ) {
        			$(this).find('.jck-woosocial-action__icon, .jck-woosocial-action__wrapper')
        			    .removeClass(jck_woosocial.vars.is_hidden_class)
        			    .animate({ opacity: 1 });
                }
    			
    		});
    		
    	},
    	
    	scroll_activity_log: function() {
        	
        	if(!window.requestAnimationFrame) {
            	
			    setTimeout(function(){ jck_woosocial.show_actions(jck_woosocial.els.action_blocks, jck_woosocial.vars.action_offset); }, 100);
           
            } else {
                
                window.requestAnimationFrame(function(){ jck_woosocial.show_actions(jck_woosocial.els.action_blocks, jck_woosocial.vars.action_offset); });  
                
            }
            
    	},
    	
    	add_to_cart: function() {
        	
        	jck_woosocial.els.add_to_cart_buttons.on('click', function(){
            	
            	var $button = $(this),
            	    button_text = $button.text();
            	    
                $button
                .attr('data-button-text',button_text)
                .append( jck_woosocial.els.loading_icon );
            	
        	});
        	
        	$( document.body ).on( 'added_to_cart', function( e, fragments, cart_hash, $button ) {
            
                var $woosocial_button_wrapper = $button.closest('.jck-woosocial-add-to-cart-wrapper'),
                    button_text = $button.attr('data-button-text');
                
                if( $woosocial_button_wrapper.length > 0 ) {
                    
                    $button
                    .html( button_text )                   
                    .append( jck_woosocial.els.tick_icon );
                    
                    setTimeout(function(){
                        
                        $button.html( button_text );
                        
                    }, 2000);
                    
                }
            	
        	});
        	
    	}
     
    };
    
	$(document).ready( jck_woosocial.on_ready() );
	
	$(window).on('scroll', function(){
    	jck_woosocial.on_scroll();
    });

}(jQuery, document));