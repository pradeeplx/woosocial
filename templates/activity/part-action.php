<li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-action <?php echo $GLOBALS['jck_woosocial']->slug; ?>-action--<?php echo $action->id; ?> <?php echo $GLOBALS['jck_woosocial']->slug; ?>-action--<?php echo $action->type; ?> <?php echo $GLOBALS['jck_woosocial']->slug; ?>-clear">
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-action__icon <?php echo $GLOBALS['jck_woosocial']->slug; ?>-action__icon--<?php echo $action->type; ?>">
		<i class="jck-woosocial-ic-<?php echo $action->icon; ?>"></i>
	</div>

	<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-action__wrapper">
		<span class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-action__description"><?php echo $action->user->avatar_link; ?> <?php echo $action->formatted; ?></span>
		
		<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-action__content">
		    
		    <?php if($action->type == "follow") { ?>
		    
		        <?php $user = ( $GLOBALS['jck_woosocial']->profile_system->user_info->ID === $action->rel_id ) ? $action->user : $action->user_2; ?>     
		        <?php include($GLOBALS['jck_woosocial']->templates->locate_template( 'cards/user.php' )); ?>
		        
		    <?php } else { ?>
		    
		        <?php $product = $action->product; ?>
		        <?php include($GLOBALS['jck_woosocial']->templates->locate_template( 'cards/product.php' )); ?>
		    
		    <?php } ?>
		    
		</div>
		
		<span class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-action__date"><?php echo $action->formatted_date; ?></span>
	</div>
    
</li>