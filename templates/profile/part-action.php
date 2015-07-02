<li class="<?php echo $JCK_WooSocial->slug; ?>-action">
    <div class="<?php echo $JCK_WooSocial->slug; ?>-action__images">
        <div class="<?php echo $JCK_WooSocial->slug; ?>-action__image <?php echo $JCK_WooSocial->slug; ?>-action__image--rel"><?php echo $action->rel_image; ?></div>
        <div class="<?php echo $JCK_WooSocial->slug; ?>-action__image <?php echo $JCK_WooSocial->slug; ?>-action__image--user"><?php echo $action->user_image; ?></div>
    </div>
    
    <div class="<?php echo $JCK_WooSocial->slug; ?>-action__text"><?php echo $action->formatted; ?></div>
    <small class="<?php echo $JCK_WooSocial->slug; ?>-action__time"><?php echo $action->formatted_date; ?></small>
</li>