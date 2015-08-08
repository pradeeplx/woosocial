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
    	
    	add_action( 'wp_ajax_jck_woo_social_like_action',                array( $this, 'like_action' ) );
        
        add_action( 'wp_ajax_jck_woo_social_load_more_likes',            array( $this, 'load_more' ) );
        add_action( 'wp_ajax_nopriv_jck_woo_social_load_more_likes',     array( $this, 'load_more' ) );

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
    
    function like_action() {
        
        
    	
    	if ( ! wp_verify_nonce( $_GET['nonce'], $GLOBALS['jck_woosocial']->slug ) )
    		die ( 'Busted!' );
    		
        $current_user_id = get_current_user_id();
        
        $response = array(
            'add_like_html' => false,
            'remove_like_class' => false
        );
        
        if( $_GET['type'] == "like" ) {
    		
            $action = $GLOBALS['jck_woosocial']->activity_log->add_like( $current_user_id, $_GET['product_id'] );
            
            $response['button']['type'] = 'unlike';
            $response['add_like_html'] = $this->get_like_list_item( $current_user_id );
        
        } else {
            
            $action = $GLOBALS['jck_woosocial']->activity_log->remove_like( $current_user_id, $_GET['product_id'] );
            
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
    * Ajax: Load More Action
    *
    ============================= */
    
    function load_more() {
        
        
        
        $response = array(
            'likes_html' => false
        );
        
        $likes = $GLOBALS['jck_woosocial']->like_system->get_likes( $_GET['user_id'], $_GET['limit'], $_GET['offset'] );
        
        $response['likes'] = $likes;
        
        if( $likes ) {
            
            ob_start();
            
            foreach( $likes as $like ) {
                
                $product = $GLOBALS['jck_woosocial']->like_system->get_product_info( $like->rel_id );        
                include($GLOBALS['jck_woosocial']->templates->locate_template( 'cards/product.php' ));
                
            }
            
            $response['likes_html'] = ob_get_clean();
            
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
        
        global $wpdb;
        
        $liked = $wpdb->get_row( 
        	"
        	SELECT *
        	FROM {$GLOBALS['jck_woosocial']->activity_log->table_name}
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
        
        $user = $GLOBALS['jck_woosocial']->profile_system->get_user_info( $user_id );
        
        ob_start();
        include($GLOBALS['jck_woosocial']->templates->locate_template( 'product/loop-likes.php' ));
        $like_list_item = ob_get_clean();
        
        return $like_list_item;
        
    }

/**	=============================
    *
    * Show Likes in the Loop
    *
    ============================= */
    
    public function show_likes_loop( $product_id = false ) {
        
        global $post;
        
        $product_id = ( $product_id ) ? $product_id : $post->ID;
        $product_likes = $this->get_product_likes( $product_id );
        
        include($GLOBALS['jck_woosocial']->templates->locate_template( 'product/loop-likes.php' ));
        
    }

/**	=============================
    *
    * Get like button
    *
    * @param array $args
    * @return str
    *
    ============================= */
    
    public function get_like_button( $args ) {
        
        $defaults = array(
            'type' => false,
            'product_id' => false
        );
        
        $args = wp_parse_args( $args, $defaults );
        
        if( !$args['product_id'] )
            return "";
            
        
            
        $type = $this->has_liked( get_current_user_id(), $args['product_id'] ) ? "unlike" : "like";
        $product_likes_count = $this->get_product_likes_count( $args['product_id'] );
        
        $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
        $myaccount_page_url = ( $myaccount_page_id ) ? sprintf( '%s?like-product=%d', get_permalink( $myaccount_page_id ), $args['product_id'] ) : "javascript: void(0);";

        $button_classes = implode(' ', array(
            'button',
            sprintf( '%s-button', $GLOBALS['jck_woosocial']->slug ),
            sprintf( '%s-like-action', $GLOBALS['jck_woosocial']->slug ),
            sprintf( '%s-like-action--%s', $GLOBALS['jck_woosocial']->slug, $type )
        ));
        $href = ( is_user_logged_in() ) ? "javascript: void(0);" : $myaccount_page_url;
        
        return sprintf( '<a href="%s" class="%s" data-type="%s" data-product-id="%d"><i class="woo-social-ic-like"></i> <span class="jck-woo-social-like-button__count">%d</span></a>', $href, $button_classes, $type, $args['product_id'], $product_likes_count );
        
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
        
        global $wpdb;
        
        $likes = $wpdb->get_results( 
        	"
        	SELECT *
        	FROM {$GLOBALS['jck_woosocial']->activity_log->table_name}
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
        
        global $wpdb;
        
        $likes_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$GLOBALS['jck_woosocial']->activity_log->table_name} WHERE user_id = $user_id AND type = 'like'" );
        
        return $GLOBALS['jck_woosocial']->shorten_number( $likes_count );
        
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
        
        global $wpdb;
        
        $likes = $wpdb->get_results( 
        	"
        	SELECT *
        	FROM {$GLOBALS['jck_woosocial']->activity_log->table_name}
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
        
        global $wpdb;
        
        $likes_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$GLOBALS['jck_woosocial']->activity_log->table_name} WHERE rel_id = $product_id AND type = 'like'" );
        
        return $GLOBALS['jck_woosocial']->shorten_number( $likes_count );
        
    }

/**	=============================
    *
    * Get Product Info
    *
    * @param int $product_id
    * @return obj
    *
    ============================= */
    
    public function get_product_info( $product_id ) {
        
        $product = wc_get_product( $product_id );
        
        $product->title = $product->post->post_title;
        $product->url = get_permalink( $product_id );
        $product->link = sprintf('<a href="%s" title="%s">%s</a>', esc_attr( $product->url ), esc_attr( $product->title ), $product->title);
        $product->sku = $product->get_sku();
                    
        $product->image_link = sprintf( '<a href="%s" title="%s">%s</a>', esc_attr( $product->url ), esc_attr( $product->title ), get_the_post_thumbnail( $product_id, 'thumbnail' ) );
        
        $product->add_to_cart_button = do_shortcode( sprintf( '[add_to_cart id="%d" sku="%s" style=""]', $product_id, $product->sku ) );
        
        $product->likes_count = $this->get_product_likes_count( $product_id );
        
        return $product;
        
    }
    
}