<?php $links = array(
    array(
        'href' => 'activity',
        'text' => __('Activity','iconic-woosocial'),
        'icon' => 'bubble'
    ),
    array(
        'href' => 'user-likes',
        'text' => __('Likes', 'iconic-woosocial'),
        'icon' => 'heart',
        'count' => $user_info->likes_count
    ),
    array(
        'href' => 'followers',
        'text' => __('Followers', 'iconic-woosocial'),
        'icon' => 'followers',
        'count' => $user_info->followers_count
    ),
    array(
        'href' => 'following',
        'text' => __('Following', 'iconic-woosocial'),
        'icon' => 'following',
        'count' => $user_info->following_count
    ),
); ?>

<ul class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-profile-links">

    <?php $i = 0; foreach( $links as $link ) { ?>

        <?php $active_class = $i === 0 ? sprintf( '%s-profile-link--active', $GLOBALS['iconic_woosocial']->slug ) : ""; ?>

        <li class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-profile-links__item">
            <a href="#<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-<?php echo $link['href']; ?>" class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-profile-link <?php echo $active_class; ?>">
                <?php if( isset( $link['count'] ) ) { ?><span class="<?php echo $GLOBALS['iconic_woosocial']->slug; ?>-profile-link__count"><?php echo $link['count']; ?></span><?php } ?>
                <i class="iconic-woosocial-ic-<?php echo $link['icon']; ?>"></i>
                <?php echo $link['text']; ?>
            </a>
        </li>

    <?php $i++; } ?>

</ul>

<?php unset($i, $links); ?>