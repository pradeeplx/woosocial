<?php global $JCK_WooSocial; ?>

<div id="<?php echo $JCK_WooSocial->slug; ?>-following" class="<?php echo $JCK_WooSocial->slug; ?>-tab-content">

    <?php $following = $JCK_WooSocial->follow_system->get_following( $JCK_WooSocial->profile_system->user_info->ID ); ?>

    <ul>
    
        <?php foreach( $following as $user ) { ?>
            
            <?php $user = $JCK_WooSocial->profile_system->get_user_info( $user->rel_id ); ?>
            
            <?php include( $JCK_WooSocial->templates->locate_template( 'cards/user.php' ) ); ?>
            
        <?php } ?>
    
    </ul>

</div>