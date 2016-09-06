<?php $activity_feed = $GLOBALS['iconic_woosocial']->activity_log->get_activity_feed( $GLOBALS['iconic_woosocial']->profile_system->user_info->ID ); ?>

<div id="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-activity" class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-tab-content <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-tab-content--active">
    
    <?php include($GLOBALS['iconic_woosocial']->templates->locate_template( 'activity/part-feed.php' )); ?>

</div>