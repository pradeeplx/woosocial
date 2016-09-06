<?php

class Iconic_WooSocial_ActivityLogSystem {

    public $table_name;
    public $db_version = "1.0";
    public $slug = "iconic-woosocial";
    public $table_slug = "iconic_woosocial_activity_log";
    public $activity_query_var;
    public $activity_slug = "activity";
    public $default_limit = 12;
    public $default_offset = 0;
    public $wrapper_breakpoints;

/**	=============================
    *
    * Constructor
    *
    ============================= */

    public function __construct() {

        add_action( 'init', array( $this, 'initiate_hook' ) );

    }

/**	=============================
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */

    public function initiate_hook() {

        $this->set_constants();

        add_action( 'wp_ajax_iconic_woosocial_load_more_activity',         array( $this, 'load_more' ) );
        add_action( 'wp_ajax_nopriv_iconic_woosocial_load_more_activity',  array( $this, 'load_more' ) );

        add_shortcode( 'woosocial-activity-log',                        array( $this, 'activity_log_shortcode' ) );

        add_action( 'deleted_post',                                     array( $this, 'deleted_post' ), 10, 1 );

        $this->enable_activity_page();

    }

/**	=============================
    *
    * Setup Constants for this class
    *
    ============================= */

    public function set_constants() {

        global $wpdb;

        $settings = $GLOBALS['iconic_woosocial']->settings;

        $this->table_name = $wpdb->prefix . $this->table_slug;
        $this->activity_query_var = $this->slug.'-activity';

        if( isset( $settings['activity_activity_feed_slug'] ) )
            $this->activity_slug = $settings['activity_activity_feed_slug'];

        if( isset( $settings['activity_activity_feed_items_per_page'] ) )
            $this->default_limit = $settings['activity_activity_feed_items_per_page'];

        $this->wrapper_breakpoints = array(
            array(
                "max_width" => 480,
                "class" => sprintf("%s-actions--small", $this->slug)
            )
        );

    }

/**	=============================
    *
    * Set up Activity Log
    *
    ============================= */

    public function setup_activity_log() {

        global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$installed_ver = get_option( $this->table_slug."_db_version" );

		if( $installed_ver != $this->db_version ) {

			$sql = "CREATE TABLE `{$wpdb->prefix}{$this->table_slug}` (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`user_id` mediumint(9),
			`type` text,
			`rel_id` mediumint(9),
			`time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY user_id (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$return = dbDelta( $sql );

			error_log( print_r( $return, true ) );

			update_option( $this->table_slug."_db_version", $this->db_version );

		}

    }

/**	=============================
    *
    * Create Activity Page
    *
    * The activity page is what logged in users will use
    * to see the activity of people they follow
    *
    ============================= */

    public function enable_activity_page() {

        add_rewrite_rule( $this->activity_slug.'$', 'index.php?'.$this->activity_query_var.'=1', 'top' );

        add_filter( 'query_vars', array( $this, 'query_vars' ) );
        add_action( 'parse_request', array( $this, 'parse_request' ) );

    }

/** =============================
    *
    * Add Query Var
    *
    * @param  [arr] [$query_vars]
    * @return [arr] [$query_vars]
    *
    ============================= */

    public function query_vars( $query_vars ) {

        $query_vars[] = $this->activity_query_var;
        return $query_vars;

    }

/** =============================
    *
    * Parse Query Var
    *
    * If we're on the activity feed page, show the template
    *
    * @param [obj] [$wp]
    *
    ============================= */

    public function parse_request( &$wp ) {

        if ( array_key_exists( $this->activity_query_var, $wp->query_vars ) ) {

            $GLOBALS['iconic_woosocial_is_activity_page'] = true;

            $GLOBALS['iconic_woosocial']->templates->get_template_part( 'activity' );

            exit();
        }

        return;

    }

/** =============================
    *
    * Is Activity Page?
    *
    ============================= */

    public function is_activity_page() {

        if( isset( $GLOBALS['iconic_woosocial_is_activity_page'] ) && $GLOBALS['iconic_woosocial_is_activity_page'] === true ) {
            return true;
        }

        return false;

    }

/**	=============================
    *
    * Activity Log Shortcode
    *
    * @param arr $atts Shortcode attributes
    * @return bool
    *
    ============================= */

    public function activity_log_shortcode( $atts ) {

        ob_start();
        $GLOBALS['iconic_woosocial']->templates->get_template_part( 'activity/feed', 'following' );
        $activity_log = ob_get_clean();

        return $activity_log;

    }

/**	=============================
    *
    * Get Following by user ID
    *
    * @param int $user_id
    * @return int
    *
    ============================= */

    public function get_following_count( $user_id ) {

        if( $user_id == "" )
            return 0;

        global $wpdb;

        $following_count = $wpdb->get_var( "
            SELECT COUNT(*)
            FROM $this->table_name AS log

            LEFT JOIN `{$wpdb->prefix}users` AS users
            ON users.ID = log.rel_id

            WHERE log.user_id = $user_id
            AND users.ID IS NOT NULL
            AND log.type = 'follow'
        " );

        return $following_count;

    }

/**	=============================
    *
    * Get Followers by user ID
    *
    * @param int $user_id
    * @return int
    *
    ============================= */

    public function get_followers_count( $user_id ) {

        if( $user_id == "" )
            return 0;

        global $wpdb;

        $followers_count = $wpdb->get_var( "SELECT COUNT(*) FROM $this->table_name WHERE rel_id = $user_id AND type = 'follow'" );

        return $followers_count;

    }

/**	=============================
    *
    * Get Activity Feed
    *
    * @param int/arr $user_id
    * @param int $limit
    * @param int $offset
    * @param int $include_followers include results for who the requested user has followed
    * @param int $from results from this time (closer)
    * @param int $to to this time (further)
    * @return arr/obj
    *
    ============================= */

    public function get_activity_feed( $user_id, $limit = null, $offset = null, $include_followers = true ) {

        if( $limit === null ) $limit = $this->default_limit;
        if( $offset === null ) $offset = $this->default_offset;

        if( $user_id == "" )
            return 0;

        if( is_array( $user_id ) )
            $user_id = implode(',', array_map('intval', $user_id));

        global $wpdb;

        $and_followers = ( $include_followers ) ? "OR `rel_id` = $user_id AND `type` != 'like'" : "";

        /*
SELECT log.id, log.user_id, log.type, log.rel_id, log.time
	FROM `wp_iconic_woosocial_activity_log` AS log
		LEFT JOIN `wp_posts` AS posts
        ON posts.ID = log.rel_id
        AND log.type = 'like'
	WHERE log.user_id IN (1)
    AND ( log.type = 'like' AND posts.post_status = 'publish' )
    OR ( log.type = 'follow' )
	ORDER BY log.time DESC
	LIMIT 10
	OFFSET 0
	*/

        $activity = $wpdb->get_results( "
            SELECT log.id, log.user_id, log.type, log.rel_id, log.time
                FROM `$this->table_name` AS log

                    LEFT JOIN `{$wpdb->prefix}posts` AS posts
                    ON posts.ID = log.rel_id

                    LEFT JOIN `{$wpdb->prefix}users` AS users
                    ON users.ID = log.rel_id

            	WHERE log.user_id IN ($user_id)

                AND (
                    ( log.type = 'like' AND posts.post_status = 'publish' )
                    OR ( log.type = 'follow' AND users.ID IS NOT NULL )
                )

                $and_followers

            	ORDER BY log.time DESC
            	LIMIT $limit
            	OFFSET $offset
        " );

        $this->format_actions( $activity );

        return $activity;

    }

/**	=============================
    *
    * Ajax: Load More Action
    *
    ============================= */

    function load_more() {

        $response = array(
            'activity_html' => false
        );

        // format user id, either a string or array of ids
        $user_id = stripslashes( $_GET['user_id'] );
        $user_id = ( $GLOBALS['iconic_woosocial']->is_json( $user_id ) ) ? json_decode( $user_id ) : $user_id;

        // if it's an array, then we're viewing own activity feed and followers should not be included
        $followers = ( is_array( $user_id ) ) ? false : true;

        $activity = $this->get_activity_feed( $user_id, $_GET['limit'], $_GET['offset'], $followers );

        if( isset( $_GET['profile_user_id'] ) )
            $GLOBALS['iconic_woosocial']->profile_system->user_info = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $_GET['profile_user_id'] );

        if( !$GLOBALS['iconic_woosocial']->profile_system->user_info )
            $GLOBALS['iconic_woosocial']->profile_system->user_info = wp_get_current_user();

        $response['activity'] = $activity;

        if( $activity ) {

            ob_start();

            foreach( $activity as $action ) {

                include($GLOBALS['iconic_woosocial']->templates->locate_template( 'activity/part-action.php' ));

            }

            $response['activity_html'] = ob_get_clean();

        }

    	// generate the response
    	$response['get'] = $_GET;

    	// response output
    	header('Content-Type: text/javascript; charset=utf8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 3628800');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

    	echo htmlspecialchars($_GET['callback']) . '(' . json_encode( $response ) . ')';

    	wp_die();

    }

/**	=============================
    *
    * Format Actions
    *
    * @param arr $activity An array of activities
    * @return array
    *
    ============================= */

    public function format_actions( $activity ) {



        if( $activity && !empty( $activity ) ) {
            $current_user_id = get_current_user_id();

            $i = 0; foreach( $activity as $action ) {

                $action->user_id = (int)$action->user_id;
                $action->rel_id = (int)$action->rel_id;

                $action->user = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $action->user_id );

                if( $action->type === "follow" ) {

                    $action->user_2 = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $action->rel_id );
                    $action->formatted = $this->format_follow( $action->user, $action->user_2 );
                    $action->icon = "user";

                } elseif( $action->type === "like" ) {

                    $username = ( $action->user_id == $current_user_id ) ? __("You", 'iconic-woosocial') : $action->user->profile_link;

                    $action->product = $GLOBALS['iconic_woosocial']->like_system->get_product_info( $action->rel_id );
                    $action->formatted = sprintf('%s liked %s', $username, $action->product->link);
                    $action->icon = "heart";

                }

                // format timestamp

                $timestamp = strtotime($action->time);

                $action->formatted_date = sprintf( _x( '%s ago', '%s = human-readable time difference', 'iconic-woosocial' ), human_time_diff( current_time( 'timestamp' ), $timestamp ) );

            $i++; }

        }

        return $activity;

    }

/**	=============================
    *
    * Format Follow
    *
    * @param obj $user_1 User Object
    * @param obj $user_2 User Object
    * @return str
    *
    ============================= */

    public function format_follow( $user_1, $user_2 ) {

        $current_user_id = get_current_user_id();

        $username_1 = ( $user_1->ID == $current_user_id ) ? __("You", 'iconic-woosocial') : $user_1->profile_link;
        $username_2 = ( $user_2->ID == $current_user_id ) ? strtolower(__("You", 'iconic-woosocial')) : $user_2->profile_link;

        return $username_1." ".__('followed', 'iconic-woosocial')." ".$username_2;

    }

/**	=============================
    *
    * Get Following Activity Feed
    *
    * @param int $user_id
    * @return arr/obj
    *
    ============================= */

    public function get_following_activity_feed( $user_id ) {

        // if no user id, return nothing
        if( $user_id == "" )
            return 0;

        // start query

        $activity = array();
        $following = $GLOBALS['iconic_woosocial']->follow_system->get_following( $user_id, null, null, true );

        if( $following && !empty($following) ) {

           $activity = $this->get_activity_feed( $following, $limit = null, $offset = null, false );

        }

        return $activity;

    }

    /**
     * Add like
     *
     * @param int $user_id
     * @param int $product_id
     * @return bool
     */
    public function add_like( $user_id, $product_id ) {

        global $wpdb;

        $insert = $wpdb->insert(
            $this->table_name,
            array(
                'user_id' => $user_id,
                'type' => 'like',
                'rel_id' => $product_id,
                'time' => current_time("mysql")
            ),
            array(
                '%d',
                '%s',
                '%d',
                '%s'
            )
        );

        if( !$insert )
            return false;

        $GLOBALS['iconic_woosocial']->like_system->update_products_like_count( $product_id );

        return $insert;

    }

    /**
     * Remove like
     *
     * @param int $user_id
     * @param int $product_id
     * @return obj Item that was deleted
     */
    public function remove_like( $user_id, $product_id ) {

        global $wpdb;

        $liked = $GLOBALS['iconic_woosocial']->like_system->has_liked( $user_id, $product_id );

        if( !$liked )
            return false;

        $result = $wpdb->delete( $this->table_name, array( 'user_id' => $user_id, 'rel_id' => $product_id, 'type' => 'like' ) );

        if( !$result )
            return false;

        $GLOBALS['iconic_woosocial']->like_system->update_products_like_count( $product_id, 'remove' );

        return $result;

    }

/**	=============================
    *
    * Add follow
    *
    * @param int $user_id
    * @param int $follow_user_id
    * @return bool
    *
    ============================= */

    public function add_follow( $user_id, $follow_user_id ) {

        global $wpdb;

        $follow_data = array(
            'user_id' => $user_id,
            'type' => 'follow',
            'rel_id' => $follow_user_id,
            'time' => current_time("mysql")
        );

        $wpdb->insert(
            $this->table_name,
            $follow_data,
            array(
                '%d',
                '%s',
                '%d',
                '%s'
            )
        );

        $follow = $GLOBALS['iconic_woosocial']->follow_system->is_following( $user_id, $follow_user_id );

        if( $follow ) {

            $follow->user_1 = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $user_id );
            $follow->user_2 = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $follow_user_id );

            $follow->formatted = $this->format_follow( $follow->user_1, $follow->user_2 );
            $follow->formatted_date = __('Just now', 'iconic-woosocial');

        }

        return $follow;

    }

/**	=============================
    *
    * Remove follow
    *
    * @param int $user_id
    * @param int $follow_user_id
    * @return obj Item that was deleted
    *
    ============================= */

    public function remove_follow( $user_id, $follow_user_id ) {

        global $wpdb;

        $following = $GLOBALS['iconic_woosocial']->follow_system->is_following( $user_id, $follow_user_id );

        if( $following ) {

            $result = $wpdb->delete( $this->table_name, array( 'user_id' => $user_id, 'rel_id' => $follow_user_id, 'type' => 'follow' ) );

        }

        return $following;

    }

    /**
     * Hook: On delete product
     *
     * @param int $post_id
     */
    public function deleted_post( $post_id ) {

        global $wpdb;

        if( get_post_type( $post_id ) === "product" ) {

            $result = $wpdb->delete( $this->table_name, array( 'rel_id' => $post_id ) );

        }

    }

}