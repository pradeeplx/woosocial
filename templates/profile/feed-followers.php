<div id="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-followers" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-content">
    
    <?php $followers = $GLOBALS['jck_woosocial']->follow_system->get_followers( $GLOBALS['jck_woosocial']->profile_system->user_info->ID ); ?>
    
    <?php foreach( $followers as $user ) { ?>
        
        <?php $user = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $user->user_id ); ?>            
        <?php include( $GLOBALS['jck_woosocial']->templates->locate_template( 'cards/user.php' ) ); ?>
        
    <?php } ?>

</div>