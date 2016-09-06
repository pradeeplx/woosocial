<?php 

$current_user_id = get_current_user_id();
    
$activity_feed = $GLOBALS['iconic_woosocial']->activity_log->get_following_activity_feed( $current_user_id );

include($GLOBALS['iconic_woosocial']->templates->locate_template( 'activity/part-feed.php' ));