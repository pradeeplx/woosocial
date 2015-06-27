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
$user_profile_info = $JCK_WooSocial->get_user_profile_info();
?>

<h1><?php echo $user_profile_info->user_nicename; ?> - Follow</h1>
<p><?php echo $user_profile_info->tagline; ?></p>
<p><?php echo $user_profile_info->likes_count; ?> likes - <?php echo $user_profile_info->followers_count; ?> followers - <?php echo $user_profile_info->following_count; ?> following</p>

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
