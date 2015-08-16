<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card <?php echo $GLOBALS['jck_woosocial']->slug; ?>-card--product">
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__image">
        <?php echo $product->image_link; ?>
    </div>
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__content">
    
        <h2 class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__title">
            <a href="#"><?php echo $product->title; ?></a>
        </h2>
        
        <span class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card--product__price">
            <?php echo $product->price_html; ?>
        </span>
        
        <?php echo $product->add_to_cart_button; ?>
    
    </div>
    
    <?php $GLOBALS['jck_woosocial']->like_system->show_likes_loop( $product->id ); ?>
    
</div>