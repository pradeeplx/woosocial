<div id="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-following" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-content" data-breakpoints="<?php echo esc_attr( json_encode( $GLOBALS['jck_woosocial']->profile_system->card_grid_breakpoints ) ); ?>">

    <?php $following = $GLOBALS['jck_woosocial']->follow_system->get_following( $GLOBALS['jck_woosocial']->profile_system->user_info->ID, $GLOBALS['jck_woosocial']->activity_log->default_limit ); ?>
    
    <?php if( $following ) { ?>
    
        <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-following <?php echo $GLOBALS['jck_woosocial']->slug; ?>-card-grid">
    
            <?php foreach( $following as $user ) { ?>
                
                <?php $user = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $user->rel_id ); ?>            
                <?php include( $GLOBALS['jck_woosocial']->templates->locate_template( 'cards/user.php' ) ); ?>
                
            <?php } ?>
        
        </div>
        
        <?php if( $GLOBALS['jck_woosocial']->activity_log->default_limit < $GLOBALS['jck_woosocial']->profile_system->user_info->following_count ) echo $GLOBALS['jck_woosocial']->get_load_more_button( 'following' ); ?>

    <?php } else { ?>
    
        None
    
    <?php } ?>

</div>