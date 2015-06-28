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
$profile_info = $JCK_WooSocial->profile_system->get_user_info();

//$JCK_WooSocial->activity_log->add_like( $profile_info->ID, 72 );
//$JCK_WooSocial->activity_log->add_follow( $profile_info->ID, 1 );
?>

<h1><?php echo $profile_info->user_nicename; ?> - Follow</h1>
<p><?php echo $profile_info->tagline; ?></p>
<p><?php echo $profile_info->likes_count; ?> likes - <?php echo $profile_info->followers_count; ?> followers - <?php echo $profile_info->following_count; ?> following</p>

<h3>Friends Activity Feed</h3>

<ul>
    <li>Recently liked</li>
    <li>Recently Followed</li>
    <li>Recently Following</li>
</ul>

<h3>Logged in Activity Feed</h3>

<ul>
    <li>Recently liked</li>
    <li>Recently Followed</li>
    <li>Recently Following</li>
</ul>

<h3>Like Feed</h3>

<h3>Followers</h3>

<h3>Following</h3>

<?php get_footer(); ?>
