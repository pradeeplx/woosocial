<?php if( $activity_feed ) { ?>

    <ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-actions" >
    
        <?php foreach( $activity_feed as $action ){ ?>
            
            <?php include($GLOBALS['jck_woosocial']->templates->locate_template( 'activity/part-action.php' )); ?>
            
        <?php } ?>
        
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-actions__load-more">
            <?php echo $GLOBALS['jck_woosocial']->get_load_more_button( 'activity' ); ?>
        </li>
    
    </ul>
    
<?PHP } ?>