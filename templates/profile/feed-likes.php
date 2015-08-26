<div id="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-user-likes" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-content woocommerce">
    
    <?php $likes = $GLOBALS['jck_woosocial']->like_system->get_likes( $GLOBALS['jck_woosocial']->profile_system->user_info->ID ); ?>
    
    <?php if( $likes ) { ?>
        
        <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-user-likes <?php echo $GLOBALS['jck_woosocial']->slug; ?>-card-grid">
            
            <?php foreach( $likes as $like ) { ?>
                
                <?php $product = $GLOBALS['jck_woosocial']->like_system->get_product_info( $like->rel_id ); ?>            
                <?php include($GLOBALS['jck_woosocial']->templates->locate_template( 'cards/product.php' )); ?>
            
            <?php } ?>
            
        </div>
	
        <?php if( $GLOBALS['jck_woosocial']->activity_log->default_limit < $GLOBALS['jck_woosocial']->profile_system->user_info->likes_count ) echo $GLOBALS['jck_woosocial']->get_load_more_button( 'likes' ); ?>
	
	<?php } ?>
    
</div>