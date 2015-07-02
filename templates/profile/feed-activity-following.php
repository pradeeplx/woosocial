<?php 
global $JCK_WooSocial;
$current_user_id = get_current_user_id();
    
$activity_feed = $JCK_WooSocial->activity_log->get_following_activity_feed( $current_user_id );

include($JCK_WooSocial->templates->locate_template( 'profile/part-activity-feed.php' ));