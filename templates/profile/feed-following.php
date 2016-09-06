<div id="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-following" class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-tab-content" data-breakpoints="<?php echo esc_attr( json_encode( $GLOBALS['iconic_woosocial']->profile_system->card_grid_breakpoints ) ); ?>">

    <?php $following = $GLOBALS['iconic_woosocial']->follow_system->get_following( $GLOBALS['iconic_woosocial']->profile_system->user_info->ID, $GLOBALS['iconic_woosocial']->activity_log->default_limit ); ?>
    
    <?php if( $following ) { ?>
    
        <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-following <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card-grid">
    
            <?php foreach( $following as $user ) { ?>
                
                <?php $user = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $user->rel_id ); ?>            
                <?php include( $GLOBALS['iconic_woosocial']->templates->locate_template( 'cards/user.php' ) ); ?>
                
            <?php } ?>
        
        </div>
        
        <?php if( $GLOBALS['iconic_woosocial']->activity_log->default_limit < $GLOBALS['iconic_woosocial']->profile_system->user_info->following_count ) echo $GLOBALS['iconic_woosocial']->get_load_more_button( 'following' ); ?>

    <?php } else { ?>
    
        <?php $GLOBALS['iconic_woosocial']->wrap_message( sprintf( __("%s hasn't followed anyone, yet!", 'iconic-woosocial'), $GLOBALS['iconic_woosocial']->profile_system->user_info->display_name ) ); ?>
    
    <?php } ?>

</div>