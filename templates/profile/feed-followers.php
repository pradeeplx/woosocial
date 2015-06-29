<?php global $JCK_WooSocial; ?>

<?php echo '<pre>'.print_r($JCK_WooSocial->follow_system->get_followers( $JCK_WooSocial->profile_system->user_info->ID ),true).'</pre>'; ?>