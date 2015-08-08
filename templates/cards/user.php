<div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card <?php echo $GLOBALS['jck_woosocial']->slug; ?>-card--user">
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__image">
        <?php echo $user->avatar_link; ?>
    </div>
    
    <div class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__content">
    
        <h2 class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__title">
            <a href="<?php echo $user->profile_url; ?>"><?php echo $user->user_nicename; ?></a>
        </h2>
        
        <?php echo $user->follow_button; ?>
        
        <ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stats">
            <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stat"><?php echo $user->likes_count_formatted; ?></li>
            <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stat"><?php echo $user->followers_count_formatted; ?></li>
            <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-card__user-stat"><?php echo $user->following_count_formatted; ?></li>
        </ul>
    
    </div>
</div>