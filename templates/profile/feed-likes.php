<?php global $JCK_WooSocial; ?>

<div id="<?php echo $JCK_WooSocial->slug; ?>-likes" class="<?php echo $JCK_WooSocial->slug; ?>-tab-content">

    <?php echo '<pre>'.print_r($JCK_WooSocial->like_system->get_likes( $JCK_WooSocial->profile_system->user_info->ID ),true).'</pre>'; ?>
    
</div>