<?php if( $activity_feed ) { ?>

    <ul class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-actions" data-breakpoints="<?php echo esc_attr( json_encode( $GLOBALS['iconic_woosocial']->activity_log->wrapper_breakpoints ) ); ?>">
    
        <?php foreach( $activity_feed as $action ){ ?>
            
            <?php include($GLOBALS['iconic_woosocial']->templates->locate_template( 'activity/part-action.php' )); ?>
            
        <?php } ?>
        
        <li class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-actions__load-more">
            <?php echo $GLOBALS['iconic_woosocial']->get_load_more_button( 'activity' ); ?>
        </li>
    
    </ul>
    
<?php } else { ?>

    <?php if( is_author() ) { ?>
    
        <?php $GLOBALS['iconic_woosocial']->wrap_message( sprintf( __("%s has no activity, yet!", 'iconic-woosocial'), $GLOBALS['iconic_woosocial']->profile_system->user_info->display_name ) ); ?>
    
    <?php } else { ?>
        
        <?php $GLOBALS['iconic_woosocial']->wrap_message( __("There's no activity to see, yet! Why not follow some more people?", 'iconic-woosocial') ); ?>
    
    <?php } ?>
    
<?php } ?>