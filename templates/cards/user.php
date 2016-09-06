<?php
/**	=============================
    *
    * iconic_woosocial_before_user_card hook
    *
    ============================= */

	do_action( 'iconic_woosocial_before_user_card', $user );
?> 

    <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card--user <?php if( get_current_user_id() == $user->ID ) echo $GLOBALS['iconic_woosocial']->slug . "-card--self"; ?>">
        
        <?php
        /**	=============================
            *
            * iconic_woosocial_user_card_before_content hook
            *
            * @hooked user_image - 10
            *
            ============================= */
        
        	do_action( 'iconic_woosocial_user_card_before_content', $user );
        ?>
        
        <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-card__content">
        
            <?php
            /**	=============================
                *
                * iconic_woosocial_user_card_content hook
                *
                * @hooked user_name - 10
                * @hooked user_follow_button - 20
                * @hooked user_stats - 30
                *
                ============================= */
            
            	do_action( 'iconic_woosocial_user_card_content', $user );
            ?>
        
        </div>
        
        <?php
        /**	=============================
            *
            * iconic_woosocial_user_card_after_content hook
            *
            ============================= */
        
        	do_action( 'iconic_woosocial_user_card_after_content', $user );
        ?>
        
    </div>

<?php
/**	=============================
    *
    * iconic_woosocial_after_user_card hook
    *
    ============================= */

	do_action( 'iconic_woosocial_after_user_card', $user );
?> 