<div id="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-followers" class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-tab-content" data-breakpoints="<?php echo esc_attr( json_encode( $GLOBALS['iconic_woosocial']->profile_system->card_grid_breakpoints ) ); ?>">    
    
    <?php $followers = $GLOBALS['iconic_woosocial']->follow_system->get_followers( $GLOBALS['iconic_woosocial']->profile_system->user_info->ID, $GLOBALS['iconic_woosocial']->activity_log->default_limit ); ?>
    
    <?php if( $followers ) { ?>
    
        <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-followers <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card-grid">
    
            <?php foreach( $followers as $user ) { ?>
                
                <?php $user = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $user->user_id ); ?>            
                <?php include( $GLOBALS['iconic_woosocial']->templates->locate_template( 'cards/user.php' ) ); ?>
                
            <?php } ?>
        
        </div>
        
        <?php if( $GLOBALS['iconic_woosocial']->activity_log->default_limit < $GLOBALS['iconic_woosocial']->profile_system->user_info->followers_count ) echo $GLOBALS['iconic_woosocial']->get_load_more_button( 'followers' ); ?>
    
    <?php } else { ?>
    
        <?php $GLOBALS['iconic_woosocial']->wrap_message( sprintf( __("%s hasn't got any followers, yet!", 'iconic-woosocial'), $GLOBALS['iconic_woosocial']->profile_system->user_info->display_name ) ); ?>
    
    <?php } ?>

</div>