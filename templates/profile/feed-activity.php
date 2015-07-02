<?php 
global $JCK_WooSocial;
    
$activity_feed = $JCK_WooSocial->activity_log->get_activity_feed( $JCK_WooSocial->profile_system->user_info->ID );

include($JCK_WooSocial->templates->locate_template( 'profile/part-activity-feed.php' ));