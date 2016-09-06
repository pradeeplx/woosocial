(function($, document) {

    var iconic_woosocial = {

        cache: function() {
            iconic_woosocial.els = {};
            iconic_woosocial.vars = {};

            iconic_woosocial.vars.is_hidden_class = "iconic-woosocial--is-hidden";
            iconic_woosocial.vars.animate_spin_class = "iconic-woosocial-spin";
            iconic_woosocial.vars.add_to_cart_buttons_class = '.iconic-woosocial-add-to-cart-wrapper .add_to_cart_button';
            iconic_woosocial.vars.card_grid_cards_class = '.iconic-woosocial-card-grid .iconic-woosocial-card';
            iconic_woosocial.vars.card_hover_class = 'iconic-woosocial-card--hover';

            iconic_woosocial.els.document_body = $(document.body);
            iconic_woosocial.els.actions_list = $('.iconic-woosocial-actions');
            iconic_woosocial.els.tab_links = $('.iconic-woosocial-profile-link');
            iconic_woosocial.els.tab_content = $('.iconic-woosocial-tab-content');
            iconic_woosocial.els.load_more_button = $('.iconic-woosocial-load-more');
            iconic_woosocial.els.action_blocks = $('.iconic-woosocial-action');
            iconic_woosocial.els.likes_container = $('.iconic-woosocial-user-likes');
            iconic_woosocial.els.following_container = $('.iconic-woosocial-following');
            iconic_woosocial.els.followers_container = $('.iconic-woosocial-followers');
            iconic_woosocial.els.add_to_cart_buttons = $('.iconic-woosocial-add-to-cart-wrapper .add_to_cart_button');
            iconic_woosocial.els.tick_icon = $('<i class="iconic-woosocial-ic-tick"></i>');
            iconic_woosocial.els.loading_icon = $('<i class="iconic-woosocial-ic-loading iconic-woosocial-spin"></i>');
            iconic_woosocial.els.card_grids = $('.iconic-woosocial-card-grid');
            iconic_woosocial.els.breakpoint_elements = $('[data-breakpoints]');
            iconic_woosocial.els.breakpoints = [];

        },

        on_ready: function() {

            iconic_woosocial.cache();
            iconic_woosocial.setup_follow_actions();
            iconic_woosocial.setup_product_like_action();
            iconic_woosocial.setup_tabs();
            iconic_woosocial.setup_load_more();
            iconic_woosocial.add_to_cart();
            iconic_woosocial.setup_breakpoints();
            iconic_woosocial.check_breakpoints();
            iconic_woosocial.setup_cards();

        },

        on_scroll: function() {

        },

        on_resize: function() {

            iconic_woosocial.check_breakpoints();

        },

        setup_follow_actions: function() {

            $('.iconic-woosocial-follow-action').on('click', function(){

                var $button = $(this),
                    user_id = $button.attr('data-user-id'),
                    type = $button.attr('data-type'),
                    loading_class = 'iconic-woosocial-follow-action--loading';

                if( type !== "login" ) {

                    if( !$button.hasClass(loading_class) ) {

                        $button.addClass(loading_class);

                        $.ajax({
            				type:        "GET",
            				url:         iconic_woosocial_vars.ajax_url,
            				cache:       false,
            				dataType:    "jsonp",
            				crossDomain: true,
            				data: {
            					action:  'iconic_woosocial_follow_action',
            					nonce:   iconic_woosocial_vars.nonce,
            					user_id: user_id,
            					type:    type
            				},

            				success: function( data ) {

            					$button.text(data.button.text);
            					$button.attr('data-type',data.button.type);

            					$button.removeClass(loading_class);
            					$button.removeClass('iconic-woosocial-follow-action--'+type);
            					$button.addClass('iconic-woosocial-follow-action--'+data.button.type);

            					if(data.add_follow_html) {
                					iconic_woosocial.els.actions_list.prepend(data.add_follow_html);
            					}
            				},

            				error: function( data ) {

            				}
            			});

        			}

    			}

			});

        },

        setup_product_like_action: function() {

            $(document).on('click', '.iconic-woosocial-product-like-action', function(){

                var $button = $(this),
                    $current_likes_list = $button.closest('.iconic-woosocial-product-likes'),
                    product_id = $button.attr('data-product-id'),
                    type = $button.attr('data-type'),
                    $count = $button.find('.iconic-woosocial-product-like-button__count'),
                    loading_class = 'iconic-woosocial-product-like-action--loading',
                    $icon = $button.find('i'),
                    $action = $button.closest('.iconic-woosocial-action');

                if( type !== "login" ) {

                    if( !$button.hasClass(loading_class) ) {

                        $button.addClass(loading_class);

                        $.ajax({
            				type: "GET",
            				url: iconic_woosocial_vars.ajax_url,
            				cache: false,
            				dataType: "jsonp",
            				crossDomain: true,
            				data: {
            					action: 'iconic_woosocial_product_like_action',
            					nonce: iconic_woosocial_vars.nonce,
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

                					$button.removeClass('iconic-woosocial-product-like-action--'+type);
                					$button.addClass('iconic-woosocial-product-like-action--'+data.button.type);

                					if( data.button.type === "unlike" ) {
                    					$icon.attr('class','iconic-woosocial-ic-heart-full');
                					} else {
                    					$icon.attr('class','iconic-woosocial-ic-heart');
                					}

                					if(data.add_like_html) {
                    					$current_likes_list.find('.iconic-woosocial-product-likes__item--like-button').after(data.add_like_html);
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

                }

            });

        },

        setup_tabs: function() {

            iconic_woosocial.els.tab_links.on('click', function(){

                var target = $(this).attr('href'),
                    active_class = 'iconic-woosocial-profile-link--active';

                iconic_woosocial.els.tab_links.removeClass(active_class);
                $(this).addClass(active_class);
                iconic_woosocial.els.tab_content.hide();
                $(target).show();

                iconic_woosocial.check_breakpoints();

                return false;

            });

        },

        spin_icon_start: function( $icon ){

            $icon.addClass( iconic_woosocial.vars.animate_spin_class );

        },

        spin_icon_stop: function( $icon ){

            $icon.removeClass( iconic_woosocial.vars.animate_spin_class );

        },

        disable_load_more_button: function( $button ){

            $button
            .html( iconic_woosocial_vars.strings.no_more )
            .removeAttr('href')
            .addClass("iconic-woosocial-load-more--disabled");

        },

        setup_load_more: function() {

            iconic_woosocial.els.load_more_button.on('click', function(){

                var $load_more_button = $(this),
                    $load_more_icon = $load_more_button.find('i');

                if( $load_more_button.hasClass("iconic-woosocial-load-more--disabled") ) {
                    return false;
                }

                iconic_woosocial.spin_icon_start( $load_more_icon );

                if( $(this).hasClass('iconic-woosocial-load-more--activity') ) {

                    iconic_woosocial.load_more_activity( $(this), function(){

                        iconic_woosocial.spin_icon_stop( $load_more_icon );

                    } );

                }

                if( $(this).hasClass('iconic-woosocial-load-more--likes') ) {

                    iconic_woosocial.load_more_likes( $(this), function(){

                        iconic_woosocial.spin_icon_stop( $load_more_icon );

                    } );

                }

                if( $(this).hasClass('iconic-woosocial-load-more--followers') ) {

                    iconic_woosocial.load_more_followers( $(this), function(){

                        iconic_woosocial.spin_icon_stop( $load_more_icon );

                    } );

                }

                if( $(this).hasClass('iconic-woosocial-load-more--following') ) {

                    iconic_woosocial.load_more_following( $(this), function(){

                        iconic_woosocial.spin_icon_stop( $load_more_icon );

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
				url:         iconic_woosocial_vars.ajax_url,
				cache:       false,
				dataType:    "jsonp",
				crossDomain: true,
				data: {
					action:          'iconic_woosocial_load_more_activity',
					nonce:           iconic_woosocial_vars.nonce,
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

    					iconic_woosocial.disable_load_more_button( $load_more_button );

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
				url:         iconic_woosocial_vars.ajax_url,
				cache:       false,
				dataType:    "jsonp",
				crossDomain: true,
				data: {
					action:  'iconic_woosocial_load_more_likes',
					nonce:   iconic_woosocial_vars.nonce,
					limit:   limit,
					offset:  offset,
					user_id: user_id
				},

				success: function( data ) {

					if( data.likes_html ) {

    					iconic_woosocial.els.likes_container.append( data.likes_html );
    					$load_more_button.attr('data-offset', next_offset) ;

					} else {

    					iconic_woosocial.disable_load_more_button( $load_more_button );

					}

					if(callback !== undefined) {
            			callback(data);
            		}

				},

				error: function( data ) {

				}
			});

        },

        load_more_followers: function( $load_more_button, callback ) {


            var limit = parseInt( $load_more_button.attr('data-limit') ),
                offset = parseInt( $load_more_button.attr('data-offset') ),
                next_offset = limit+offset,
                user_id = $load_more_button.attr('data-user-id');

            $.ajax({
				type:        "GET",
				url:         iconic_woosocial_vars.ajax_url,
				cache:       false,
				dataType:    "jsonp",
				crossDomain: true,
				data: {
					action:  'iconic_woosocial_load_more_followers',
					nonce:   iconic_woosocial_vars.nonce,
					limit:   limit,
					offset:  offset,
					user_id: user_id
				},

				success: function( data ) {

    				console.log(data);

					if( data.followers_html ) {

    					iconic_woosocial.els.followers_container.append( data.followers_html );
    					$load_more_button.attr('data-offset', next_offset) ;

					} else {

    					iconic_woosocial.disable_load_more_button( $load_more_button );

					}

					if(callback !== undefined) {
            			callback(data);
            		}

				},

				error: function( data ) {

				}
			});

        },

        load_more_following: function( $load_more_button, callback ) {

            var limit = parseInt( $load_more_button.attr('data-limit') ),
                offset = parseInt( $load_more_button.attr('data-offset') ),
                next_offset = limit+offset,
                user_id = $load_more_button.attr('data-user-id');

            $.ajax({
				type:        "GET",
				url:         iconic_woosocial_vars.ajax_url,
				cache:       false,
				dataType:    "jsonp",
				crossDomain: true,
				data: {
					action:  'iconic_woosocial_load_more_following',
					nonce:   iconic_woosocial_vars.nonce,
					limit:   limit,
					offset:  offset,
					user_id: user_id
				},

				success: function( data ) {

    				console.log(data);

					if( data.following_html ) {

    					iconic_woosocial.els.following_container.append( data.following_html );
    					$load_more_button.attr('data-offset', next_offset) ;

					} else {

    					iconic_woosocial.disable_load_more_button( $load_more_button );

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

        	iconic_woosocial.els.document_body.on('click', iconic_woosocial.vars.add_to_cart_buttons_class, function(){

            	var $button = $(this),
            	    button_text = $button.text();

                $button
                .attr('data-button-text',button_text)
                .append( iconic_woosocial.els.loading_icon );

        	});

        	iconic_woosocial.els.document_body.on( 'added_to_cart', function( e, fragments, cart_hash, $button ) {

            	if( typeof $button === "undefined" ) { return; }

                var $woosocial_button_wrapper = $button.closest('.iconic-woosocial-add-to-cart-wrapper'),
                    button_text = $button.attr('data-button-text');

                if( $woosocial_button_wrapper.length > 0 ) {

                    $button
                    .html( button_text )
                    .append( iconic_woosocial.els.tick_icon );

                    setTimeout(function(){

                        $button.html( button_text );

                    }, 2000);

                }

        	});

    	},

        setup_breakpoints: function() {

            if( iconic_woosocial.els.breakpoint_elements.length > 0 ) {

                // store breakpoint data in cache for future reference

                iconic_woosocial.els.breakpoint_elements.each(function( index, element ){

                    var $element = $(element),
                	    breakpoints = $element.attr('data-breakpoints'),
                	    breakpoints_array = $.parseJSON( breakpoints ),
                        breakpoint_object = {
                        	element: $element,
                            breakpoints: breakpoints_array,
                            width: 0
                        };

                    iconic_woosocial.els.breakpoints.push( breakpoint_object );

                });

            }

            // when an element width has changed, trigger breakpoint update

            iconic_woosocial.els.document_body.on('element_width_change', function( event, breakpoint_object ){

                iconic_woosocial.update_breakpoints( breakpoint_object );

            });

        },

        check_breakpoints: function() {

            if( iconic_woosocial.els.breakpoints.length > 0 ) {

            	$.each(iconic_woosocial.els.breakpoints, function(index, breakpoint_object){

                    var previous_width = breakpoint_object.width,
                        current_width = breakpoint_object.element.width();

                    if( current_width !== previous_width ) {

                        breakpoint_object.width = current_width;

                        iconic_woosocial.els.document_body.trigger('element_width_change', [breakpoint_object]);

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

        	iconic_woosocial.els.document_body.hoverIntent(

        	    function(){

            	    var $card = $(this);

            	    $card.addClass( iconic_woosocial.vars.card_hover_class );

        	    },

        	    function(){

            	    var $card = $(this);

            	    $card.removeClass( iconic_woosocial.vars.card_hover_class );

        	    },

        	    iconic_woosocial.vars.card_grid_cards_class
            );

    	}

    };

	$(document).ready( iconic_woosocial.on_ready() );
	$(window).on('scroll', function() { iconic_woosocial.on_scroll(); } );
	$(window).on('resize', function() { iconic_woosocial.on_resize(); } );

}(jQuery, document));