<?php
    
class JCK_WooSocial_FollowSystem {
    
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
    
}