<div id="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-likes" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-content woocommerce">
    
    <?php $likes = $GLOBALS['jck_woosocial']->like_system->get_likes( $GLOBALS['jck_woosocial']->profile_system->user_info->ID ); ?>
    
    <?php if( $likes ) { ?>
        
        <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-likes">
            
            <?php foreach( $likes as $like ) { ?>
                
                <?php $product = $GLOBALS['jck_woosocial']->like_system->get_product_info( $like->rel_id ); ?>            
                <?php include($GLOBALS['jck_woosocial']->templates->locate_template( 'cards/product.php' )); ?>
            
            <?php } ?>
            
        </div>
	
        <?php echo $GLOBALS['jck_woosocial']->get_load_more_button( 'likes' ); ?>
	
	<?php } ?>
    
</div>