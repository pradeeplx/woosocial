<?php
/**
 * The template for displaying a profile page
 *
 * @package jck-woo-social
 * @since WooCommerce Social 1.0.0
 */

get_header(); ?>

<?php 

$user_info = $GLOBALS['jck_woosocial']->profile_system->user_info;
?>

<?php
/**	=============================
    *
    * jck_woo_social_before_profile hook
    *
    * @hooked woocommerce_breadcrumb - 20
    *
    ============================= */

	do_action( 'jck_woo_social_before_profile' );
?>
    
    <?php include( $GLOBALS['jck_woosocial']->templates->locate_template( 'profile/part-header.php' ) ); ?>
    
    <?php include( $GLOBALS['jck_woosocial']->templates->locate_template( 'profile/part-links.php' ) ); ?>
    
    <?php $feeds = array(
        'activity',
        'likes',
        'followers',
        'following',
    ); ?>
    
    <?php foreach( $feeds as $feed ) { ?>
        
        <?php include( $GLOBALS['jck_woosocial']->templates->locate_template( sprintf( 'profile/feed-%s.php', $feed ) ) ); ?>
        
    <?php } ?>

<?php
/**	=============================
    *
    * jck_woo_social_after_profile hook
    *
    * @hooked woocommerce_breadcrumb - 20
    *
    ============================= */

	do_action( 'jck_woo_social_after_profile' );
?>

<?php get_footer(); ?>