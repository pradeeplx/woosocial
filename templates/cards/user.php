<div>
    <?php echo $user->avatar_link; ?>
    <h2><a href="<?php echo $user->profile_url; ?>"><?php echo $user->user_nicename; ?></a></h2>
    <?php echo $user->follow_button; ?>
    <ul>
        <li><?php echo $user->likes_count_formatted; ?></li>
        <li><?php echo $user->followers_count_formatted; ?></li>
        <li><?php echo $user->following_count_formatted; ?></li>
    </ul>
</div>