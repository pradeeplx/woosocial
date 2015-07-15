<?php global $JCK_WooSocial; ?>

<div id="<?php echo $JCK_WooSocial->slug; ?>-likes" class="<?php echo $JCK_WooSocial->slug; ?>-tab-content woocommerce">
    
    <?php $likes = $JCK_WooSocial->like_system->get_likes( $JCK_WooSocial->profile_system->user_info->ID ); ?>
    
    <?php woocommerce_product_loop_start(); ?>
        
        <?php foreach( $likes as $like ) { ?>
            
            <?php $post = get_post($like->rel_id); ?>
            <?php setup_postdata( $post ); ?>
            
            <?php wc_get_template_part( 'content', 'product' ); ?>
        
        <?php } ?>
        
        <?php wp_reset_postdata(); ?>

	<?php woocommerce_product_loop_end(); ?>
	
	<?php echo $JCK_WooSocial->get_load_more_button( 'likes' ); ?>
    
</div>