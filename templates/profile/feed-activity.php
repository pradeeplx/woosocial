<?php global $JCK_WooSocial; ?>

<?php $activity_feed = $JCK_WooSocial->activity_log->get_activity_feed( $JCK_WooSocial->profile_system->user_info->ID ); ?>

<div id="<?php echo $JCK_WooSocial->slug; ?>-activity" class="<?php echo $JCK_WooSocial->slug; ?>-tab-content <?php echo $JCK_WooSocial->slug; ?>-tab-content--active">
    
    <?php include($JCK_WooSocial->templates->locate_template( 'activity/part-feed.php' )); ?>

</div>