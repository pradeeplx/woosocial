<?php

class Iconic_WooSocial_LikeSystem {

    /**
     * Product likes meta key
     *
     * @since 1.0.1
     * @access public
     * @var str
     */
    public $product_likes_meta_key = "_iconic_woosocial_likes";

    /**
     * Construct the class
     */
    public function __construct() {

        add_action( 'init', array( $this, 'initiate_hook' ) );
        add_action( 'wp',   array( $this, 'wp_hook' ) );

    }

    /**
     * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
     */
    public function initiate_hook() {

    	add_action( 'wp_ajax_iconic_woosocial_product_like_action',  array( $this, 'like_action' ) );

        add_action( 'wp_ajax_iconic_woosocial_load_more_likes', array( $this, 'load_more' ) );
        add_action( 'wp_ajax_nopriv_iconic_woosocial_load_more_likes', array( $this, 'load_more' ) );

        //add_filter( 'woocommerce_get_catalog_ordering_args', array( $this, 'add_catalog_ordering_args' ), 10, 1 );
        //add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'catalog_orderby' ), 10, 1 );
        //add_filter( 'woocommerce_catalog_orderby', array( $this, 'catalog_orderby' ), 10, 1 );

	}

    /**
     * Run after wp is fully set up and $post is accessible (http://codex.wordpress.org/Plugin_API/Action_Reference)
     */
    public function wp_hook() {

    	if(!is_admin()) {

        	$settings = $GLOBALS['iconic_woosocial']->settings;

            if( $settings['likes_likes_category_display'] !== "none" && ( is_product_category() || is_shop() ) && !is_search() )
                add_action( $settings['likes_likes_category_display'], array( $this, 'show_likes_loop' ) );

            if( $settings['likes_likes_search_display'] !== "none" && is_search() )
                add_action( $settings['likes_likes_search_display'], array( $this, 'show_likes_loop' ) );

            if( $settings['likes_likes_product_display'] !== "none" && is_product() ) {

                $position = explode('/', $settings['likes_likes_product_display']);
                $priority = isset( $position[1] ) ? (int)$position[1] : 10;

                add_action( $position[0], array( $this, 'show_likes_loop' ), $priority );

            }

        }

	}

    /**
     * Ajax: Follow Action
     */
    function like_action() {

    	if ( ! wp_verify_nonce( $_GET['nonce'], $GLOBALS['iconic_woosocial']->slug ) )
    		die ( 'Busted!' );

        $current_user_id = get_current_user_id();

        $response = array(
            'add_like_html' => false,
            'remove_like_class' => false
        );

        if( $_GET['type'] == "like" ) {

            $action = $GLOBALS['iconic_woosocial']->activity_log->add_like( $current_user_id, $_GET['product_id'] );

            $response['button']['type'] = 'unlike';
            $response['add_like_html'] = $this->get_like_list_item( $current_user_id );

        } else {

            $action = $GLOBALS['iconic_woosocial']->activity_log->remove_like( $current_user_id, $_GET['product_id'] );

            $response['button']['type'] = 'like';
            $response['remove_like_class'] = sprintf( '.%s-product-likes__item--%s', $GLOBALS['iconic_woosocial']->slug, $current_user_id );

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

    /**
     * Ajax: Load More Action
     */
    function load_more() {

        $response = array(
            'likes_likes_html' => false
        );

        $likes = $GLOBALS['iconic_woosocial']->like_system->get_likes( $_GET['user_id'], $_GET['limit'], $_GET['offset'] );

        $response['likes'] = $likes;

        if( $likes ) {

            ob_start();

            foreach( $likes as $like ) {

                $product = $GLOBALS['iconic_woosocial']->like_system->get_product_info( $like->rel_id );
                include($GLOBALS['iconic_woosocial']->templates->locate_template( 'cards/product.php' ));

            }

            $response['likes_likes_html'] = ob_get_clean();

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

    /**
     * Has Liked
     *
     * @param int $user_id
     * @param int $follow_user_id
     * @return bool
     */
    public function has_liked( $user_id, $product_id ) {

        global $wpdb;

        $liked = $wpdb->get_row(
        	"
        	SELECT *
        	FROM {$GLOBALS['iconic_woosocial']->activity_log->table_name}
        	WHERE user_id = $user_id
        	AND rel_id = $product_id
        	AND type = 'like'
        	"
        );

        return ( $liked ) ? $liked : false;
    }

    /**
     * Get Like List item
     *
     * @param int $user_id
     * @return str
     */
    public function get_like_list_item( $user_id ) {

        $user = $GLOBALS['iconic_woosocial']->profile_system->get_user_info( $user_id );

        ob_start();
        include($GLOBALS['iconic_woosocial']->templates->locate_template( 'product/part-like-item.php' ));
        $like_list_item = ob_get_clean();

        return $like_list_item;

    }

    /**
     * Show Likes in the Loop
     *
     * @param int $product_id
     */
    public function show_likes_loop( $product_id = false ) {

        global $post;

        $product_id = ( $product_id ) ? $product_id : $post->ID;
        $product_likes = $this->get_product_likes( $product_id );

        include($GLOBALS['iconic_woosocial']->templates->locate_template( 'product/loop-likes.php' ));

    }

    /**
     * Get like button
     *
     * @param array $args
     * @return str
     */
    public function get_like_button( $args ) {

        $defaults = array(
            'type' => false,
            'product_id' => false
        );

        $args = wp_parse_args( $args, $defaults );

        if( !$args['product_id'] )
            return "";

        $product_id = $args['product_id'];
        $type = $this->has_liked( get_current_user_id(), $product_id ) ? "unlike" : "like";
        $product_likes_count = $this->get_product_likes_count( $product_id );

        $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
        $myaccount_page_url = ( $myaccount_page_id ) ? sprintf( '%s?like-product=%d', get_permalink( $myaccount_page_id ), $args['product_id'] ) : "javascript: void(0);";

        $button_classes = implode(' ', apply_filters( 'iconic_woosocial_like_button_classes', array(
            sprintf( '%s-btn', $GLOBALS['iconic_woosocial']->slug ),
            sprintf( '%s-btn--like', $GLOBALS['iconic_woosocial']->slug ),
            sprintf( '%s-product-like-action', $GLOBALS['iconic_woosocial']->slug ),
            sprintf( '%s-product-like-action--%s', $GLOBALS['iconic_woosocial']->slug, $type )
        ), $product_id, $type, $product_likes_count ));
        $href = ( is_user_logged_in() ) ? "javascript: void(0);" : $myaccount_page_url;

        ob_start();
        include($GLOBALS['iconic_woosocial']->templates->locate_template( 'product/part-like-button.php' ));
        $like_button = ob_get_clean();

        return $like_button;

    }

    /**
     * Get Likes
     *
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return obj
     */
    public function get_likes( $user_id, $limit = null, $offset = null ) {

        if( $limit === null ) $limit = $GLOBALS['iconic_woosocial']->activity_log->default_limit;
        if( $offset === null ) $offset = $GLOBALS['iconic_woosocial']->activity_log->default_offset;

        global $wpdb;

        $likes = $wpdb->get_results(
        	"
        	SELECT * FROM `{$GLOBALS['iconic_woosocial']->activity_log->table_name}` AS log
            INNER JOIN `{$wpdb-> posts}` AS posts
            ON posts.ID = log.rel_id
            AND log.user_id = $user_id
            AND log.type = 'like'
            AND posts.post_status = 'publish'
            ORDER BY log.time DESC
            LIMIT $limit
            OFFSET $offset
        	"
        );

        return $likes;

    }

    /**
     * Get Likes count by user ID
     *
     * @param int $user_id
     * @return int
     */
    public function get_likes_count( $user_id ) {

        if( $user_id == "" )
            return 0;

        global $wpdb;

        $likes_count = $wpdb->get_var(
            "
            SELECT COUNT(*)
            FROM `{$GLOBALS['iconic_woosocial']->activity_log->table_name}` AS log
            INNER JOIN `{$wpdb-> posts}` AS posts
            ON posts.ID = log.rel_id
            AND posts.post_status = 'publish'
            AND log.user_id = $user_id
            AND log.type = 'like'
            "
        );

        return $GLOBALS['iconic_woosocial']->shorten_number( $likes_count );

    }

    /**
     * Get Product Likes
     *
     * Get Likes for a single Product
     *
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return obj
     */
    public function get_product_likes( $product_id, $limit = 5 ) {

        global $wpdb;

        $likes = $wpdb->get_results(
        	"
        	SELECT *
        	FROM {$GLOBALS['iconic_woosocial']->activity_log->table_name}
        	WHERE rel_id = $product_id
        	AND type = 'like'
        	ORDER BY time DESC
        	LIMIT $limit
        	"
        );

        return $likes;

    }

    /**
     * Get Likes count by product ID
     *
     * @param int $product_id
     * @return int
     */
    public function get_product_likes_count( $product_id ) {

        if( $product_id == "" )
            return 0;

        global $wpdb;

        $likes_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$GLOBALS['iconic_woosocial']->activity_log->table_name} WHERE rel_id = $product_id AND type = 'like'" );

        return $GLOBALS['iconic_woosocial']->shorten_number( $likes_count );

    }

    /**
     * Get Product Info
     *
     * @param int $product_id
     * @return obj
     */
    public function get_product_info( $product_id ) {

        $product_id = absint( $product_id );

        $product = wc_get_product( $product_id );

        $product->title = $product->post->post_title;
        $product->url = get_permalink( $product_id );
        $product->link = sprintf('<a href="%s" title="%s">%s</a>', esc_attr( $product->url ), esc_attr( $product->title ), $product->title);
        $product->sku = $product->get_sku();
        $product->price_html = $product->get_price_html();

        $product->image_link = sprintf( '<a href="%s" title="%s">%s</a>', esc_attr( $product->url ), esc_attr( $product->title ), $product->get_image( 'shop_catalog' ) );

        $product->add_to_cart_button = do_shortcode( sprintf( '[add_to_cart id="%d" sku="%s" style="" show_price="false" class="%s-add-to-cart-wrapper"]', $product_id, $product->sku, $GLOBALS['iconic_woosocial']->slug ) );

        $product->likes_count = $this->get_product_likes_count( $product_id );

        return $product;

    }

    /*
     * Get "Likes" Alignment Class
     *
     * @return str
     */
    public function get_likes_alignment_class() {

        $class = sprintf("%s-product-likes-wrapper--", $GLOBALS['iconic_woosocial']->slug);

        if( ( is_category() || is_shop() ) && !is_search() ) {

            $class .= $GLOBALS['iconic_woosocial']->settings['likes_likes_category_align'];

        } elseif( is_search() ) {

            $class .= $GLOBALS['iconic_woosocial']->settings['likes_likes_search_align'];

        } else {

            $class .= $GLOBALS['iconic_woosocial']->settings['likes_likes_product_align'];

        }

        return $class;

    }

    /**
     * Add option to order by likes
     */
    public function add_catalog_ordering_args( $sort_args ) {

        $orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

        if ( 'likes' == $orderby_value ) {
            $sort_args['orderby'] ='meta_value_num title';
            $sort_args['order'] = 'desc';
            $sort_args['meta_key'] = $this->product_likes_meta_key;
        }

        return $sort_args;

    }

    /**
     * Add order_by option to dropdown
     *
     * @param array $orderby
     * @return array
     */
    public function catalog_orderby( $orderby ) {

        $orderby['likes'] = __('Sort by likes', 'iconic-woosocial');

        return $orderby;

    }

    /**
     * Update product likes count
     *
     * @param $product_id
     * @param str $type add/remove
     */
    public function update_products_like_count( $product_id, $type = "add" ) {

        $product_likes = get_post_meta($product_id, $this->product_likes_meta_key, true);

        if( !$product_likes ) { $product_likes = 0; }

        if( $type == "add" ) {

            $product_likes = (int)$product_likes + 1;

        } else {

            $product_likes = $product_likes > 0 ? (int)$product_likes - 1 : 0;

        }

        update_post_meta($product_id, $this->product_likes_meta_key, $product_likes);

    }

    /**
     * Recalculate likes for all products
     */
    public function recalculate_likes() {

        $args = array(
            'post_type' => array('product','product_variation'),
            'posts_per_page' => -1,
            'post_status' => 'any'
        );

        $products = new WP_Query( $args );

        if ( !$products->have_posts() )
            return;

        foreach( $products->posts as $product ) {

            $product_likes = $this->get_product_likes_count( $product->ID );

            error_log( print_r( $product_likes, true ) );

            //update_post_meta($product->ID, $this->product_likes_meta_key, $product_likes);

        }

        wp_reset_postdata();

    }

}