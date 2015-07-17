<?php global $JCK_WooSocial; ?>

<div id="<?php echo $JCK_WooSocial->slug; ?>-likes" class="<?php echo $JCK_WooSocial->slug; ?>-tab-content woocommerce">
    
    <?php $likes = $JCK_WooSocial->like_system->get_likes( $JCK_WooSocial->profile_system->user_info->ID ); ?>
        
        <?php foreach( $likes as $like ) { ?>
            
            <?php $product = $JCK_WooSocial->like_system->get_product_info( $like->rel_id ); ?>            
            <?php include($JCK_WooSocial->templates->locate_template( 'cards/product.php' )); ?>
        
        <?php } ?>
	
	<?php echo $JCK_WooSocial->get_load_more_button( 'likes' ); ?>
    
</div>