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

<h1><?php echo $user_info->user_nicename; ?></h1>

<?php // wp_login_form(); ?>

<?php echo $JCK_WooSocial->follow_system->get_follow_button( $user_info ); ?>

<h3><?php echo $user_info->user_nicename; ?>'s Activity</h3>
<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'activity' ); ?>

<h3>Likes (<?php echo $user_info->likes_count; ?>)</h3>

<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'likes' ); ?>

<h3>Followers (<?php echo $user_info->followers_count; ?>)</h3>

<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'followers' ); ?>

<h3>Following (<?php echo $user_info->following_count; ?>)</h3>

<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'following' ); ?>

<?php get_footer(); ?>
