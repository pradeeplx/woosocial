<li>
    <a href="<?php echo $user->profile_url; ?>">
        <?php echo $user->avatar; ?>
        <h2><?php echo $user->user_nicename; ?></h2>
        <ul>
            <li><?php echo $user->likes_count_formatted; ?></li>
            <li><?php echo $user->followers_count_formatted; ?></li>
            <li><?php echo $user->following_count_formatted; ?></li>
        </ul>
    </a>
</li>