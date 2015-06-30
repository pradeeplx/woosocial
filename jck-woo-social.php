<?php
/*
Plugin Name: WooCommerce Social
Plugin URI: http://www.jckemp.com
Description: Profiles, likes, and followers - WooCommerce Social
Version: 1.0.0
Author: James Kemp
Author URI: http://www.jckemp.com
Text Domain: jck-woo-social
*/

defined('JCK_WOO_SOCIAL_PLUGIN_PATH') or define('JCK_WOO_SOCIAL_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
defined('JCK_WOO_SOCIAL_PLUGIN_URL') or define('JCK_WOO_SOCIAL_PLUGIN_URL', plugin_dir_url( __FILE__ ));

require_once( dirname( __FILE__ ) . '/inc/admin/class-tgm-plugin-activation.php' );

class JCK_WooSocial {
    
    public $name = 'WooCommerce Social';
    public $shortname = 'Woo Social';
    public $slug = 'jck_woo_social';
    public $version = "1.0.0";
    public $plugin_path = JCK_WOO_SOCIAL_PLUGIN_PATH;
    public $plugin_url = JCK_WOO_SOCIAL_PLUGIN_URL;
    public $options_name;
    public $options;
    public $profile_system;
    public $like_system;
    public $follow_system;
	
/**	=============================
    *
    * Construct the plugin
    *
    ============================= */
   	
    public function __construct() {
        
        $this->set_constants();        
        $this->load_classes();
        
        register_activation_hook( __FILE__, array( $this, 'install' ) );
        
        // Hook up to the init and plugins_loaded actions
        add_action( 'plugins_loaded',   array( $this, 'plugins_loaded_hook' ) );
        add_action( 'init',             array( $this, 'initiate_hook' ) );
        add_action( 'wp',               array( $this, 'wp_hook' ) );
        
    }

/**	=============================
    *
    * Setup Constants for this class
    *
    ============================= */
    
    public function set_constants() {

        $this->options_name = $this->slug.'_options';
        
    }

/**	=============================
    *
    * Install Plugin on Activation
    *
    ============================= */
    
    public function install() {
        
        $this->activity_log->setup_activity_log();
        
    }

/**	=============================
    *
    * Load Classes
    *
    ============================= */
    
    private function load_classes() {
        
        require_once($this->plugin_path.'/inc/class-template-loader.php');
        require_once($this->plugin_path.'/inc/class-profile-system.php');
        require_once($this->plugin_path.'/inc/class-like-system.php');
        require_once($this->plugin_path.'/inc/class-follow-system.php');
        require_once($this->plugin_path.'/inc/class-activity-log-system.php');
        
        $this->templates = new JCK_WooSocial_TemplateLoader();
        $this->profile_system = new JCK_WooSocial_ProfileSystem();
        $this->like_system = new JCK_WooSocial_LikeSystem();
        $this->follow_system = new JCK_WooSocial_FollowSystem();
        $this->activity_log = new JCK_WooSocial_ActivityLogSystem();
        
    }

/**	=============================
    *
    * Run quite near the start (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
   	
	public function plugins_loaded_hook() {
    	
    	load_plugin_textdomain( "jck_woo_social", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        
	}

/**	=============================
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
   	
	public function initiate_hook() {

        if(is_admin()) {
            
            add_action( 'admin_enqueue_scripts',                        array( $this, 'admin_scripts' )); 
            add_action( 'admin_enqueue_scripts',                        array( $this, 'admin_styles' )); 
            
        } else {
            
            add_action( 'wp_enqueue_scripts',                           array( $this, 'frontend_scripts' ) );
            add_action( 'wp_enqueue_scripts',                           array( $this, 'frontend_styles' ) );
            
        }
        
	}

/**	=============================
    *
    * Run after wp is fully set up and $post is accessible (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
	
	public function wp_hook() {
    	
        global $post;
        
    	if(!is_admin()) {
        	
        	$this->remove_frontend_hooks();
        	$this->add_frontend_hooks();
        	$this->options = ( $post ) ? get_post_meta( $post->ID, $this->options_name, true ) : false;
        	
    	}
        
	}
	
/**	=============================
    *
    * Register required plugins
    *
    * Use TGM to determine which plugins are required in order
    * for this plugin to run as it should
    *
    ============================= */
    
    private function register_required_plugins() {
        
    /**	=============================
        *
        * Array of plugin arrays. Required keys are name and slug.
    	* If the source is NOT from the .org repo, then source is also required.
        *
        ============================= */
        
	    $plugins = array(	
	
	        array(
	            'name'      => 'Redux Framework',
	            'slug'      => 'redux-framework',
	            'required'  => true,
	        )
	
	    );
	    
    /**	=============================
        *
        * Array of configuration settings. Amend each line as needed.
        * If you want the default strings to be available under your own theme domain,
        * leave the strings uncommented.
        * Some of the strings are added into a sprintf, so see the comments at the
        * end of each line for what each argument will be.
        *
        ============================= */

	    $config =   array(
                        'id'            =>  $this->slug.'-tgmpa',
                        'default_path'  =>  '',
                        'menu'          =>  $this->slug.'-install-plugins',
                        'has_notices'   =>  true,
                        'dismissable'   =>  false,
                        'dismiss_msg'   =>  '',
                        'is_automatic'  =>  true,
                        'message'       =>  '',
                        'strings'       =>  array(
                            
                                                'page_title'                      => __( 'Install Required Plugins', $this->slug ),
                                                'menu_title'                      => __( 'Install Plugins', $this->slug ),
                                                'installing'                      => __( 'Installing Plugin: %s', $this->slug ),
                                                'oops'                            => __( 'Something went wrong with the plugin API.', $this->slug ),
                                                'notice_can_install_required'     => _n_noop( 'Please install the following required plugin: %1$s.', 'Please install the following required plugins: %1$s.', $this->slug ),
                                                'notice_can_install_recommended'  => _n_noop( 'Please consider this recommended plugin: %1$s.', ' Please consider these recommended plugins: %1$s.', $this->slug ),
                                                'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', $this->slug ), 
                                                'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', $this->slug ),
                                                'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', $this->slug ),
                                                'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', $this->slug ), 
                                                'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with '.$this->name.': %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with '.$this->name.': %1$s.', $this->slug ),
                                                'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', $this->slug ),
                                                'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', $this->slug ),
                                                'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', $this->slug ),
                                                'return'                          => __( 'Return to Required Plugins Installer', $this->slug ),
                                                'plugin_activated'                => __( 'Plugin activated successfully.', $this->slug ),
                                                'complete'                        => __( 'All plugins installed and activated successfully. %s', $this->slug ),
                                                'nag_type'                        => 'error'
                                            
                                            )
                    );
	
	    tgmpa( $plugins, $config );
	    
    }

/**	=============================
    *
    * Remove hooks, where necessary
    *
    ============================= */
   	
    public function remove_frontend_hooks() {
        
        // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        
    }

/**	=============================
    *
    * Add WooCommerce hooks for product page
    *
    ============================= */
    
    public function add_frontend_hooks() {
    	
    	// add_action( 'woocommerce_single_product_summary', array( $this, 'shop_the_look_display' ), 30 );
        
    }

/**	=============================
    *
    * Frontend Styles
    *
    * @access public
    *
    ============================= */
    
    public function frontend_styles() {
        
        wp_register_style( $this->slug.'_styles', $this->plugin_url . 'assets/frontend/css/main.min.css', array(), $this->version );
		
        wp_enqueue_style( $this->slug.'_styles' );
        
    }
    
/**	=============================
    *
    * Frontend Scripts
    *
    * @access public
    *
    ============================= */
    
    public function frontend_scripts() {
        
        wp_register_script( $this->slug.'_scripts', $this->plugin_url . 'assets/frontend/js/main.min.js', array( 'jquery' ), $this->version, true);
		
        wp_enqueue_script( $this->slug.'_scripts' );
        
    }

/**	=============================
    *
    * Admin Styles
    *
    * @access public
    *
    ============================= */
    
    public function admin_styles() {
        
        global $post, $pagenow;

        // maybe limit this to particular pages, you can use:
		// if( $post && (get_post_type( $post->ID ) == "product" && ($pagenow == "post.php" || $pagenow == "post-new.php")) ){
        
        wp_register_style( $this->slug.'_admin_styles', $this->plugin_url . 'assets/admin/css/main.min.css', array(), $this->version );
		
        wp_enqueue_style( $this->slug.'_admin_styles' );
        
    }
    
/**	=============================
    *
    * Admin Scripts
    *
    * @access public
    *
    ============================= */
    
    public function admin_scripts() {
        
        global $post, $pagenow;

        // maybe limit this to particular pages, you can use:
		// if( $post && (get_post_type( $post->ID ) == "product" && ($pagenow == "post.php" || $pagenow == "post-new.php")) ){
    		
		wp_register_script( $this->slug.'_admin_scripts', $this->plugin_url . 'assets/admin/js/main.min.js', array( 'jquery' ), $this->version, true);
	
        wp_enqueue_script( $this->slug.'_admin_scripts' );
		
		$vars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( $this->slug ),
			'pluginSlug' => $this->slug
		);
		
		wp_localize_script( $this->slug, 'vars', $vars );
        
    }

/** =============================
    *
    * Allow to remove method for a hook when it's a class method used
    * and the class doesn't have a variable assigned, but the class name is known
    *
    * @hook_name: 	  Name of the wordpress hook
    * @class_name: 	  Name of the class where the add_action resides
    * @method_name:	  Name of the method to unhook
    * @priority:	  The priority of which the above method has in the add_action
    *
    ============================= */
   	
	private function remove_filters_for_anonymous_class( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {
    	
        global $wp_filter;
        
        // Take only filters on right hook name and priority
        if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) )
                return false;
        
        // Loop on filters registered
        foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
                // Test if filter is an array ! (always for class/method)
                if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
                        // Test if object is a class, class and method is equal to param !
                        if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && get_class($filter_array['function'][0]) == $class_name && $filter_array['function'][1] == $method_name ) {
                                unset($wp_filter[$hook_name][$priority][$unique_id]);
                        }
                }
                
        }
        
        return false;
        
	}

/**	=============================
    *
    * Get Woo Version Number
    *
    * @return mixed bool/str NULL or Woo version number
    *
    ============================= */
    
    private function get_woo_version_number() {
        
        // If get_plugins() isn't available, require it
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        
            // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';
        
        // If the plugin version number is set, return it 
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
    
        } else {
        // Otherwise return null
            return NULL;
        }
        
    }
  
}

$JCK_WooSocial = new JCK_WooSocial();