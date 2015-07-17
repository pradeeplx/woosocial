<li class="<?php echo $JCK_WooSocial->slug; ?>-action <?php echo $JCK_WooSocial->slug; ?>-action--<?php echo $action->id; ?> <?php echo $JCK_WooSocial->slug; ?>-action--<?php echo $action->type; ?> <?php echo $JCK_WooSocial->slug; ?>-clear">
    
    <div class="<?php echo $JCK_WooSocial->slug; ?>-action__icon <?php echo $JCK_WooSocial->slug; ?>-action__icon--<?php echo $action->type; ?>">
		<i class="woo-social-ic-<?php echo $action->type; ?>"></i>
	</div>

	<div class="<?php echo $JCK_WooSocial->slug; ?>-action__wrapper">
		<span class="<?php echo $JCK_WooSocial->slug; ?>-action__description"><?php echo $action->user->avatar_link; ?> <?php echo $action->formatted; ?></span>
		
		<div class="<?php echo $JCK_WooSocial->slug; ?>-action__content">
		    
		    <?php if($action->type == "follow") { ?>
		    
		        <?php $user = ( $JCK_WooSocial->profile_system->user_info->ID === $action->rel_id ) ? $action->user : $action->user_2; ?>     
		        <?php include($JCK_WooSocial->templates->locate_template( 'cards/user.php' )); ?>
		        
		    <?php } else { ?>
		    
		        <?php $product = $action->product; ?>
		        <?php include($JCK_WooSocial->templates->locate_template( 'cards/product.php' )); ?>
		    
		    <?php } ?>
		    
		</div>
		
		<span class="<?php echo $JCK_WooSocial->slug; ?>-action__date"><?php echo $action->formatted_date; ?></span>
	</div>
    
</li>