<?php
if( $activity_feed ) {
    
    echo '<ul class="'.$JCK_WooSocial->slug.'-actions">';
    
        foreach( $activity_feed as $action ){
            
            include($JCK_WooSocial->templates->locate_template( 'profile/part-action.php' ));
            
        }
    
    echo '</ul>';
    
}