<?php
/* Template Name: email-changed */
get_header();
$email_text = get_field('email_text');
$email_content = get_field('email_content');
$email_content_p = get_field('email_content_p');
$email_button = get_field('email_button');
$user_image = get_field('user_image');
?>
<div class="container">
    <div class="parent_email_change_content">
        <div class="email-manage">
            <?php if (!empty($email_text)):?>
                <h1><?php echo $email_text;?></h1>
            <?php endif; ?>
            <?php if (!empty($email_content)):?>
                <div class="email-content">
                    <p><?php echo $email_content;?></p>
                </div>
            <?php endif; ?>
            <div class="email-current">
                <p>
                    <?php
                    $current_user = wp_get_current_user();
                    $user_email = $current_user->user_email;
                    echo "Envoyé à: <a href='mailto:" . $user_email . "'>" . $user_email . "</a>";
                    ?>
                </p>
            </div>
            <?php if (!empty($email_content_p)) : ?><p><?php echo $email_content_p; ?></p><?php endif; ?>
            <?php echo do_shortcode('[foobar]'); ?>
        </div>
        <?php if (!empty($user_image)) : ?>
            <div class="email-user-image">
                <img src="<?php echo $user_image['url']; ?>" alt="">
            </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>