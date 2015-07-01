<?php 
global $JCK_WooSocial;
$current_user_id = get_current_user_id();
?>

<?php echo '<pre>'.print_r($JCK_WooSocial->activity_log->get_following_activity_feed( $current_user_id ),true).'</pre>'; ?>