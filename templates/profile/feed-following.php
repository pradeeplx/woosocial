<div id="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-following" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-content <?php echo $GLOBALS['jck_woosocial']->slug; ?>-card-grid" data-eq-pts="xsmall: 0, small: 320, medium: 600, large: 802">

    <?php $following = $GLOBALS['jck_woosocial']->follow_system->get_following( $GLOBALS['jck_woosocial']->profile_system->user_info->ID ); ?>
    
    <?php foreach( $following as $user ) { ?>
        
        <?php $user = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $user->rel_id ); ?>            
        <?php include( $GLOBALS['jck_woosocial']->templates->locate_template( 'cards/user.php' ) ); ?>
        
    <?php } ?>

</div>