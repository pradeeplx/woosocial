(function($, document) {
    
    var plugin = {
 
        on_ready: function() {
            // on ready stuff here
            plugin.a_function();
        },
     
        a_function: function() {
            // function stuff here
        }
     
    };
    
	$(document).ready( plugin.on_ready() );

}(jQuery, document));