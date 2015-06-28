<?php
    
class JCK_WooSocial_ActivityLogSystem {
    
    public $table_name;
    public $db_version = "1.0";
    public $slug = "jck_woo_social_activity_log";

/**	=============================
    *
    * Constructor
    *
    ============================= */
    
    public function __construct() {
        
        $this->set_constants();
        
    }

/**	=============================
    *
    * Setup Constants for this class
    *
    ============================= */
    
    public function set_constants() {
        
        global $wpdb;
        
        $this->table_name = $wpdb->prefix . $this->slug;
        
    }

/**	=============================
    *
    * Set up Activity Log
    *
    ============================= */
    
    public function setup_activity_log() {
        
        global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();
		$installed_ver = get_option( $this->slug."_db_version" );	
			
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
			
			update_option( $this->slug."_db_version", $this->db_version );
			
		}
        
    }

/**	=============================
    *
    * Get Likes by user ID
    *
    * @param int $user_id
    * @return int
    *
    ============================= */
    
    public function get_likes_count( $user_id ) {
        
        if( $user_id == "" )
            return 0;
        
        global $wpdb;
        
        $likes_count = $wpdb->get_var( "SELECT COUNT(*) FROM $this->table_name WHERE user_id = $user_id AND type = 'like'" );
        
        return $likes_count;
        
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
    * @param int $user_id
    * @return arr/obj
    *
    ============================= */
    
    public function get_activity_feed( $user_id, $limit = null, $offset = null, $include_followers = true ) {
        
        if( $limit === null ) $limit = 10;
        if( $offset === null ) $offset = 0;
        
        if( $user_id == "" )
            return 0;
        
        global $wpdb;
        
        $and_followers = ( $include_followers ) ? "OR rel_id = $user_id" : "";
        
        $activity = $wpdb->get_results( "SELECT * FROM $this->table_name WHERE user_id = $user_id $and_followers ORDER BY time DESC LIMIT $limit OFFSET $offset" );
        
        return $activity;
        
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
        
        if( $user_id == "" )
            return 0;
        
        global $JCK_WooSocial;
        
        $activity = array();
        $following = $JCK_WooSocial->follow_system->get_following( $user_id, null, null, true );
        
        if( $following && !empty($following) ) {
            
            foreach( $following as $following_user_id ) {
                
                error_log( print_r( $following_user_id, true ) );
                
                $activity = array_merge( $this->get_activity_feed( $following_user_id, null, null, false ), $activity );
                
            }
            
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
    * Add follow
    *
    * @param int $user_id
    * @param int $follow_user_id
    * @return bool
    *
    ============================= */
    
    public function add_follow( $user_id, $follow_user_id ) {
        
        global $wpdb;
        
        $wpdb->insert( 
            $this->table_name, 
            array( 
                'user_id' => $user_id, 
                'type' => 'follow',
                'rel_id' => $follow_user_id,
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
    
}