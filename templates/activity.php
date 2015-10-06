<?php
/**
 * The template for displaying a profile page
 *
 * @package jck-woosocial
 * @since WooSocial 1.0.0
 */
 
if( !is_user_logged_in() ) {
    $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
    wp_safe_redirect( get_permalink( $myaccount_page_id ) ); die;
}

get_header(); ?>

<?php
/**	=============================
    *
    * jck_woosocial_before_profile hook
    *
    * @hooked woocommerce_breadcrumb - 20
    *
    ============================= */

	do_action( 'jck_woosocial_before_activity_feed' );
?>    
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-container <?php echo $GLOBALS['jck_woosocial']->slug; ?>-activity-wrapper <?php echo $GLOBALS['jck_woosocial']->slug; ?>-clear">
    
        <?php $GLOBALS['jck_woosocial']->templates->get_template_part( 'activity/feed', 'following' ); ?>    
    
    </div>

<?php
/**	=============================
    *
    * jck_woosocial_after_profile hook
    *
    * @hooked woocommerce_breadcrumb - 20
    *
    ============================= */

	do_action( 'jck_woosocial_after_activity_feed' );
?>

<?php get_footer(); ?>