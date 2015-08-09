<?php $links = array(
    array(
        'href' => 'activity',
        'text' => __('Activity','jck-woosocial'),
    ),
    array(
        'href' => 'user-likes',
        'text' => $user_info->likes_count_formatted,
    ),
    array(
        'href' => 'followers',
        'text' => $user_info->followers_count_formatted,
    ),
    array(
        'href' => 'following',
        'text' => $user_info->following_count_formatted,
    ),
); ?>

<ul class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links">

    <?php $i = 0; foreach( $links as $link ) { ?>
    
        <?php $active_class = $i === 0 ? sprintf( '%s-tab-link--active', $GLOBALS['jck_woosocial']->slug ) : ""; ?>
    
        <li class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-links__item">
            <a href="#<?php echo $GLOBALS['jck_woosocial']->slug; ?>-<?php echo $link['href']; ?>" class="<?php echo $GLOBALS['jck_woosocial']->slug; ?>-profile-link <?php echo $GLOBALS['jck_woosocial']->slug; ?>-tab-link <?php echo $active_class; ?>">
                <?php echo $link['text']; ?>
            </a>
        </li>
    
    <?php $i++; } ?>
    
</ul>

<?php unset($i, $links); ?>