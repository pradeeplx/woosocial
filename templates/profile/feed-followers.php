<?php global $JCK_WooSocial; ?>

<div id="<?php echo $JCK_WooSocial->slug; ?>-followers" class="<?php echo $JCK_WooSocial->slug; ?>-tab-content">
    
    <?php $followers = $JCK_WooSocial->follow_system->get_followers( $JCK_WooSocial->profile_system->user_info->ID ); ?>
    
    <?php foreach( $followers as $user ) { ?>
        
        <?php $user = $JCK_WooSocial->profile_system->get_user_info( $user->user_id ); ?>            
        <?php include( $JCK_WooSocial->templates->locate_template( 'cards/user.php' ) ); ?>
        
    <?php } ?>

</div>