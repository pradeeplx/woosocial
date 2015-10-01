<?php
    
class JCK_WooSocial_ActivityLogSystem {
    
    public $table_name;
    public $db_version = "1.0";
    public $slug = "jck-woosocial";
    public $table_slug = "jck_woosocial_activity_log";
    public $default_limit = 12;
    public $default_offset = 0;
    public $wrapper_breakpoints;

/**	=============================
    *
    * Constructor
    *
    ============================= */
    
    public function __construct() {
        
        $this->set_constants();
        
        add_action( 'init', array( $this, 'initiate_hook' ) );
        
    }

/**	=============================
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
    
    public function initiate_hook() {
        
        add_action( 'wp_ajax_jck_woosocial_load_more_activity',         array( $this, 'load_more' ) );
        add_action( 'wp_ajax_nopriv_jck_woosocial_load_more_activity',  array( $this, 'load_more' ) );
        
        add_shortcode( 'woosocial-activity-log',                        array( $this, 'activity_log_shortcode' ) );
        
    }

/**	=============================
    *
    * Setup Constants for this class
    *
    ============================= */
    
    public function set_constants() {
        
        global $wpdb;
        
        $this->table_name = $wpdb->prefix . $this->table_slug;
        
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
			
			$sql = "CREATE TABLE $this->table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id mediumint(9),
			type text,
			rel_id mediumint(9),
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY user_id (id)
			) $charset_collate;";
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
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
    
    public function create_activity_page() {
        
        if( get_page_by_title( 'activity' ) === null ) {
        
            $activity = array(
            	'post_title' => 'Activity',
            	'post_content' => '[jck-woosocial-activity-log]',
            	'post_status' => 'publish',
            	'post_type' => 'page',
            	'post_slug' => 'activity'
            );
            
            $post_id = wp_insert_post($activity);
        
        }
        
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
        $GLOBALS['jck_woosocial']->templates->get_template_part( 'activity/feed', 'following' );
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
        
        $following_count = $wpdb->get_var( "SELECT COUNT(*) FROM $this->table_name WHERE user_id = $user_id AND type = 'follow'" );
        
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
        
        $and_followers = ( $include_followers ) ? "OR rel_id = $user_id" : "";
        
        $activity = $wpdb->get_results( "
            SELECT * 
            FROM $this->table_name 
            WHERE user_id IN ($user_id) 
            $and_followers 
            ORDER BY time DESC 
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
        $user_id = ( $GLOBALS['jck_woosocial']->is_json( $user_id ) ) ? json_decode( $user_id ) : $user_id;
        
        // if it's an array, then we're viewing own activity feed and followers should not be included
        $followers = ( is_array( $user_id ) ) ? false : true;
        
        $activity = $this->get_activity_feed( $user_id, $_GET['limit'], $_GET['offset'], $followers );
        
        if( isset( $_GET['profile_user_id'] ) )
            $GLOBALS['jck_woosocial']->profile_system->user_info = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $_GET['profile_user_id'] );
        
        if( !$GLOBALS['jck_woosocial']->profile_system->user_info )
            $GLOBALS['jck_woosocial']->profile_system->user_info = wp_get_current_user();
        
        $response['activity'] = $activity;
        
        if( $activity ) {
            
            ob_start();
            
            foreach( $activity as $action ) {
                
                include($GLOBALS['jck_woosocial']->templates->locate_template( 'activity/part-action.php' ));
                
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
                
                $action->user = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $action->user_id );
                    
                if( $action->type === "follow" ) {
                    
                    $action->user_2 = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $action->rel_id );                    
                    $action->formatted = $this->format_follow( $action->user, $action->user_2 );
                    $action->icon = "user";
                    
                } elseif( $action->type === "like" ) {
                    
                    $username = ( $action->user_id == $current_user_id ) ? __("You", 'jck-woosocial') : $action->user->profile_link;
                    
                    $action->product = $GLOBALS['jck_woosocial']->like_system->get_product_info( $action->rel_id );                     
                    $action->formatted = sprintf('%s liked %s', $username, $action->product->link);
                    $action->icon = "heart";
                    
                }
                
                // format timestamp
                
                $timestamp = strtotime($action->time);
                
                $action->formatted_date = sprintf( _x( '%s ago', '%s = human-readable time difference', 'jck-woosocial' ), human_time_diff( current_time( 'timestamp' ), $timestamp ) );
                
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
        
        $username_1 = ( $user_1->ID == $current_user_id ) ? __("You", 'jck-woosocial') : $user_1->profile_link; 
        $username_2 = ( $user_2->ID == $current_user_id ) ? strtolower(__("You", 'jck-woosocial')) : $user_2->profile_link; 
        
        return $username_1." ".__('followed', 'jck-woosocial')." ".$username_2;
        
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
        $following = $GLOBALS['jck_woosocial']->follow_system->get_following( $user_id, null, null, true );
        
        if( $following && !empty($following) ) {
            
           $activity = $this->get_activity_feed( $following, $limit = null, $offset = null, false );
            
        }
        
        return $activity;
        
    }

/**	=============================
    *
    * Add like
    *
    * @param int $user_id
    * @param int $product_id
    * @return bool
    *
    ============================= */
    
    public function add_like( $user_id, $product_id ) {
        
        global $wpdb;
        
        $wpdb->insert( 
            $this->table_name, 
            array( 
                'user_id' => $user_id, 
                'type' => 'like',
                'rel_id' => $product_id,
                'time' => current_time("Y-m-d H:i:s")
            ), 
            array( 
                '%d', 
                '%s',
                '%d',
                '%s'
            ) 
        );
        
    }

/**	=============================
    *
    * Remove like
    *
    * @param int $user_id
    * @param int $product_id
    * @return obj Item that was deleted
    *
    ============================= */
    
    public function remove_like( $user_id, $product_id ) {
        
        global $wpdb;
        
        $liked = $GLOBALS['jck_woosocial']->like_system->has_liked( $user_id, $product_id );
        
        if( $liked ) {
        
            $result = $wpdb->delete( $this->table_name, array( 'user_id' => $user_id, 'rel_id' => $product_id, 'type' => 'like' ) );
        
        }
        
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
            'time' => current_time("Y-m-d H:i:s")
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
        
        $follow = $GLOBALS['jck_woosocial']->follow_system->is_following( $user_id, $follow_user_id );
        
        if( $follow ) {
        
            $follow->user_1 = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $user_id );
            $follow->user_2 = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $follow_user_id );
            
            $follow->formatted = $this->format_follow( $follow->user_1, $follow->user_2 );
            $follow->formatted_date = __('Just now', 'jck-woosocial');
        
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
        
        $following = $GLOBALS['jck_woosocial']->follow_system->is_following( $user_id, $follow_user_id );
        
        if( $following ) {
        
            $result = $wpdb->delete( $this->table_name, array( 'user_id' => $user_id, 'rel_id' => $follow_user_id, 'type' => 'follow' ) );
        
        }
        
        return $following;
        
    }
    
}