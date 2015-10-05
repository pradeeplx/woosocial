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
	
	    // Product cards
        add_action( 'jck_woosocial_product_card_before_content', array( $this, 'product_image' ),       10, 1 );
        add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_title' ),       10, 1 );
        add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_price' ),       20, 1 );
        add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_add_to_cart' ), 30, 1 );
        add_action( 'jck_woosocial_product_card_content',        array( $this, 'product_likes' ),       40, 1 );
        
        // User cards
        
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
	
}