<?php if( $activity_feed ) { ?>

    <ul class="<?php echo $JCK_WooSocial->slug; ?>-actions">
    
        <?php foreach( $activity_feed as $action ){ ?>
            
            <?php include($JCK_WooSocial->templates->locate_template( 'profile/part-action.php' )); ?>
            
        <?php } ?>
        
        <li class="<?php echo $JCK_WooSocial->slug; ?>-actions__load-more">
            <a href="javascript: void(0);" class="<?php echo $JCK_WooSocial->slug; ?>-load-more <?php echo $JCK_WooSocial->slug; ?>-load-more--activity" data-limit="<?php echo $JCK_WooSocial->activity_log->default_limit; ?>" data-offset="<?php echo $JCK_WooSocial->activity_log->default_limit+1; ?>" data-user-id="<?php echo $JCK_WooSocial->profile_system->user_info->ID; ?>">
                <i class="woo-social-ic-loading"></i>
                <?php _e('Load more','jck-woo-social'); ?>
            </a>
        </li>
    
    </ul>
    
<?PHP } ?>