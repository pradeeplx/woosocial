<?php if( $activity_feed ) { ?>

    <ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-actions" data-breakpoints="<?php echo esc_attr( json_encode( $GLOBALS['jck_woosocial']->activity_log->wrapper_breakpoints ) ); ?>">
    
        <?php foreach( $activity_feed as $action ){ ?>
            
            <?php include($GLOBALS['jck_woosocial']->templates->locate_template( 'activity/part-action.php' )); ?>
            
        <?php } ?>
        
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-actions__load-more">
            <?php echo $GLOBALS['jck_woosocial']->get_load_more_button( 'activity' ); ?>
        </li>
    
    </ul>
    
<?php } else { ?>

    <?php if( is_author() ) { ?>
    
        <?php $GLOBALS['jck_woosocial']->wrap_message( sprintf( __("%s has no activity, yet!", 'jck-woosocial'), $GLOBALS['jck_woosocial']->profile_system->user_info->display_name ) ); ?>
    
    <?php } else { ?>
        
        <?php $GLOBALS['jck_woosocial']->wrap_message( __("There's no activity to see, yet! Why not follow some more people?", 'jck-woosocial') ); ?>
    
    <?php } ?>
    
<?php } ?>