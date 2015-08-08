<?php 

$current_user_id = get_current_user_id();
    
$activity_feed = $GLOBALS['jck_woosocial']->activity_log->get_following_activity_feed( $current_user_id );

include($GLOBALS['jck_woosocial']->templates->locate_template( 'activity/part-feed.php' ));