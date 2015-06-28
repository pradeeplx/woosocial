<?php
    
class JCK_WooSocial_LikeSystem {
    
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
    
}