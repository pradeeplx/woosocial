<?php if( $activity_feed ) { ?>

    <ul class="<?php echo $JCK_WooSocial->slug; ?>-actions">
    
        <?php foreach( $activity_feed as $action ){ ?>
            
            <?php include($JCK_WooSocial->templates->locate_template( 'activity/part-action.php' )); ?>
            
        <?php } ?>
        
        <li class="<?php echo $JCK_WooSocial->slug; ?>-actions__load-more">
            <?php echo $JCK_WooSocial->get_load_more_button( 'activity' ); ?>
        </li>
    
    </ul>
    
<?PHP } ?>