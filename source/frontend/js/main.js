(function($, document) {
    
    var jck_woo_social = {
        
        cache: function() {
            jck_woo_social.els = {};
            jck_woo_social.vars = {};
            
            jck_woo_social.els.$actions_list = $('.jck_woo_social-actions');
        },
 
        on_ready: function() {
            
            jck_woo_social.cache();
            jck_woo_social.setup_follow_actions();
            
        },
     
        setup_follow_actions: function() {
            
            $('.jck_woo_social-follow-action').on('click', function(){
                
                var $button = $(this),
                    user_id = $button.attr('data-user-id'),
                    type = $button.attr('data-follow-type'),
                    loading_class = 'jck_woo_social-follow-action--loading';
                
                if( type == "login" ) {
                    
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
            					console.log( 'success' );
            					console.log( data );
            					
            					$button.text(data.button.text);
            					$button.attr('data-follow-type',data.button.type);
            					
            					$button.removeClass(loading_class);
            					$button.removeClass('jck_woo_social-follow-action--'+type);
            					$button.addClass('jck_woo_social-follow-action--'+data.button.type);
            					
            					if(data.add_action_html) {
                					jck_woo_social.els.$actions_list.prepend(data.add_action_html);
            					}
            					
            					if(data.remove_action_class) {
                					$(data.remove_action_class).remove();
            					}
            				},
            				
            				error: function( data ) {
            					console.log( 'error' );
            					console.log( data );
            				}
            			});
        			
        			}
    			
    			}
			
			});
            
        }
     
    };
    
	$(document).ready( jck_woo_social.on_ready() );

}(jQuery, document));