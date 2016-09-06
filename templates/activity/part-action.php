<li class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action--<?php echo $action->id; ?> <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action--<?php echo $action->type; ?> <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-clear">
    
    <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action__icon <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action__icon--<?php echo $action->type; ?>">
		<i class="iconic-woosocial-ic-<?php echo $action->icon; ?>"></i>
	</div>

	<div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action__wrapper">
		<span class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action__description"><?php echo $action->user->avatar_link; ?> <?php echo $action->formatted; ?></span>
		
		<div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action__content">
		    
		    <?php if($action->type == "follow") { ?>
		    
		        <?php $user = ( $GLOBALS['iconic_woosocial']->profile_system->user_info->ID === $action->rel_id ) ? $action->user : $action->user_2; ?>     
		        <?php include($GLOBALS['iconic_woosocial']->templates->locate_template( 'cards/user.php' )); ?>
		        
		    <?php } else { ?>
		    
		        <?php $product = $action->product; ?>
		        <?php include($GLOBALS['iconic_woosocial']->templates->locate_template( 'cards/product.php' )); ?>
		    
		    <?php } ?>
		    
		</div>
		
		<span class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-action__date"><?php echo $action->formatted_date; ?></span>
	</div>
    
</li>