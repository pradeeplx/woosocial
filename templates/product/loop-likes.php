<?php $location = ( is_archive() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) ? "loop" : "single"; ?>

<ul class="jck-woo-social-likes jck-woo-social-likes--<?php echo $location; ?>">
            
    <?php $like_button = $GLOBALS['jck_woosocial']->like_system->get_like_button( array( 'product_id' => $product_id ) ); ?>
        
    <li class="jck-woo-social-likes__item jck-woo-social-likes__item--like-button"><?php echo $like_button; ?></li>
            
    <?php if( $product_likes && !empty($product_likes) ) { ?>
            
        <?php foreach($product_likes as $product_like ) { ?>
                    
            <?php echo $GLOBALS['jck_woosocial']->like_system->get_like_list_item( $product_like->user_id ); ?>
                    
        <?php } ?>
            
    <?php } ?>
        
</ul>