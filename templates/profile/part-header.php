<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-header">
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-header__avatar"><?php echo $user_info->avatar; ?></div>
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-header__info">
        <h1 class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-header__info-username"><?php echo $user_info->user_nicename; ?></h1>    
        <?php echo $GLOBALS['jck_woosocial']->follow_system->get_follow_button( $user_info ); ?>
    </div>

</div>