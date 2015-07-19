<div class="<?php echo $JCK_WooSocial->slug; ?>-card <?php echo $JCK_WooSocial->slug; ?>-card--product">
    
    <div class="<?php echo $JCK_WooSocial->slug; ?>-card__image">
        <?php echo $product->image_link; ?>
    </div>
    
    <div class="<?php echo $JCK_WooSocial->slug; ?>-card__content">
    
        <h2 class="<?php echo $JCK_WooSocial->slug; ?>-card__title">
            <a href="#"><?php echo $product->title; ?></a>
        </h2>
        
        <?php echo $product->add_to_cart_button; ?>
    
    </div>
    
    <?php $JCK_WooSocial->like_system->show_likes_loop( $product->id ); ?>
    
</div>