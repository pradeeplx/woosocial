<?php $activity_feed = $GLOBALS['jck_woosocial']->activity_log->get_activity_feed( $GLOBALS['jck_woosocial']->profile_system->user_info->ID ); ?>

<div id="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-activity" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-content <?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-content--active">
    
    <?php include($GLOBALS['jck_woosocial']->templates->locate_template( 'activity/part-feed.php' )); ?>

</div>