<?php
    
class JCK_WooSocial_LikeSystem {
    
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
    	
    	add_action( 'wp_ajax_jck_woo_social_like_action',                array( $this, 'jck_woo_social_like_action' ) );
        add_action( 'wp_ajax_nopriv_jck_woo_social_like_action',         array( $this, 'jck_woo_social_like_action' ) );

        if(!is_admin()) {
            
            // @jck: make this position an option
            add_action( 'woocommerce_after_shop_loop_item',              array( $this, 'show_likes_loop' ) );
            add_action( 'woocommerce_single_product_summary',            array( $this, 'show_likes_loop' ), 1 );
            
        }
        
	}

/**	=============================
    *
    * Ajax: Follow Action
    *
    ============================= */
    
    function jck_woo_social_like_action() {
        
        global $JCK_WooSocial;
    	
    	if ( ! wp_verify_nonce( $_GET['nonce'], $JCK_WooSocial->slug ) )
    		die ( 'Busted!' );
    		
        $current_user_id = get_current_user_id();
        
        $response = array(
            'add_like_html' => false,
            'remove_like_class' => false
        );
        
        if( $_GET['type'] == "like" ) {
    		
            $action = $JCK_WooSocial->activity_log->add_like( $current_user_id, $_GET['product_id'] );
            
            $response['button']['type'] = 'unlike';
            $response['add_like_html'] = $this->get_like_list_item( $current_user_id );
        
        } else {
            
            $action = $JCK_WooSocial->activity_log->remove_like( $current_user_id, $_GET['product_id'] );
            
            $response['button']['type'] = 'like';
            $response['remove_like_class'] = '.jck-woo-social-likes__item--'.$current_user_id;
            
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
    * Has Liked
    *
    * @param int $user_id
    * @param int $follow_user_id
    * @return bool
    *
    ============================= */
    
    public function has_liked( $user_id, $product_id ) {
        
        global $JCK_WooSocial, $wpdb;
        
        $liked = $wpdb->get_row( 
        	"
        	SELECT *
        	FROM {$JCK_WooSocial->activity_log->table_name}
        	WHERE user_id = $user_id
        	AND rel_id = $product_id
        	AND type = 'like'
        	"
        );
        
        return ( $liked ) ? $liked : false;
    }

/**	=============================
    *
    * Get Like List item
    *
    * @param int $user_id
    * @return str
    *
    ============================= */
    
    public function get_like_list_item( $user_id ) {
        
        global $JCK_WooSocial;
        
        $user = $JCK_WooSocial->profile_system->get_user_info( $user_id );
        
        return sprintf('<li class="jck-woo-social-likes__item jck-woo-social-likes__item--%d">%s</li>', $user_id, $user->avatar);
        
    }

/**	=============================
    *
    * Show Likes in the Loop
    *
    ============================= */
    
    public function show_likes_loop() {
        
        global $JCK_WooSocial, $post;
        
        $product_likes = $this->get_product_likes( $post->ID );
        $product_likes_count = $this->get_product_likes_count( $post->ID );
        $location = ( is_archive() ) ? "loop" : "single";
            
        echo sprintf('<ul class="jck-woo-social-likes jck-woo-social-likes--%s">', $location);
        
            $type = $this->has_liked( get_current_user_id(), $post->ID ) ? "unlike" : "like";
        
            echo sprintf('<li class="jck-woo-social-likes__item jck-woo-social-likes__item--like-button"><span class="jck-woo-social-like-action jck_woo_social-like-action--%s" data-type="%s" data-product-id="%d"><i class="woo-social-ic-heart"></i> <span class="jck-woo-social-like-button__count">%s</span></span></li>', $type, $type, $post->ID, $product_likes_count);
            
            if( $product_likes && !empty($product_likes) ) {
            
                foreach($product_likes as $product_like ) {
                    
                    echo $this->get_like_list_item( $product_like->user_id );
                    
                }
            
            }
        
        echo '</ul>';
        
    }
    
/**	=============================
    *
    * Get Likes
    *
    * @param int $user_id
    * @param int $limit
    * @param int $offset
    * @return obj
    *
    ============================= */
    
    public function get_likes( $user_id, $limit = 10, $offset = 0 ) {
        
        global $wpdb, $JCK_WooSocial;
        
        $likes = $wpdb->get_results( 
        	"
        	SELECT *
        	FROM {$JCK_WooSocial->activity_log->table_name}
        	WHERE user_id = $user_id
        	AND type = 'like'
        	ORDER BY time DESC
        	LIMIT $limit
        	OFFSET $offset
        	"
        );
        
        return $likes;
        
    }

/**	=============================
    *
    * Get Likes count by user ID
    *
    * @param int $user_id
    * @return int
    *
    ============================= */
    
    public function get_likes_count( $user_id ) {
        
        if( $user_id == "" )
            return 0;
        
        global $JCK_WooSocial, $wpdb;
        
        $likes_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$JCK_WooSocial->activity_log->table_name} WHERE user_id = $user_id AND type = 'like'" );
        
        return $JCK_WooSocial->shorten_number( $likes_count );
        
    }
    
/**	=============================
    *
    * Get Product Likes
    *
    * Get Likes for a single Product
    *
    * @param int $user_id
    * @param int $limit
    * @param int $offset
    * @return obj
    *
    ============================= */
    
    public function get_product_likes( $product_id, $limit = 8 ) {
        
        global $wpdb, $JCK_WooSocial;
        
        $likes = $wpdb->get_results( 
        	"
        	SELECT *
        	FROM {$JCK_WooSocial->activity_log->table_name}
        	WHERE rel_id = $product_id
        	AND type = 'like'
        	ORDER BY time DESC
        	LIMIT $limit
        	"
        );
        
        return $likes;
        
    }

/**	=============================
    *
    * Get Likes count by product ID
    *
    * @param int $product_id
    * @return int
    *
    ============================= */
    
    public function get_product_likes_count( $product_id ) {
        
        if( $product_id == "" )
            return 0;
        
        global $JCK_WooSocial, $wpdb;
        
        $likes_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$JCK_WooSocial->activity_log->table_name} WHERE rel_id = $product_id AND type = 'like'" );
        
        return $JCK_WooSocial->shorten_number( $likes_count );
        
    }
    
}