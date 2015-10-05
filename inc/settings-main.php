<?php
/**
 * WordPress Settings Framework
 *
 * @author Gilbert Pellegrom, James Kemp
 * @link https://github.com/gilbitron/WordPress-Settings-Framework
 * @license MIT
 */

/**
 * Define your settings
 */

add_filter( 'wpsf_register_settings_jck_woosocial', 'jck_woosocial_settings' );

function jck_woosocial_settings( $wpsf_settings ) {
    
    // Tabs
    
    // Tab 1
    $wpsf_settings['tabs'][] = array(
		'id' => 'activity',
		'title' => __('Activity Feed'),
	);
	
	// Tab 2
	$wpsf_settings['tabs'][] = array(
		'id' => 'profile',
		'title' => __('Profile'),
	);
	
	// Tab 3
	$wpsf_settings['tabs'][] = array(
		'id' => 'likes',
		'title' => __('Likes','jck-woosocial'),
	);
	
	// Colours
	$wpsf_settings['tabs'][] = array(
		'id' => 'colours',
		'title' => __('Colours','jck-woosocial'),
	);

    // Activity Feed Settings
    
    // Activity Feed Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'activity',
        'section_id' => 'activity_feed',
        'section_title' => __('Activity Feed Settings','jck-woosocial'),
        'section_order' => 5,
        'fields' => array(
            array(
                'id' => 'slug',
                'title' => __('Activity Feed Slug','jck-woosocial'),
                'desc' => __("The slug to use for the logged in user's activity feed. E.g. yourdomain.com/activity/ <br>If you change this, make sure to visit the <a href='".admin_url('options-permalink.php')."' target='_blank'>permalinks page</a>. This will refresh your URLs; you don't need to click anything, just visit the page!",'jck-woosocial'),
                'placeholder' => '',
                'type' => 'text',
                'default' => 'activity'
            ),
            array(
                'id' => 'items_per_page',
                'title' => __('Items per Page','jck-woosocial'),
                'desc' => __('The number of activity items to display before the "load more" button','jck-woosocial'),
                'placeholder' => '',
                'type' => 'text',
                'default' => '8'
            ),
        )
    );
    
    // Product Cards Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'activity',
        'section_id' => 'product_cards',
        'section_title' => __('Product Card Settings','jck-woosocial'),
        'section_order' => 10,
        'fields' => array(
            array(
                'id' => 'show_title',
                'title' => __('Show Title','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'show_price',
                'title' => __('Show Price','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'show_image',
                'title' => __('Show Image','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'show_add_to_cart_button',
                'title' => __('Show Add to Cart button','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'show_likes',
                'title' => __('Show Likes','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
        )
    );
    
    // User Cards Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'activity',
        'section_id' => 'user_cards',
        'section_title' => __('User Card Settings','jck-woosocial'),
        'section_order' => 15,
        'fields' => array(
            array(
                'id' => 'show_image',
                'title' => __('Show Image','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'show_name',
                'title' => __('Show Name','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'show_follow_button',
                'title' => __('Show Follow Button','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'show_user_stats',
                'title' => __('Show User Stats','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
        )
    );
    
    // Profile Settings
    
    // Profile Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'profile',
        'section_id' => 'profile',
        'section_title' => __('Profile Settings','jck-woosocial'),
        'section_order' => 5,
        'fields' => array(
            array(
                'id' => 'profile_slug',
                'title' => __('Profile Slug','jck-woosocial'),
                'desc' => __("The slug to use in a user's profile URL. E.g. yourdomain.com/profile/username <br>If you change this, make sure to visit the <a href='".admin_url('options-permalink.php')."' target='_blank'>permalinks page</a>. This will refresh your URLs; you don't need to click anything, just visit the page!",'jck-woosocial'),
                'placeholder' => '',
                'type' => 'text',
                'default' => 'profile'
            ),
        )
    );
    
    // Likes Settings
    
    // Likes Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'likes',
        'section_id' => 'likes',
        'section_title' => __('Likes Settings','jck-woosocial'),
        'section_order' => 5,
        'fields' => array(
            array(
                'id' => 'show_avatars',
                'title' => __('Show User Avatars','jck-woosocial'),
                'type' => 'checkbox',
                'default' => 1
            ),
            array(
                'id' => 'category_display',
                'title' => __('Category Display','jck-woosocial'),
                'desc' => __('Where to display the like button on a category page.','jck-woosocial'),
                'type' => 'select',
                'default' => 'woocommerce_after_shop_loop_item',
                'choices' => array(
                    'none'                                    => __('None','jck-woosocial'),
                    'woocommerce_before_shop_loop_item'       => __('Before Item','jck-woosocial'),
                    'woocommerce_before_shop_loop_item_title' => __('Before Title','jck-woosocial'),
                    'woocommerce_after_shop_loop_item_title'  => __('After Title','jck-woosocial'),
                    'woocommerce_after_shop_loop_item'        => __('After Item','jck-woosocial'),
                )
            ),
            array(
                'id' => 'search_display',
                'title' => __('Search Results Display','jck-woosocial'),
                'desc' => __('Where to display the like button on a search results page.','jck-woosocial'),
                'type' => 'select',
                'default' => 'woocommerce_after_shop_loop_item',
                'choices' => array(
                    'none'                                    => __('None','jck-woosocial'),
                    'woocommerce_before_shop_loop_item'       => __('Before Item','jck-woosocial'),
                    'woocommerce_before_shop_loop_item_title' => __('Before Title','jck-woosocial'),
                    'woocommerce_after_shop_loop_item_title'  => __('After Title','jck-woosocial'),
                    'woocommerce_after_shop_loop_item'        => __('After Item','jck-woosocial'),
                )
            ),
            array(
                'id' => 'product_display',
                'title' => __('Product Page Display','jck-woosocial'),
                'desc' => __('Where to display the like button on a product page.','jck-woosocial'),
                'type' => 'select',
                'default' => 'woocommerce_single_product_summary/1',
                'choices' => array(
                    'none'                                      => __('None','jck-woosocial'),
                    'woocommerce_before_single_product'         => __('Before Product','jck-woosocial'),
                    'woocommerce_before_single_product_summary' => __('Before Summary','jck-woosocial'),
                    'woocommerce_single_product_summary/1'      => __('In Summary - Start','jck-woosocial'),
                    'woocommerce_single_product_summary/99'     => __('In Summary - End','jck-woosocial'),
                    'woocommerce_after_single_product_summary'  => __('After Summary','jck-woosocial'),
                    'woocommerce_after_single_product'          => __('After Product','jck-woosocial'),
                )
            ),
        )
    );
    
    // Colour Settings
    
    // Colours Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'colours',
        'section_id' => 'colours_general',
        'section_title' => __('General','jck-woosocial'),
        'section_order' => 5,
        'fields' => array(
            array(
                'id' => 'general_button',
                'title' => __('General Button','jck-woosocial'),
                'type' => 'color',
                'default' => '#3498db'
            ),
            array(
                'id' => 'general_button_hover',
                'title' => __('General Button Hover','jck-woosocial'),
                'type' => 'color',
                'default' => '#258cd1'
            ),
        )
    );
    
    // Colours - Likes Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'colours',
        'section_id' => 'colours_likes',
        'section_title' => __('Likes','jck-woosocial'),
        'section_order' => 10,
        'fields' => array(
            array(
                'id' => 'background',
                'title' => __('Background','jck-woosocial'),
                'type' => 'color',
                'default' => '#9b59b6'
            ),
            array(
                'id' => 'background_hover',
                'title' => __('Background Hover','jck-woosocial'),
                'type' => 'color',
                'default' => '#9b59b6'
            ),
            array(
                'id' => 'foreground',
                'title' => __('Foreground','jck-woosocial'),
                'type' => 'color',
                'default' => '#ffffff'
            ),
            array(
                'id' => 'foreground_hover',
                'title' => __('Foreground Hover','jck-woosocial'),
                'type' => 'color',
                'default' => '#ffffff'
            ),
        )
    );
    
    // Colours - Follow Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'colours',
        'section_id' => 'colours_follow',
        'section_title' => __('Follow','jck-woosocial'),
        'section_order' => 15,
        'fields' => array(
            array(
                'id' => 'background',
                'title' => __('Background','jck-woosocial'),
                'type' => 'color',
                'default' => '#31cc71'
            ),
            array(
                'id' => 'background_hover',
                'title' => __('Background Hover','jck-woosocial'),
                'type' => 'color',
                'default' => '#31cc71'
            ),
            array(
                'id' => 'foreground',
                'title' => __('Foreground','jck-woosocial'),
                'type' => 'color',
                'default' => '#ffffff'
            ),
            array(
                'id' => 'foreground_hover',
                'title' => __('Foreground Hover','jck-woosocial'),
                'type' => 'color',
                'default' => '#ffffff'
            ),
        )
    );
    
    // Colours - Cards Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'colours',
        'section_id' => 'colours_cards',
        'section_title' => __('Cards','jck-woosocial'),
        'section_order' => 20,
        'fields' => array(
            array(
                'id' => 'border',
                'title' => __('Border','jck-woosocial'),
                'type' => 'color',
                'default' => '#e7e9ed'
            ),
            array(
                'id' => 'background',
                'title' => __('Background','jck-woosocial'),
                'type' => 'color',
                'default' => '#ffffff'
            ),
        )
    );
    
    // Colours - Profile Tabs Section
    $wpsf_settings['sections'][] = array(
        'tab_id' => 'colours',
        'section_id' => 'colours_profile_tabs',
        'section_title' => __('Profile Tabs','jck-woosocial'),
        'section_order' => 25,
        'fields' => array(
            array(
                'id' => 'background',
                'title' => __('Background','jck-woosocial'),
                'type' => 'color',
                'default' => '#ffffff'
            ),
            array(
                'id' => 'background_hover',
                'title' => __('Background Hover','jck-woosocial'),
                'type' => 'color',
                'default' => '#ffffff'
            ),
            array(
                'id' => 'foreground',
                'title' => __('Foreground','jck-woosocial'),
                'type' => 'color',
                'default' => '#95a5a6'
            ),
            array(
                'id' => 'foreground_hover',
                'title' => __('Foreground Hover','jck-woosocial'),
                'type' => 'color',
                'default' => '#2c3e50'
            ),
            array(
                'id' => 'active_background',
                'title' => __('Active Background','jck-woosocial'),
                'type' => 'color',
                'default' => '#f1f4f8'
            ),
            array(
                'id' => 'active_foreground',
                'title' => __('Active Foreground','jck-woosocial'),
                'type' => 'color',
                'default' => '#2c3e50'
            ),
        )
    );

    return $wpsf_settings;
}