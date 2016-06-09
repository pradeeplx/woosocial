<?php 
$location = ( is_archive() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) ? "loop" : "single";
$align_class = $GLOBALS['jck_woosocial']->like_system->get_likes_alignment_class();
?>

<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-product-likes-wrapper <?php echo $align_class; ?>">

    <ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-product-likes <?php echo $GLOBALS['jck_woosocial']->slug; ?>-product-likes--<?php echo $location; ?>">
                
        <?php $like_button = $GLOBALS['jck_woosocial']->like_system->get_like_button( array( 'product_id' => $product_id ) ); ?>
            
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-product-likes__item <?php echo $GLOBALS['jck_woosocial']->slug; ?>-product-likes__item--like-button"><?php echo $like_button; ?></li>
                
        <?php if( $product_likes && !empty($product_likes) && $GLOBALS['jck_woosocial']->settings['likes_likes_show_avatars'] ) { ?>
                
            <?php foreach($product_likes as $product_like ) { ?>
                        
                <?php echo $GLOBALS['jck_woosocial']->like_system->get_like_list_item( $product_like->user_id ); ?>
                        
            <?php } ?>
                
        <?php } ?>
            
    </ul>

</div>