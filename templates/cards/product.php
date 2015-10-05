<?php
/**	=============================
    *
    * jck_woosocial_before_product_card hook
    *
    ============================= */

	do_action( 'jck_woosocial_before_product_card', $product );
?> 

    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card <?php echo $GLOBALS['jck_woosocial']->slug; ?>-card--product">
        
        <?php
        /**	=============================
            *
            * jck_woosocial_product_card_before_content hook
            *
            * @hooked product_image - 10
            *
            ============================= */
        
        	do_action( 'jck_woosocial_product_card_before_content', $product );
        ?>
        
        <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__content">
            
            <?php
            /**	=============================
                *
                * jck_woosocial_product_card_content hook
                *
                * @hooked product_title - 10
                * @hooked product_price - 20
                * @hooked product_add_to_cart - 30
                * @hooked product_likes - 40
                *
                ============================= */
            
            	do_action( 'jck_woosocial_product_card_content', $product );
            ?>
        
        </div>
        
        <?php
        /**	=============================
            *
            * jck_woosocial_product_card_after_content hook
            *
            ============================= */
        
        	do_action( 'jck_woosocial_product_card_after_content', $product );
        ?>
        
    </div>

<?php
/**	=============================
    *
    * jck_woosocial_after_product_card hook
    *
    ============================= */

	do_action( 'jck_woosocial_after_product_card', $product );
?> 