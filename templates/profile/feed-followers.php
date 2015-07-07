<?php global $JCK_WooSocial; ?>

<div id="<?php echo $JCK_WooSocial->slug; ?>-followers" class="<?php echo $JCK_WooSocial->slug; ?>-tab-content">

    <?php echo '<pre>'.print_r($JCK_WooSocial->follow_system->get_followers( $JCK_WooSocial->profile_system->user_info->ID ),true).'</pre>'; ?>

</div>