<a href="<?php echo $href; ?>" class="<?php echo $button_classes; ?>" data-type="<?php echo is_user_logged_in() ? $type : "login"; ?>" data-product-id="<?php echo $product_id; ?>">
    
    <?php $icon = ( $type == "like" ) ? "heart" : "heart-full"; ?>
    
    <i class="jck-woosocial-ic-<?php echo $icon; ?>"></i> <span class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-product-like-button__count"><?php echo $product_likes_count; ?></span>
</a>