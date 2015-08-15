<?php $links = array(
    array(
        'href' => 'activity',
        'text' => __('Activity','jck-woosocial'),
        'icon' => 'bubble'
    ),
    array(
        'href' => 'user-likes',
        'text' => __('Likes', 'jck-woosocial'),
        'icon' => 'heart',
        'count' => $user_info->likes_count
    ),
    array(
        'href' => 'followers',
        'text' => __('Followers', 'jck-woosocial'),
        'icon' => 'followers',
        'count' => $user_info->followers_count
    ),
    array(
        'href' => 'following',
        'text' => __('Following', 'jck-woosocial'),
        'icon' => 'following',
        'count' => $user_info->following_count
    ),
); ?>

<ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links">

    <?php $i = 0; foreach( $links as $link ) { ?>
    
        <?php $active_class = $i === 0 ? sprintf( '%s-profile-link--active', $GLOBALS['jck_woosocial']->slug ) : ""; ?>
    
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links__item">
            <a href="#<?php echo $GLOBALS['jck_woosocial']->slug; ?>-<?php echo $link['href']; ?>" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-link <?php echo $active_class; ?>">
                <i class="jck-woosocial-ic-<?php echo $link['icon']; ?>"></i> <?php echo $link['text']; ?>
                <?php if( isset( $link['count'] ) ) { ?><span class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-link__count"><?php echo $link['count']; ?></span><?php } ?>
            </a>
        </li>
    
    <?php $i++; } ?>
    
</ul>

<?php unset($i, $links); ?>