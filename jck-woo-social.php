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
        $this->activity_log->create_activity_page();
        
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
            
            add_action( 'admin_enqueue_scripts',                        array( $this, 'admin_scripts' ) ); 
            add_action( 'admin_enqueue_scripts',                        array( $this, 'admin_styles' ) ); 
            add_action( 'admin_init',                                   array( $this, 'nav_menu_add_meta_boxes' ) );
            
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
        	
        	$this->options = ( $post ) ? get_post_meta( $post->ID, $this->options_name, true ) : false;
        	
    	}
        
	}

/**	=============================
    *
    * Add Custom Nav Meta Box
    *
    ============================= */

	public function nav_menu_add_meta_boxes() {
		add_meta_box( 'jck_woo_social_nav_link', __( 'WooCommerce Social', 'jck-woo-social' ), array( $this, 'nav_menu_links' ), 'nav-menus', 'side', 'low' );
	}

	public function nav_menu_links() {
    	
    	$links = array(
        	__('Activity Feed','jck-woo-social') => '/jck-woo-social/activity/',
        	__('Your Profile','jck-woo-social') => '/jck-woo-social/profile/%nicename%/'
    	);
    	
		?>
		<div id="posttype-woocommerce-social" class="posttypediv">
			<div id="tabs-panel-woocommerce-social" class="tabs-panel tabs-panel-active">
				<ul id="woocommerce-social-checklist" class="categorychecklist form-no-clear">
					<?php
					$i = -1;
					foreach ($links as $title => $url ) {
						?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $title ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="custom" />
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_html( $title ); ?>" />
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="<?php echo esc_url( $url ); ?>" />
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" />
						</li>
						<?php
						$i --;
					}
					?>
				</ul>
			</div>
			<p class="button-controls">
				<span class="list-controls">
					<a href="<?php echo admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-woocommerce-social' ); ?>" class="select-all"><?php _e( 'Select All', 'woocommerce' ); ?></a>
				</span>
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php _e( 'Add to Menu', 'woocommerce' ); ?>" name="add-post-type-menu-item" id="submit-posttype-woocommerce-social">
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
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
        
        $vars = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( $this->slug ),
			'user_id' => is_user_logged_in() ? get_current_user_id() : 0
		);
		
		wp_localize_script( $this->slug.'_scripts', $this->slug.'_vars', $vars );
		
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

/**	=============================
    *
    * Shorten large numbers into abbreviations (i.e. 1,500 = 1.5k)
    *
    * @param int $number Number to shorten
    * @return string A number with a symbol
    *
    ============================= */

    function shorten_number($number) {
        
        if( $number == 0 ) 
            return $number;
        
        $abbrevs = array(12 => "T", 9 => "B", 6 => "M", 3 => "K", 0 => "");
    
        foreach($abbrevs as $exponent => $abbrev) {
            if($number >= pow(10, $exponent)) {
            	$display_num = $number / pow(10, $exponent);
            	$decimals = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;
                return number_format($display_num,$decimals) . $abbrev;
            }
        }
    }

/**	=============================
    *
    * Get "Load More" Ajax Button
    *
    * @param mixed $type
    * @return str
    *
    ============================= */
    
    public function get_load_more_button( $type = false ) {
        
        if( !$type )
            return "";
        
        $classes = array(
            sprintf('%s-load-more', $this->slug),
            sprintf('%s-load-more--%s', $this->slug, $type)
        );
        
        $additional_attributes = array();
        
        if( $this->profile_system->is_profile() )
            $additional_attributes[] = sprintf( 'data-profile-user-id="%d"', $this->profile_system->user_info->ID );
        
        return sprintf(
            '<a href="javascript: void(0);" class="%s" data-limit="%d" data-offset="%d" data-user-id="%d" %s><i class="woo-social-ic-loading"></i> %s</a>', 
            implode(' ', $classes),
            $this->activity_log->default_limit,
            $this->activity_log->default_offset + $this->activity_log->default_limit,
            $this->profile_system->user_info->ID,
            implode(' ', $additional_attributes),
            __('Load more','jck-woo-social')
        );
        
    }
  
}

$GLOBALS['jck_woosocial'] = new JCK_WooSocial();