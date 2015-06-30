<?php
    
class JCK_WooSocial_ProfileSystem {
    
    public $custom_author_levels = array( 'author', 'profile' );
    public $user_info;
    
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
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
   	
	public function initiate_hook() {
    	
    	$this->change_author_base();

        if(is_admin()) {
            
        } else {
            
            add_filter( 'author_rewrite_rules',                         array( $this, 'author_rewrite_rules' ) );
            add_filter( 'author_link',                                  array( $this, 'author_link' ), 10, 2 );
            add_filter( 'template_include',                             array( $this, 'profile_template' ), 99 );
            
        }
        
	}

/**	=============================
    *
    * Run after wp is fully set up and $post is accessible (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
	
	public function wp_hook() {
        
    	if( !is_admin() ) {
        	
        	$this->user_info = $this->get_user_info();
        	
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
    
    public function author_link( $link, $author_id ) {
        
        // https://wordpress.org/support/topic/get-a-users-role-by-user-id
        
        if ( 1 == $author_id ) {
            $author_level = 'author';
        } else {
            $author_level = 'profile';
        }
        
        $link = str_replace( '%author_level%', $author_level, $link );
        return $link;
        
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
        
        global $JCK_WooSocial;
    
    	if ( is_author() && strpos( $_SERVER['REQUEST_URI'], 'profile/' ) !== false ) {
    		
    		$profile_template = $JCK_WooSocial->templates->get_template_part( 'profile', '', false );
    		
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
        
        global $wp_query, $JCK_WooSocial;
        
        if( $user_id === null ) {
            
            $user = $wp_query->get_queried_object();
            
        } else {
            
            $user = get_userdata( $user_id );
            
        }
        
        if( $user ) {
            
            $user->likes_count = $JCK_WooSocial->activity_log->get_likes_count( $user->ID );
            $user->followers_count = $JCK_WooSocial->activity_log->get_followers_count( $user->ID );
            $user->following_count = $JCK_WooSocial->activity_log->get_following_count( $user->ID );
        
        }        
        
        return $user;
        
    }
	
}