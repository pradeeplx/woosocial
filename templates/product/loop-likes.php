<?php $location = ( is_archive() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) ? "loop" : "single"; ?>

<ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-likes <?php echo $GLOBALS['jck_woosocial']->slug; ?>-likes--<?php echo $location; ?>">
            
    <?php $like_button = $GLOBALS['jck_woosocial']->like_system->get_like_button( array( 'product_id' => $product_id ) ); ?>
        
    <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-likes__item <?php echo $GLOBALS['jck_woosocial']->slug; ?>-likes__item--like-button"><?php echo $like_button; ?></li>
            
    <?php if( $product_likes && !empty($product_likes) ) { ?>
            
        <?php foreach($product_likes as $product_like ) { ?>
                    
            <?php echo $GLOBALS['jck_woosocial']->like_system->get_like_list_item( $product_like->user_id ); ?>
                    
        <?php } ?>
            
    <?php } ?>
        
</ul>