<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-info">
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-info__avatar"><?php echo $user_info->avatar; ?></div>
    <h1 class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-info__username"><?php echo $user_info->display_name; ?></h1>    
    <?php echo $GLOBALS['jck_woosocial']->follow_system->get_follow_button( $user_info ); ?>
    
    <?php include( $GLOBALS['jck_woosocial']->templates->locate_template( 'profile/part-links.php' ) ); ?>

</div>