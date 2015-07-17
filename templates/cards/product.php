<div>
    <?php echo $product->image_link; ?>
    <h2><a href="#"><?php echo $product->title; ?></a></h2>
    <?php echo $product->add_to_cart_button; ?>
    
    <?php $JCK_WooSocial->like_system->show_likes_loop( $product->id ); ?>
</div>