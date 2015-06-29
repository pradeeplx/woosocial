<?php global $JCK_WooSocial; ?>

<?php echo '<pre>'.print_r($JCK_WooSocial->activity_log->get_following_activity_feed( $JCK_WooSocial->profile_system->user_info->ID ),true).'</pre>'; ?>