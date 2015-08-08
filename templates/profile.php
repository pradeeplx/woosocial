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
    
    <ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links">
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links__item"><a href="#<?php echo $GLOBALS['jck_woosocial']->slug; ?>-activity" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-link <?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-link <?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-link--active"><?php _e('Activity','jck-woo-social'); ?></a></li>
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links__item"><a href="#<?php echo $GLOBALS['jck_woosocial']->slug; ?>-likes" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-link <?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-link"><?php echo $user_info->likes_count_formatted; ?></a></li>
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links__item"><a href="#<?php echo $GLOBALS['jck_woosocial']->slug; ?>-followers" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-link <?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-link"><?php echo $user_info->followers_count_formatted; ?></a></li>
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links__item"><a href="#<?php echo $GLOBALS['jck_woosocial']->slug; ?>-following" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-link <?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-link"><?php echo $user_info->following_count_formatted; ?></a></li>
    </ul>
    
    <?php $GLOBALS['jck_woosocial']->templates->get_template_part( 'profile/feed', 'activity' ); ?>
    
    <?php $GLOBALS['jck_woosocial']->templates->get_template_part( 'profile/feed', 'likes' ); ?>
    
    <?php $GLOBALS['jck_woosocial']->templates->get_template_part( 'profile/feed', 'followers' ); ?>
    
    <?php $GLOBALS['jck_woosocial']->templates->get_template_part( 'profile/feed', 'following' ); ?>

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