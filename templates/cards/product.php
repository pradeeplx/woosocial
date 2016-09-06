<?php
/**	=============================
    *
    * iconic_woosocial_before_product_card hook
    *
    ============================= */

	do_action( 'iconic_woosocial_before_product_card', $product );
?>

    <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card--product">

        <?php
        /**	=============================
            *
            * iconic_woosocial_product_card_before_content hook
            *
            * @hooked product_image - 10
            *
            ============================= */

        	do_action( 'iconic_woosocial_product_card_before_content', $product );
        ?>

        <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card__content">

            <?php
            /**	=============================
                *
                * iconic_woosocial_product_card_content hook
                *
                * @hooked product_title - 10
                * @hooked product_price - 20
                * @hooked product_add_to_cart - 30
                * @hooked product_likes - 40
                *
                ============================= */

            	do_action( 'iconic_woosocial_product_card_content', $product );
            ?>

        </div>

        <?php
        /**	=============================
            *
            * iconic_woosocial_product_card_after_content hook
            *
            ============================= */

        	do_action( 'iconic_woosocial_product_card_after_content', $product );
        ?>

    </div>

<?php
/**	=============================
    *
    * iconic_woosocial_after_product_card hook
    *
    ============================= */

	do_action( 'iconic_woosocial_after_product_card', $product );
?>