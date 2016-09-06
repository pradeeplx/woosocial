<?php
/**
 * The template for displaying a profile page
 *
 * @package iconic-woosocial
 * @since WooSocial 1.0.0
 */

get_header(); ?>

<?php 

$user_info = $GLOBALS['iconic_woosocial']->profile_system->user_info;

?>

<?php
/**	=============================
    *
    * iconic_woosocial_before_profile hook
    *
    * @hooked woocommerce_breadcrumb - 20
    *
    ============================= */

	do_action( 'iconic_woosocial_before_profile' );
?>    
    
    <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-profile-wrapper <?php echo $GLOBALS['iconic_woosocial']->slug; ?>-clear" data-breakpoints="<?php echo esc_attr( json_encode( $GLOBALS['iconic_woosocial']->profile_system->wrapper_breakpoints ) ); ?>">
    
        <?php include( $GLOBALS['iconic_woosocial']->templates->locate_template( 'profile/part-info.php' ) ); ?>        
        
        <div class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-profile-feeds">
        
            <?php $feeds = array(
                'activity',
                'likes',
                'followers',
                'following',
            ); ?>
            
            <?php foreach( $feeds as $feed ) { ?>
                
                <?php include( $GLOBALS['iconic_woosocial']->templates->locate_template( sprintf( 'profile/feed-%s.php', $feed ) ) ); ?>
                
            <?php } ?>
        
        </div>
    
    </div>

<?php
/**	=============================
    *
    * iconic_woosocial_after_profile hook
    *
    * @hooked woocommerce_breadcrumb - 20
    *
    ============================= */

	do_action( 'iconic_woosocial_after_profile' );
?>

<?php get_footer(); ?>