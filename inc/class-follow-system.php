<?php
    
class JCK_WooSocial_FollowSystem {
    
/**	=============================
    *
    * Construct the class
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

        add_action( 'wp_ajax_jck_woo_social_follow_action',                array( $this, 'jck_woo_social_follow_action' ) );
        add_action( 'wp_ajax_nopriv_jck_woo_social_follow_action',         array( $this, 'jck_woo_social_follow_action' ) );
        
	}

/**	=============================
    *
    * Ajax: Follow Action
    *
    ============================= */
    
    function jck_woo_social_follow_action() {
        
        global $JCK_WooSocial;
    	
    	if ( ! wp_verify_nonce( $_GET['nonce'], $JCK_WooSocial->slug ) )
    		die ( 'Busted!' );
    		
        $current_user_id = get_current_user_id();
        $response = array(
            'add_follow_html' => false,
            'remove_action_class' => false
        );
        
        if( $_GET['type'] == "follow" ) {
    		
            $action = $JCK_WooSocial->activity_log->add_follow( $current_user_id, $_GET['user_id'] );
            
            $response['button']['text'] = __('Unfollow','jck-woo-social');
            $response['button']['type'] = 'unfollow';
            
            ob_start();
            include($JCK_WooSocial->templates->locate_template( 'profile/part-action.php' ));
            $response['add_follow_html'] = ob_get_clean();
        
        } else {
            
            $removed = $JCK_WooSocial->activity_log->remove_follow( $current_user_id, $_GET['user_id'] );
            
            $response['button']['text'] = __('Follow','jck-woo-social');
            $response['button']['type'] = 'follow';
            
            $response['remove_follow_class'] = '.'.$JCK_WooSocial->slug.'-action--'.$removed->id;
            
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
    * Get Followers
    *
    * @param int $user_id
    * @param int $limit
    * @param int $offset
    * @return obj
    *
    ============================= */
    
    public function get_followers( $user_id, $limit = 10, $offset = 0 ) {
        
        global $wpdb, $JCK_WooSocial;
        
        $followers = $wpdb->get_results( 
        	"
        	SELECT *
        	FROM {$JCK_WooSocial->activity_log->table_name}
        	WHERE rel_id = $user_id
        	AND type = 'follow'
        	LIMIT $limit
        	OFFSET $offset
        	"
        );
        
        return $followers;
        
    }
    
/**	=============================
    *
    * Get Following
    *
    * @param int $user_id
    * @param int $limit
    * @param int $offset
    * @return obj
    *
    ============================= */
    
    public function get_following( $user_id, $limit = null, $offset = null, $id_only = false ) {
        
        if( $limit === null ) $limit = 10;
        if( $offset === null ) $offset = 0;
        
        global $wpdb, $JCK_WooSocial;
        
        $select = ( $id_only ) ? 'rel_id' : "*";
        
        $followers = $wpdb->get_results( 
        	"
        	SELECT $select
        	FROM {$JCK_WooSocial->activity_log->table_name}
        	WHERE user_id = $user_id
        	AND type = 'follow'
        	LIMIT $limit
        	OFFSET $offset
        	"
        );
        
        if( $id_only && !empty($followers) ) {
            
            $i = 0; foreach( $followers as $follower ) {
                
                $followers[$i] = $follower->rel_id;
                
            $i++; }
            
        }
        
        return $followers;
        
    }

/**	=============================
    *
    * Is Following
    *
    * @param int $user_id
    * @param int $follow_user_id
    * @return bool
    *
    ============================= */
    
    public function is_following( $user_id, $follow_user_id ) {
        
        global $JCK_WooSocial, $wpdb;
        
        $following = $wpdb->get_row( 
        	"
        	SELECT *
        	FROM {$JCK_WooSocial->activity_log->table_name}
        	WHERE user_id = $user_id
        	AND rel_id = $follow_user_id
        	AND type = 'follow'
        	"
        );
        
        return ( $following ) ? $following : false;
    }

/**	=============================
    *
    * Get Follow Button
    *
    * @param int $user_id
    * @return str
    *
    ============================= */
    
    public function get_follow_button( $user_info ) {
        
        global $JCK_WooSocial;
        
        $current_user_id = get_current_user_id();
        $is_user_logged_in = is_user_logged_in();
        
        if( $current_user_id != $user_info->ID ) {
            
            $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
            $myaccount_page_url = ( $myaccount_page_id ) ? get_permalink( $myaccount_page_id ).'?profile='.$user_info->user_nicename : "javascript: void(0);";

            $is_following = $JCK_WooSocial->follow_system->is_following( $current_user_id, $user_info->ID );
            $button_text = ( $is_following ) ? __("Unfollow",'jck-woo-social') : __("Follow",'jck-woo-social');
            $button_type = ( $is_user_logged_in ) ? ( $is_following ? "unfollow" : "follow" ) : "login";
            $button_classes = implode(' ', array(
                'button',
                sprintf( '%s-button', $JCK_WooSocial->slug ),
                sprintf( '%s-follow-action', $JCK_WooSocial->slug ),
                sprintf( '%s-follow-action--', $JCK_WooSocial->slug, $button_type )
            ));
            $href = ( $is_user_logged_in ) ? "javascript: void(0);" : $myaccount_page_url;
        
            return sprintf('<a href="%s" class="%s" data-user-id="%d" data-type="%s">%s</a>', $href, $button_classes, $user_info->ID, $button_type, $button_text);
        
        }
        
    }
    
}