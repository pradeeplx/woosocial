<li class="<?php echo $JCK_WooSocial->slug; ?>-action <?php echo $JCK_WooSocial->slug; ?>-action--<?php echo $action->id; ?> <?php echo $JCK_WooSocial->slug; ?>-action--<?php echo $action->type; ?> <?php echo $JCK_WooSocial->slug; ?>-clear">
    
    <div class="<?php echo $JCK_WooSocial->slug; ?>-action__icon cd-location">
		<?php echo $action->user_image; ?>
	</div>

	<div class="<?php echo $JCK_WooSocial->slug; ?>-action__wrapper">
		<span class="<?php echo $JCK_WooSocial->slug; ?>-action__description"><?php echo $action->user_image; ?> <?php echo $action->formatted; ?></span>
		
		<div class="<?php echo $JCK_WooSocial->slug; ?>-action__content">
		    <?php echo $action->rel_image; ?>
		    <?php echo '<pre>'.print_r($action,true).'</pre>'; ?>
		</div>
		
		<span class="cd-date"><?php echo $action->formatted_date; ?></span>
	</div>
    
    <?php /* ?>
    <div class="<?php echo $JCK_WooSocial->slug; ?>-action__images">
        <div class="<?php echo $JCK_WooSocial->slug; ?>-action__image <?php echo $JCK_WooSocial->slug; ?>-action__image--rel"><?php echo $action->rel_image; ?></div>
        <div class="<?php echo $JCK_WooSocial->slug; ?>-action__image <?php echo $JCK_WooSocial->slug; ?>-action__image--user"><?php echo $action->user_image; ?></div>
    </div>
    
    <div class="<?php echo $JCK_WooSocial->slug; ?>-action__text"><?php echo $action->formatted; ?></div>
    <small class="<?php echo $JCK_WooSocial->slug; ?>-action__time"><?php echo $action->formatted_date; ?></small>
    <?php */ ?>
    
</li>