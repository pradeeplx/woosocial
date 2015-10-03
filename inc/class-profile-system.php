<?php
    
class JCK_WooSocial_ProfileSystem {
    
    public $custom_author_levels = array( 'author', 'profile' );
    public $user_info;
    public $profile_base;
    public $wrapper_breakpoints;
    public $card_grid_breakpoints;
    
/**	=============================
    *
    * Construct the class
    *
    ============================= */
   	
    public function __construct() {
        
        add_action( 'init',             array( $this, 'initiate_hook' ) );
        add_action( 'wp',               array( $this, 'wp_hook' ) );
        
    }

/**	=============================
    *
    * Setup Constants for this class
    *
    ============================= */
    
    public function set_constants() {

        $this->profile_base = 'profile';
        
        $this->wrapper_breakpoints = array(
            array(
                "max_width" => 800,
                "class" => sprintf("%s-profile-wrapper--medium", $GLOBALS['jck_woosocial']->slug)
            ),
            array(
                "max_width" => 320,
                "class" => sprintf("%s-profile-wrapper--small", $GLOBALS['jck_woosocial']->slug)
            )
        );
        
        $this->card_grid_breakpoints = array(
            array(
                "max_width" => 820,
                "class" => sprintf("%s-card-grid--medium", $GLOBALS['jck_woosocial']->slug)
            ),
            array(
                "max_width" => 615,
                "class" => sprintf("%s-card-grid--small", $GLOBALS['jck_woosocial']->slug)
            ),
            array(
                "max_width" => 400,
                "class" => sprintf("%s-card-grid--xsmall", $GLOBALS['jck_woosocial']->slug)
            )
        );
        
    }

/**	=============================
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
   	
	public function initiate_hook() {
    	
    	$this->set_constants();
    	$this->change_author_base();

        if(is_admin()) {
            
        } else {
            
            add_filter( 'author_rewrite_rules',                         array( $this, 'author_rewrite_rules' ) );
            add_filter( 'template_include',                             array( $this, 'profile_template' ), 99 );
            add_filter( 'woocommerce_login_redirect',                   array( $this, 'login_redirect' ), 10, 2 );
            add_filter( 'nav_menu_link_attributes',                     array( $this, 'nav_menu_profile_link' ), 10, 4 );
            add_filter( 'wp_get_nav_menu_items',                        array( $this, 'nav_menu_items' ), 10, 3 );
            
            add_action( 'jck_woosocial_before_profile',                 array( $this, 'before_profile' ), 5 );
            add_action( 'jck_woosocial_after_profile',                  array( $this, 'after_profile' ), 50 );
            
            add_filter('body_class',                                    array( $this, 'body_class' ), 10, 1 );
            
        }
        
	}

/**	=============================
    *
    * Run after wp is fully set up and $post is accessible (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
	
	public function wp_hook() {
        
    	if( ( !is_admin() && is_author() ) || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
        	
        	$this->user_info = $this->get_user_info();
        	
    	} else {
        	
        	$this->user_info = wp_get_current_user();
        	
    	}
        
	}

/**	=============================
    *
    * Change author base
    *
    ============================= */
    
    public function change_author_base() {
        
        // http://wordpress.stackexchange.com/questions/17106/change-author-base-slug-for-different-roles
        
        global $wp_rewrite;
    
        // Define the tag and use it in the rewrite rule
        add_rewrite_tag( '%author_level%', '(' . implode( '|', $this->custom_author_levels ) . ')' );
        $wp_rewrite->author_base = '%author_level%';
        
    }
    
    public function author_rewrite_rules( $author_rewrite_rules ) {
        
        foreach ( $author_rewrite_rules as $pattern => $substitution ) {
            if ( FALSE === strpos( $substitution, 'author_name' ) ) {
                unset( $author_rewrite_rules[$pattern] );
            }
        }
        
        return $author_rewrite_rules;
        
    }

/**	=============================
    *
    * Replace Profile Link in menu Item
    *
    ============================= */
    
    public function nav_menu_profile_link( $atts, $item, $args, $depth ) {
        
        if( is_user_logged_in() ) {
            
            $current_user = wp_get_current_user();
        
            $atts['href'] = str_replace('/jck-woosocial', '', $atts['href']);
            $atts['href'] = str_replace('%nicename%', $current_user->user_nicename, $atts['href']);
        
        }
        
        return $atts;
        
    }
    
/**	=============================
    *
    * Edit Menu Items
    *
    * Hide profile and article link if not logged in
    *
    ============================= */
    
    public function nav_menu_items( $items, $menu, $args ) {
        
        $i = 0; foreach( $items as $item ) {
            
            if( strpos( $item->url, "jck-woosocial" ) !== false && !is_user_logged_in() ) {
                
                unset( $items[$i] );
                
            }
            
        $i++; }
        
        return $items;
        
    }

/**	=============================
    *
    * Login Redirect
    *
    * If user clicks to follow or like, redirect
    * to where they were after logging in
    *
    ============================= */
    
    public function login_redirect( $redirect, $user ) {
        
        if( isset( $_POST['_wp_http_referer'] ) ) {
            
            $referer_split = explode( '?', $_POST['_wp_http_referer'] );
            parse_str( $referer_split[1], $referer_params );
            
            if( isset( $referer_params['profile'] ) ) {
                $redirect = $this->get_profile_url( $referer_params['profile'] );
            }
            
            if( isset( $referer_params['like-product'] ) ) {
                $redirect = get_permalink( (int)$referer_params['like-product'] );
            }
            
        }
        
        return $redirect;
        
    }

/**	=============================
    *
    * Get profile URL
    *
    * @param int $author_id 
    * @return str/bool
    *
    ============================= */
    
    public function get_profile_url( $author_nicename ) {
        
        return esc_url( home_url( $this->profile_base.'/'.$author_nicename ) );
        
    }

/**	=============================
    *
    * Get profile Link
    *
    * @param int $author_id 
    * @return str/bool
    *
    ============================= */
    
    public function get_profile_link( $user ) {
        
        $profile_url = $this->get_profile_url( $user->user_nicename );
        $profile_title = esc_attr(sprintf(__("%s - Visit Profile", 'jck-woosocial'), $user->display_name));
        
        return sprintf( '<a href="%s" title="%s">%s</a>', $profile_url, $profile_title, $user->display_name );
        
    }
    
/**	=============================
    *
    * Override profile template
    *
    * @param mixed $anything Description of the parameter
    * @return bool
    *
    ============================= */

    function profile_template( $template ) {
        
    	if ( is_author() && strpos( $_SERVER['REQUEST_URI'], 'profile/' ) !== false ) {
    		
    		$profile_template = $GLOBALS['jck_woosocial']->templates->get_template_part( 'profile', '', false );
    		
    		if ( '' != $profile_template ) {
    			return $profile_template ;
    		}
    		
    	}
    
    	return $template;
    	
    }

/**	=============================
    *
    * Get User info for Current Profile
    *
    * @param mixed $anything Description of the parameter
    * @return bool
    *
    ============================= */
    
    public function get_user_info( $user_id = null ) {
        
        global $wp_query;
        
        if( $user_id === null ) {
            
            $user = $wp_query->get_queried_object();
            
        } else {
            
            $user = get_userdata( $user_id );
            
        }
        
        if( $user ) {
            
            $user->likes_count = $GLOBALS['jck_woosocial']->like_system->get_likes_count( $user->ID );
            $user->followers_count = $GLOBALS['jck_woosocial']->activity_log->get_followers_count( $user->ID );
            $user->following_count = $GLOBALS['jck_woosocial']->activity_log->get_following_count( $user->ID );
            $user->profile_url = $this->get_profile_url( $user->user_nicename );
            $user->profile_link = $this->get_profile_link( $user );
            $user->avatar = get_avatar( $user->ID, get_option( 'thumbnail_size_w' ) );
            $user->avatar_link = sprintf( '<a href="%s" title="%s">%s</a>', esc_attr($user->profile_url), esc_attr($user->display_name), $user->avatar );
            
            $user->likes_count_formatted = sprintf('<i class="%s-ic-heart"></i> %d', $GLOBALS['jck_woosocial']->slug, $user->likes_count );
            $user->followers_count_formatted = sprintf('<i class="%s-ic-followers"></i> %d', $GLOBALS['jck_woosocial']->slug, $user->followers_count );
            $user->following_count_formatted = sprintf('<i class="%s-ic-following"></i> %d', $GLOBALS['jck_woosocial']->slug, $user->following_count );
            
            $user->follow_button = $GLOBALS['jck_woosocial']->follow_system->get_follow_button( $user );
        
        }        
        
        return $user;
        
    }
    
/**	=============================
    *
    * Before Profile
    *
    ============================= */
    
    public function before_profile() {
        
        echo '<div class="jck_woosocial-container jck_woosocial-container--profile">';
        
    }

/**	=============================
    *
    * After Profile
    *
    ============================= */
    
    public function after_profile() {
        
        echo '</div>';
        
    }

/**	=============================
    *
    * Body Class
    *
    * @param arr $classes Array of existing classes
    * @return arr $classes
    *
    ============================= */
    
    public function body_class( $classes ) {
        
        if( $this->is_profile() )
            $classes[] = 'woocommerce';
        
        return $classes;
         
    }

/**	=============================
    *
    * Is Profile
    *
    * Function to check whether current page is a profile page
    *
    * @return bool
    *
    ============================= */
    
    public function is_profile() {
        
        $current_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $profile_url = str_replace( array( 'http://', 'https://' ), '', home_url( $this->profile_base.'/' ) );
        
        return (strpos( $current_url, $profile_url ) !== false) ? true : false;
        
    }
	
}