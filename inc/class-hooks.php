<?php
    
class JCK_WooSocial_Hooks {
    
/**	=============================
    *
    * Construct the class
    *
    ============================= */
   	
    public function __construct() {
        
        add_action( 'init',             array( $this, 'initiate_hook' ) );
        
    }

/**	=============================
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
   	
	public function initiate_hook() {
    	
    	$settings = $GLOBALS['jck_woosocial']->settings;
	
	    // Product cards
	    if( $settings['product_cards_show_image'] )
            add_action( 'jck_woosocial_product_card_before_content', array( $this, 'product_image' ),       10, 1 );
        if( $settings['product_cards_show_title'] )
            add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_title' ),       10, 1 );
        if( $settings['product_cards_show_price'] )
            add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_price' ),       20, 1 );
        if( $settings['product_cards_show_add_to_cart_button'] )
            add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_add_to_cart' ), 30, 1 );
        if( $settings['product_cards_show_likes'] )
            add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_likes' ),       40, 1 );
        
        // User cards
        if( $settings['user_cards_show_image'] )
            add_action( 'jck_woosocial_user_card_before_content',    array( $this, 'user_image' ),          10, 1 );
        if( $settings['user_cards_show_name'] )
            add_action( 'jck_woosocial_user_card_content',           array( $this, 'user_name' ),           10, 1 );
        if( $settings['user_cards_show_follow_button'] )
            add_action( 'jck_woosocial_user_card_content',           array( $this, 'user_follow_button' ),  20, 1 );
        if( $settings['user_cards_show_user_stats'] )
            add_action( 'jck_woosocial_user_card_content',           array( $this, 'user_stats' ),          30, 1 );
        
	}
	
/** =============================
    *
    * Product Image
    *
    * @param  [obj] [$product]
    *
    ============================= */
    
    public function product_image( $product ) {
        ?>
        <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__image">
            <?php echo $product->image_link; ?>
        </div>
        <?php        
    }

/** =============================
    *
    * Product Title
    *
    * @param  [obj] [$product]
    *
    ============================= */
    
    public function product_title( $product ) {
        ?>
        <h2 class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__title">
            <a href="<?php echo $product->url; ?>" title="<?php echo esc_attr($product->title); ?>"><?php echo $product->title; ?></a>
        </h2>
        <?php        
    }

/** =============================
    *
    * Product Price
    *
    * @param  [obj] [$product]
    *
    ============================= */
    
    public function product_price( $product ) {
        ?>
        <span class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card--product__price">
            <?php echo $product->price_html; ?>
        </span>
        <?php        
    }

/** =============================
    *
    * Product Add to Cart
    *
    * @param  [obj] [$product]
    *
    ============================= */
    
    public function product_add_to_cart( $product ) {
        
        echo $product->add_to_cart_button;
                  
    }
    
/** =============================
    *
    * Product Likes
    *
    * @param  [obj] [$product]
    *
    ============================= */
    
    public function product_likes( $product ) {
        
        $GLOBALS['jck_woosocial']->like_system->show_likes_loop( $product->id );
                   
    }

/** =============================
    *
    * User Image
    *
    * @param  [obj] [$user]
    *
    ============================= */
    
    public function user_image( $user ) {
        ?>
        <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__image">
            <?php echo $user->avatar_link; ?>
        </div>
        <?php        
    }

/** =============================
    *
    * User Name
    *
    * @param  [obj] [$user]
    *
    ============================= */
    
    public function user_name( $user ) {
        ?>
        <h2 class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__title">
            <a href="<?php echo $user->profile_url; ?>"><?php echo $user->display_name; ?></a>
        </h2>
        <?php        
    }
    
/** =============================
    *
    * User Follow Button
    *
    * @param  [obj] [$user]
    *
    ============================= */
    
    public function user_follow_button( $user ) {
        
        echo $user->follow_button;
                  
    }
    
/** =============================
    *
    * User Stats
    *
    * @param  [obj] [$user]
    *
    ============================= */
    
    public function user_stats( $user ) {
        ?>
        <ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stats">
            <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stat"><?php echo $user->likes_count_formatted; ?></li>
            <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stat"><?php echo $user->followers_count_formatted; ?></li>
            <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stat"><?php echo $user->following_count_formatted; ?></li>
        </ul>
        <?php        
    }
	
}