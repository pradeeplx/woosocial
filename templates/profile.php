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
$current_user_id = get_current_user_id();

//$JCK_WooSocial->activity_log->add_like( 2, 67 );
//$JCK_WooSocial->activity_log->add_follow( 2, 3 );

// https://codex.wordpress.org/Function_Reference/human_time_diff
?>

<h1><?php echo $user_info->user_nicename; ?></h1>

<?php if( $current_user_id != $user_info->ID ) { ?>

    <?php 
    $is_following = $JCK_WooSocial->follow_system->is_following( $current_user_id, $user_info->ID );
    $button_text = ( $is_following ) ? "Unfollow" : "Follow";
    $button_type = ( $is_following ) ? "unfollow" : "follow";
    ?>

    <a href="javascript: void(0);" class="<?php echo $JCK_WooSocial->slug; ?>-follow-action <?php echo $JCK_WooSocial->slug; ?>-follow-action--<?php echo $button_type; ?>" data-user-id="<?php echo $user_info->ID; ?>" data-follow-type="<?php echo $button_type; ?>"><?php echo $button_text; ?></a>

<?php } ?>

<h3><?php echo $user_info->user_nicename; ?>'s Activity</h3>
<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'activity' ); ?>

<h3>Likes (<?php echo $user_info->likes_count; ?>)</h3>

<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'likes' ); ?>

<h3>Followers (<?php echo $user_info->followers_count; ?>)</h3>

<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'followers' ); ?>

<h3>Following (<?php echo $user_info->following_count; ?>)</h3>

<?php $JCK_WooSocial->templates->get_template_part( 'profile/feed', 'following' ); ?>

<?php get_footer(); ?>
