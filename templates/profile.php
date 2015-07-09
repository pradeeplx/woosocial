<?php
/**
 * The template for displaying a profile page
 *
 * @package jck-woo-social
 * @since WooCommerce Social 1.0.0
 */

get_header(); ?>

<?php 
global $JCK_WooSocial;
$user_info = $JCK_WooSocial->profile_system->user_info;
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
    
    <?php include( $JCK_WooSocial->templates->locate_template( 'profile/part-header.php' ) ); ?>
    
    <ul class="<?php echo $JCK_WooSocial->slug; ?>-profile-links">
        <li class="<?php echo $JCK_WooSocial->slug; ?>-profile-links__item"><a href="#<?php echo $JCK_WooSocial->slug; ?>-activity" class="<?php echo $JCK_WooSocial->slug; ?>-profile-link <?php echo $JCK_WooSocial->slug; ?>-tab-link <?php echo $JCK_WooSocial->slug; ?>-tab-link--active"><?php _e('Activity','jck-woo-social'); ?></a></li>
        <li class="<?php echo $JCK_WooSocial->slug; ?>-profile-links__item"><a href="#<?php echo $JCK_WooSocial->slug; ?>-likes" class="<?php echo $JCK_WooSocial->slug; ?>-profile-link <?php echo $JCK_WooSocial->slug; ?>-tab-link"><?php echo $user_info->likes_count_formatted; ?></a></li>
        <li class="<?php echo $JCK_WooSocial->slug; ?>-profile-links__item"><a href="#<?php echo $JCK_WooSocial->slug; ?>-followers" class="<?php echo $JCK_WooSocial->slug; ?>-profile-link <?php echo $JCK_WooSocial->slug; ?>-tab-link"><?php echo $user_info->followers_count_formatted; ?></a></li>
        <li class="<?php echo $JCK_WooSocial->slug; ?>-profile-links__item"><a href="#<?php echo $JCK_WooSocial->slug; ?>-following" class="<?php echo $JCK_WooSocial->slug; ?>-profile-link <?php echo $JCK_WooSocial->slug; ?>-tab-link"><?php echo $user_info->following_count_formatted; ?></a></li>
    </ul>
    
    <?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'activity' ); ?>
    
    <?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'likes' ); ?>
    
    <?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'followers' ); ?>
    
    <?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'following' ); ?>

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