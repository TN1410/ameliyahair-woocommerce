<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ameliyahair
 */
$footer_social_media_twitter  = get_field('footer_social_media_twitter', 'option');
$footer_social_media_instagram = get_field('footer_social_media_instagram', 'option');
$footer_social_media_facebook = get_field('footer_social_media_facebook', 'option');
$footer_text_one  = get_field('footer_text_one','option');
$footer_text_two  = get_field('footer_text_two','option');
$footer_copyright = get_field('footer_copyright', 'option');
?>
<footer id="colophon" class="site-footer">
	<div class="container">
		<div class="footer-section">
			<div class="f-social">
				<ul>
					<li><a href="<?php echo $footer_social_media_twitter;?>" title="twitter-in" target="_blank"><i class="fab fa-twitter"></i></a></li>
					<li><a href="<?php echo $footer_social_media_instagram;?>" title="instagram" target="_blank"><i class="fab fa-instagram-square"></i></a></li>
					<li><a href="<?php echo $footer_social_media_facebook;?>" title="facebook" target="_blank"><i class="fab fa-facebook"></i></a></li>
				</ul>
			</div>
				<div class="footer-text">
					<a href="#"><?php echo $footer_text_one;?></a> | <a href="#"><?php echo $footer_text_two;?></a>
				</div>
			<?php if(!empty($footer_copyright)):?>
				<div class="footer-copyright">
					<span>© Copyright <?php echo date("Y"); ?> <?php echo $footer_copyright;?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>