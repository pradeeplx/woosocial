<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card <?php echo $GLOBALS['jck_woosocial']->slug; ?>-card--product">
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__image">
        <?php echo $product->image_link; ?>
        <?php echo $product->add_to_cart_button; ?>
    </div>
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__content">
    
        <h2 class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__title">
            <a href="<?php echo $product->url; ?>" title="<?php echo esc_attr($product->title); ?>"><?php echo $product->title; ?></a>
        </h2>
        
        <span class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card--product__price">
            <?php echo $product->price_html; ?>
        </span>
    
    </div>
    
    <?php $GLOBALS['jck_woosocial']->like_system->show_likes_loop( $product->id ); ?>
    
    <?php echo $product->add_to_cart_button; ?>
    
</div>