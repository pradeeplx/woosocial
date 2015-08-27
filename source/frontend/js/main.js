(function($, document) {
    
    var jck_woosocial = {
        
        cache: function() {
            jck_woosocial.els = {};
            jck_woosocial.vars = {};
            
            jck_woosocial.vars.is_hidden_class = "jck-woosocial--is-hidden";
            jck_woosocial.vars.animate_spin_class = "jck-woosocial-spin";
            jck_woosocial.vars.add_to_cart_buttons_class = '.jck-woosocial-add-to-cart-wrapper .add_to_cart_button';
            jck_woosocial.vars.card_grid_cards_class = '.jck-woosocial-card-grid .jck-woosocial-card';
            jck_woosocial.vars.card_hover_class = 'jck-woosocial-card--hover';
            
            jck_woosocial.els.document_body = $(document.body);
            jck_woosocial.els.actions_list = $('.jck-woosocial-actions');
            jck_woosocial.els.tab_links = $('.jck-woosocial-profile-link');
            jck_woosocial.els.tab_content = $('.jck-woosocial-tab-content');
            jck_woosocial.els.load_more_button = $('.jck-woosocial-load-more');
            jck_woosocial.els.action_blocks = $('.jck-woosocial-action');            
            jck_woosocial.els.likes_container = $('.jck-woosocial-user-likes');
            jck_woosocial.els.add_to_cart_buttons = $('.jck-woosocial-add-to-cart-wrapper .add_to_cart_button');
            jck_woosocial.els.tick_icon = $('<i class="jck-woosocial-ic-tick"></i>');
            jck_woosocial.els.loading_icon = $('<i class="jck-woosocial-ic-loading jck-woosocial-spin"></i>');
            jck_woosocial.els.card_grids = $('.jck-woosocial-card-grid');
            jck_woosocial.els.breakpoint_elements = $('[data-breakpoints]');
            jck_woosocial.els.breakpoints = [];
            
        },
 
        on_ready: function() {
            
            jck_woosocial.cache();
            jck_woosocial.setup_follow_actions();
            jck_woosocial.setup_like_action();
            jck_woosocial.setup_tabs();
            jck_woosocial.setup_load_more();
            jck_woosocial.add_to_cart();
            jck_woosocial.setup_breakpoints();
            jck_woosocial.check_breakpoints();
            jck_woosocial.setup_cards();
            
        },
        
        on_scroll: function() {
            
        },
        
        on_resize: function() {
            
            jck_woosocial.check_breakpoints();
            
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
                
                jck_woosocial.check_breakpoints();
                
                return false;
                
            });
            
        },
        
        spin_icon_start: function( $icon ){
            
            $icon.addClass( jck_woosocial.vars.animate_spin_class );
            
        },
        
        spin_icon_stop: function( $icon ){
            
            $icon.removeClass( jck_woosocial.vars.animate_spin_class );
            
        },
        
        disable_load_more_button: function( $button ){
            
            $button
            .html( jck_woosocial_vars.strings.no_more )
            .removeAttr('href')
            .addClass("jck-woosocial-load-more--disabled");
            
        },
        
        setup_load_more: function() {
            
            jck_woosocial.els.load_more_button.on('click', function(){
                
                var $load_more_button = $(this),
                    $load_more_icon = $load_more_button.find('i');
                    
                if( $load_more_button.hasClass("jck-woosocial-load-more--disabled") ) {
                    return false;
                }
                
                jck_woosocial.spin_icon_start( $load_more_icon );
               
                if( $(this).hasClass('jck-woosocial-load-more--activity') ) {
                    
                    jck_woosocial.load_more_activity( $(this), function(){
                        
                        jck_woosocial.spin_icon_stop( $load_more_icon );
                        
                    } );
                    
                }
                    
                if( $(this).hasClass('jck-woosocial-load-more--likes') ) {
                    
                    jck_woosocial.load_more_likes( $(this), function(){
                        
                        jck_woosocial.spin_icon_stop( $load_more_icon );
                        
                    } );
                    
                }
                
            });
            
        },
        
        load_more_activity: function( $load_more_button, callback ) {
            
            var limit = parseInt( $load_more_button.attr('data-limit') ),
                offset = parseInt( $load_more_button.attr('data-offset') ),
                next_offset = limit+offset,
                user_id = $load_more_button.attr('data-user-id'),
                profile_user_id = $load_more_button.attr('data-profile-user-id'),
                $load_more_item = $load_more_button.closest('li');
                
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
    					
    					jck_woosocial.disable_load_more_button( $load_more_button );
    					
					}
					
					if(callback !== undefined) {
            			callback(data);
            		}
					
				},
				
				error: function( data ) {
    				
				}
			});
            
        },
        
        load_more_likes: function( $load_more_button, callback ) {
            
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
    					
    					jck_woosocial.disable_load_more_button( $load_more_button );
    					
					}
					
					if(callback !== undefined) {
            			callback(data);
            		}
					
				},
				
				error: function( data ) {
    				
				}
			});
            
        },
    	
    	add_to_cart: function() {
        	
        	jck_woosocial.els.document_body.on('click', jck_woosocial.vars.add_to_cart_buttons_class, function(){
            	
            	var $button = $(this),
            	    button_text = $button.text();
            	    
                $button
                .attr('data-button-text',button_text)
                .append( jck_woosocial.els.loading_icon );
            	
        	});
        	
        	jck_woosocial.els.document_body.on( 'added_to_cart', function( e, fragments, cart_hash, $button ) {
            
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
        	
    	},
    	
        setup_breakpoints: function() {
            
            if( jck_woosocial.els.breakpoint_elements.length > 0 ) {
                
                // store breakpoint data in cache for future reference
            
                jck_woosocial.els.breakpoint_elements.each(function( index, element ){
                
                    var $element = $(element),
                	    breakpoints = $element.attr('data-breakpoints'),
                	    breakpoints_array = $.parseJSON( breakpoints ),
                        breakpoint_object = {
                        	element: $element,
                            breakpoints: breakpoints_array,
                            width: 0
                        };
                    
                    jck_woosocial.els.breakpoints.push( breakpoint_object );
                    
                });
            
            }
            
            // when an element width has changed, trigger breakpoint update
            
            jck_woosocial.els.document_body.on('element_width_change', function( event, breakpoint_object ){
            
                jck_woosocial.update_breakpoints( breakpoint_object );
                
            });
            
        },
        
        check_breakpoints: function() {
        
            if( jck_woosocial.els.breakpoints.length > 0 ) {
            
            	$.each(jck_woosocial.els.breakpoints, function(index, breakpoint_object){
                    
                    var previous_width = breakpoint_object.width,
                        current_width = breakpoint_object.element.width();
                    
                    if( current_width !== previous_width ) {
                        
                        breakpoint_object.width = current_width;
                    	
                        jck_woosocial.els.document_body.trigger('element_width_change', [breakpoint_object]);
                        
                    }
                    
                });
                
            }
        
        },

    	update_breakpoints: function( breakpoint_object ) {
            
            var current_breakpoint = false;
            
            // figure out which breakpoint data we want to use
            
            $.each( breakpoint_object.breakpoints, function( index, breakpoint_data ){
                        	
                if( breakpoint_object.width <= breakpoint_data.max_width ) {
                    
                    if( ! current_breakpoint ) {

                        current_breakpoint = breakpoint_data.max_width;

                    } else if ( breakpoint_data.max_width <= current_breakpoint ) {

                        current_breakpoint = breakpoint_data.max_width;

                    }

                }

            });
            
            // now we know the breakpoint to use, apply that class and remove the others
                    	
            $.each( breakpoint_object.breakpoints, function( index, breakpoint_data ){

                if( breakpoint_data.max_width === current_breakpoint ) {

                    breakpoint_object.element.addClass( breakpoint_data.class );

                } else {

                    breakpoint_object.element.removeClass( breakpoint_data.class );

                }

            });
        	
    	},
    	
    	setup_cards: function() {
        	
        	jck_woosocial.els.document_body.hoverIntent( 
            	
        	    function(){
            	    
            	    var $card = $(this);
            	    
            	    $card.addClass( jck_woosocial.vars.card_hover_class );
            	    
        	    }, 
        	    
        	    function(){
            	    
            	    var $card = $(this);
            	    
            	    $card.removeClass( jck_woosocial.vars.card_hover_class );
            	    
        	    }, 
        	    
        	    jck_woosocial.vars.card_grid_cards_class 
            );
        	
    	}
     
    };
    
	$(document).ready( jck_woosocial.on_ready() );	
	$(window).on('scroll', function() { jck_woosocial.on_scroll(); } );
	$(window).on('resize', function() { jck_woosocial.on_resize(); } );

}(jQuery, document));