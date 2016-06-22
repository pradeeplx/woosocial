<?php
/*
Plugin Name: WooSocial
Plugin URI: https://iconicwp.com
Description: Profiles, likes, and followers - WooSocial
Version: 1.0.1
Author: Iconic
Author URI: https://iconicwp.com
Text Domain: jck-woosocial
*/

class JCK_WooSocial {

    /**
     * Name
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $name = 'WooSocial';

    /**
     * Short Name
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $shortname = 'WooSocial';

    /**
     * Slug
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $slug = 'jck-woosocial';

    /**
     * Slug Alt
     *
     * Main slug, but with underscores
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $slug_alt;

    /**
     * version
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $version = "1.0.1";

    /**
     * Plugin path
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $plugin_path;

    /**
     * Plugin URL
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $plugin_url;

    /**
     * Templates
     *
     * Used to allow users to override templates in their theme
     *
     * @since 1.0.0
     * @access public
     * @var Iconic_CFFV_TemplateLoader $templates
     */
    public $templates;

    /**
     * Hooks class
     *
     * @since 1.0.0
     * @access public
     * @var JCK_WooSocial_Hooks
     */
    private $hooks;

    /**
     * Profile class
     *
     * @since 1.0.0
     * @access public
     * @var JCK_WooSocial_ProfileSystem
     */
    public $profile_system;

    /**
     * Like class
     *
     * @since 1.0.0
     * @access public
     * @var JCK_WooSocial_LikeSystem
     */
    public $like_system;

    /**
     * Follow class
     *
     * @since 1.0.0
     * @access public
     * @var JCK_WooSocial_FollowSystem
     */
    public $follow_system;

    /**
     * Activity Log class
     *
     * @since 1.0.0
     * @access public
     * @var JCK_WooSocial_ActivityLogSystem
     */
    public $activity_log;

    /**
     * WP Settings Framework
     *
     * @since 1.0.0
     * @access private
     * @var WordPressSettingsFramework
     */
    private $wpsf;

    /**
     * Settings name
     *
     * @since 1.0.0
     * @access public
     * @var str
     */
    public $settings_name;

    /**
     * Settings
     *
     * @since 1.0.0
     * @access public
     * @var arr
     */
    public $settings;

    /**
     * Construct the plugin
     */
    public function __construct() {

        $this->set_constants();
        $this->load_classes();
        $this->load_settings_framework();

        register_activation_hook( __FILE__, array( $this, 'install' ) );

        // Hook up to the init and plugins_loaded actions
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded_hook' ) );
        add_action( 'init',           array( $this, 'initiate_hook' ) );

    }

    /**
     * Load settings framework
     */
    public function load_settings_framework() {

        require_once( $this->plugin_path .'inc/admin/wp-settings-framework/wp-settings-framework.php' );

        $this->option_group = $this->slug_alt;

        $this->wpsf = new WordPressSettingsFramework( $this->plugin_path .'inc/admin/settings.php', $this->option_group );

        $this->settings = wpsf_get_settings( $this->option_group );

    }

    /**
     * Setup Constants for this class
     */
    public function set_constants() {

        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->slug_alt = str_replace('-', '_', $this->slug);
        $this->settings_name = $this->slug_alt;

    }

    /**
     * Install Plugin on Activation
     */
    public function install() {

        $this->activity_log->setup_activity_log();
        flush_rewrite_rules();

    }

    /**
     * Load Classes
     */
    private function load_classes() {

        require_once( $this->plugin_path.'/inc/class-hooks.php' );
        require_once( $this->plugin_path.'/inc/class-template-loader.php' );
        require_once( $this->plugin_path.'/inc/class-profile-system.php' );
        require_once( $this->plugin_path.'/inc/class-like-system.php' );
        require_once( $this->plugin_path.'/inc/class-follow-system.php' );
        require_once( $this->plugin_path.'/inc/class-activity-log-system.php' );

        $this->hooks = new JCK_WooSocial_Hooks();
        $this->templates = new Iconic_Template_Loader( $this->slug_alt, 'jck-woosocial', $this->plugin_path );
        $this->activity_log = new JCK_WooSocial_ActivityLogSystem();

        $this->profile_system = new JCK_WooSocial_ProfileSystem();
        $this->follow_system = new JCK_WooSocial_FollowSystem();
        $this->like_system = new JCK_WooSocial_LikeSystem( $this->activity_log );

    }

    /**
     * Run quite near the start (http://codex.wordpress.org/Plugin_API/Action_Reference)
     */
    public function plugins_loaded_hook() {

        load_plugin_textdomain( "jck-woosocial", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    }

    /**
     * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
     */
    public function initiate_hook() {

        if(is_admin()) {

            add_action( 'admin_enqueue_scripts',    array( $this, 'admin_scripts' ) );
            add_action( 'admin_enqueue_scripts',    array( $this, 'admin_styles' ) );
            add_action( 'admin_init',               array( $this, 'nav_menu_add_meta_boxes' ) );
            add_action( 'admin_menu',               array( $this, 'init_settings' ), 99 );

        } else {

            add_action( 'wp_enqueue_scripts',       array( $this, 'frontend_scripts' ) );
            add_action( 'wp_enqueue_scripts',       array( $this, 'frontend_styles' ) );
            add_filter( 'nav_menu_link_attributes', array( $this, 'nav_menu_profile_link' ), 10, 3 );
            add_filter( 'walker_nav_menu_start_el', array( $this, 'nav_menu_profile_link_walker' ), 10, 4 );

        }

    }

    /**
     * Initiate Settings
     */
    public function init_settings() {

        $this->wpsf->add_settings_page( array(
            'page_slug'   => 'settings',
            'parent_slug' => 'woocommerce',
            'page_title'  => __( 'WooSocial Settings', 'jck-woosocial' ),
            'menu_title'  => __( 'WooSocial', 'jck-woosocial' ),
            'capability'  => 'manage_options'
        ) );

    }

    /**
     * Add Custom Nav Meta Box
     */
    public function nav_menu_add_meta_boxes() {
        add_meta_box( 'jck_woosocial_nav_link', __( 'WooSocial', 'jck-woosocial' ), array( $this, 'nav_menu_links' ), 'nav-menus', 'side', 'low' );
    }

    public function nav_menu_links() {

        $links = array(
            __('Activity Feed', 'jck-woosocial') => '/jck-woosocial/activity/',
            __('Your Profile', 'jck-woosocial')  => '/jck-woosocial/profile/%nicename%/'
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

    /**
     * Replace Profile and Activity Feed Links in menu Item
     */
    public function nav_menu_profile_link( $atts, $item, $args ) {

        if( is_user_logged_in() ) {

            $atts['href'] = $this->replace_url( $atts['href'] );

        }

        return $atts;

    }

    /**
     * Helper: Replace menu URLs with proper links
     *
     * @param  [str] [$url]
     * @return [str]
     */
    public function replace_url( $url ) {

        $current_user = wp_get_current_user();

        $url = str_replace('/jck-woosocial', get_bloginfo('url'), $url);
        $url = str_replace('%nicename%', $current_user->user_nicename, $url);

        return $url;

    }

    /**
     * Frontend: Replace URLs for themes with this custom walker (like Atelier)
     *
     * @param  [str] [$item_output]
     * @param  [obj] [$item]
     * @param  [int] [$depth]
     * @param  [arr] [$args]
     * @return [str]
     */
    public function nav_menu_profile_link_walker( $item_output, $item, $depth, $args ) {

        if( is_user_logged_in() ) {

            $item_output_parts = new SimpleXMLElement( $item_output );
            $href = $item_output_parts['href'];
            $new_url = $this->replace_url( $href );
            $pattern = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";

            $item_output = preg_replace( $pattern, $new_url, $item_output );

        }

        return $item_output;

    }

    /**
     * Frontend Styles
     *
     * @access public
     */
    public function frontend_styles() {

        if( $this->can_load_assets() ) {

            wp_register_style( $this->slug.'_styles', $this->plugin_url . 'assets/frontend/css/main.min.css', array(), $this->version );

            wp_enqueue_style( $this->slug.'_styles' );

            // custom colours

            $custom_css = sprintf("
                .jck-woosocial-add-to-cart-wrapper a.button, .jck-woosocial-btn {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-add-to-cart-wrapper a.button:hover, .jck-woosocial-btn:hover,
                .jck-woosocial-add-to-cart-wrapper a.button:focus, .jck-woosocial-btn:focus {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-action__icon--like {
                    border-color: %s;
                }

                .jck-woosocial-action__icon--like,
                .jck-woosocial-btn--like {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-btn--like:hover,
                .jck-woosocial-btn--like:focus {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-action__icon--follow {
                    border-color: %s;
                }

                .jck-woosocial-action__icon--follow,
                .jck-woosocial-btn--follow {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-btn--follow:hover,
                .jck-woosocial-btn--follow:focus {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-action__description,
                .jck-woosocial-actions:before {
                    background: %s;
                }

                .jck-woosocial-card {
                    border-color: %s;
                    background: %s;
                }

                .jck-woosocial-action__wrapper:before {
                    border-left-color: %s;
                }

                .jck-woosocial-action:nth-child(even) .jck-woosocial-action__wrapper:before {
                    border-right-color: %s;
                }

                .jck-woosocial-profile-info,
                .jck-woosocial-profile-links__item {
                    border-color: %s;
                }

                .jck-woosocial-profile-info,
                .jck-woosocial-profile-link {
                    background: %s;
                }

                .jck-woosocial-profile-link {
                    color: %s;
                }

                .jck-woosocial-profile-link:hover,
                .jck-woosocial-profile-link:focus {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-profile-link__count {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-profile-link:hover .jck-woosocial-profile-link__count
                .jck-woosocial-profile-link:focus .jck-woosocial-profile-link__count {
                    background: %s;
                    color: %s;
                }

                .jck-woosocial-profile-link--active,
                .jck-woosocial-profile-link--active:hover,
                .jck-woosocial-profile-link--active:focus {
                    background: %s;
                    color: %s;
                }
                ",
                $this->settings['colours_colours_general_button_background'],
                $this->settings['colours_colours_general_button_foreground'],
                $this->settings['colours_colours_general_button_background_hover'],
                $this->settings['colours_colours_general_button_foreground_hover'],
                $this->settings['colours_colours_likes_border'],
                $this->settings['colours_colours_likes_background'],
                $this->settings['colours_colours_likes_foreground'],
                $this->settings['colours_colours_likes_background_hover'],
                $this->settings['colours_colours_likes_foreground_hover'],
                $this->settings['colours_colours_follow_border'],
                $this->settings['colours_colours_follow_background'],
                $this->settings['colours_colours_follow_foreground'],
                $this->settings['colours_colours_follow_background_hover'],
                $this->settings['colours_colours_follow_foreground_hover'],
                $this->settings['colours_colours_cards_border'],
                $this->settings['colours_colours_cards_border'],
                $this->settings['colours_colours_cards_background'],
                $this->settings['colours_colours_cards_border'],
                $this->settings['colours_colours_cards_border'],
                $this->settings['colours_colours_profile_tabs_border'],
                $this->settings['colours_colours_profile_tabs_background'],
                $this->settings['colours_colours_profile_tabs_foreground'],
                $this->settings['colours_colours_profile_tabs_background_hover'],
                $this->settings['colours_colours_profile_tabs_foreground_hover'],
                $this->settings['colours_colours_profile_tabs_count_background'],
                $this->settings['colours_colours_profile_tabs_count_foreground'],
                $this->settings['colours_colours_profile_tabs_count_background_hover'],
                $this->settings['colours_colours_profile_tabs_count_foreground_hover'],
                $this->settings['colours_colours_profile_tabs_active_background'],
                $this->settings['colours_colours_profile_tabs_active_foreground']
            );

            wp_add_inline_style( $this->slug.'_styles', $this->minify_css( $custom_css ) );

        }

    }

    /**
     * Minify CSS
     *
     * @param  [str] [$css]
     * @return [str] [$css]
     */
    public function minify_css( $css ) {
        return str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$css)))));
    }

    /**
     * Frontend Scripts
     *
     * @access public
     */
    public function frontend_scripts() {

        if( $this->can_load_assets() ) {

            wp_register_script( $this->slug.'_scripts', $this->plugin_url . 'assets/frontend/js/main.min.js', array( 'jquery' ), $this->version, true);

            $vars = array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( $this->slug ),
                'user_id'  => is_user_logged_in() ? get_current_user_id() : 0,
                'strings'  => array(
                    "no_more" => __( "No more to load", "jck-woosocial" )
                )
            );

            wp_localize_script( $this->slug.'_scripts', $this->slug_alt.'_vars', $vars );

            wp_enqueue_script( 'hoverIntent' );
            wp_enqueue_script( $this->slug.'_scripts' );

        }

    }

    /**
     * Can Load Assets?
     *
     * @return [bool]
     */
    public function can_load_assets() {

        if(
            $this->profile_system->is_profile() ||
            $this->activity_log->is_activity_page() ||
            ( is_shop() && $this->settings['likes_likes_category_display'] !== "none" ) ||
            ( $this->settings['likes_likes_category_display'] !== "none" && is_product_category() && !is_search() ) ||
            ( $this->settings['likes_likes_product_display'] !== "none" && is_product() ) ||
            ( $this->settings['likes_likes_search_display'] !== "none" && is_search() )
        ) {
            return true;
        }

        return false;

    }

    /**
     * Admin Styles
     *
     * @access public
     */
    public function admin_styles() {

        global $post, $pagenow;

        // maybe limit this to particular pages, you can use:
        // if( $post && (get_post_type( $post->ID ) == "product" && ($pagenow == "post.php" || $pagenow == "post-new.php")) ){

        wp_register_style( $this->slug.'_admin_styles', $this->plugin_url . 'assets/admin/css/main.min.css', array( 'jquery', 'eq', 'hoverIntent' ), $this->version );

        wp_enqueue_style( $this->slug.'_admin_styles' );

    }

    /**
     * Admin Scripts
     *
     * @access public
     */
    public function admin_scripts() {

        global $post, $pagenow;

        // maybe limit this to particular pages, you can use:
        // if( $post && (get_post_type( $post->ID ) == "product" && ($pagenow == "post.php" || $pagenow == "post-new.php")) ){

        wp_register_script( $this->slug.'_admin_scripts', $this->plugin_url . 'assets/admin/js/main.min.js', array( 'jquery' ), $this->version, true);

        wp_enqueue_script( $this->slug.'_admin_scripts' );

    }

    /**
     * Allow to remove method for a hook when it's a class method used
     * and the class doesn't have a variable assigned, but the class name is known
     *
     * @hook_name:       Name of the wordpress hook
     * @class_name:       Name of the class where the add_action resides
     * @method_name:      Name of the method to unhook
     * @priority:      The priority of which the above method has in the add_action
     */
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

    /**
     * Get Woo Version Number
     *
     * @return mixed bool/str NULL or Woo version number
     */
    private function get_woo_version_number() {

        // If get_plugins() isn't available, require it
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file   = 'woocommerce.php';

        // If the plugin version number is set, return it
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {

            return $plugin_folder[$plugin_file]['Version'];

        // Otherwise return null
        } else {

            return NULL;

        }

    }

    /**
     * Shorten large numbers into abbreviations (i.e. 1,500 = 1.5k)
     *
     * @param int $number Number to shorten
     * @return string A number with a symbol
     */
    public function shorten_number($number) {

        if( $number == 0 )
            return $number;

        $abbrevs = array(
            12 => "T",
            9  => "B",
            6  => "M",
            3  => "K",
            0  => "",
        );

        foreach($abbrevs as $exponent => $abbrev) {

            if( $number >= pow(10, $exponent) ) {

                $display_num = $number / pow(10, $exponent);
                $decimals    = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;

                return number_format($display_num,$decimals) . $abbrev;

            }

        }
    }

    /**
     * Get "Load More" Ajax Button
     *
     * @param mixed $type
     * @return str
     */
    public function get_load_more_button( $type = false ) {

        if( !$type )
            return "";

        $classes = array(
            sprintf('%s-btn', $this->slug),
            sprintf('%s-load-more', $this->slug),
            sprintf('%s-load-more--%s', $this->slug, $type)
        );

        $additional_attributes = array();
        $user_id = 0;

        if( $this->profile_system->is_profile() )
            $additional_attributes[] = sprintf( 'data-profile-user-id="%d"', $this->profile_system->user_info->ID );

        if( is_author() ) {
            $user_id = $this->profile_system->user_info->ID;
        } else {
            $following = $this->follow_system->get_following( $this->profile_system->user_info->ID, null, null, true );
            if( $following && !empty( $following ) ) {
                $user_id = esc_attr(json_encode($following));
            }
        }

        return sprintf(
            '<div class="%s-load-more-wrapper"><a href="javascript: void(0);" class="%s" data-limit="%d" data-offset="%d" data-user-id="%s" %s><i class="jck-woosocial-ic-loading"></i> %s</a></div>',
            $this->slug,
            implode(' ', $classes),
            $this->activity_log->default_limit,
            $this->activity_log->default_offset + $this->activity_log->default_limit,
            $user_id,
            implode(' ', $additional_attributes),
            __('Load more', 'jck-woosocial')
        );

    }

    /**
     * Message Wrapper
     *
     * Wrap Text in a nice wrapper for displaying messages
     *
     * @param str $message
     * @param str $type
     * @return str
     */
    public function wrap_message( $message, $type = "notice" ) {

        echo sprintf(
            '<div class="%s-message-wrapper %s-message-wrapper--%s">%s</div>',
            $this->slug,
            $this->slug,
            $type,
            $message
        );

    }


    /**
     * Is Json?
     *
     * @param  [str] [$string]
     * @return [bool]
     */
    public function is_json( $string ) {

        json_decode( $string );
        return (json_last_error() == JSON_ERROR_NONE);

    }

}

$GLOBALS['jck_woosocial'] = new JCK_WooSocial();