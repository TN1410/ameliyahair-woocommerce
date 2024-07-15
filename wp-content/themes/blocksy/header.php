<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ameliyahair
 */
$header_image = get_field('header_image', 'option');

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php global $woocommerce; ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'ameliyahair'); ?></a>
		<header id="masthead" class="site-header">
			<div class="container">
				<div class="header_logo">
					<div class="logo_main">
						<a href="<?php echo get_home_url();?>"><img src="<?php echo $header_image['url']; ?>" alt=""></a>
					</div>
					<div class="cart_right">
						<a href="https://devwp1.websiteserverhost.biz/ameliyahair/cart/" class="notification">
							<i class="fal fa-shopping-cart"></i>
							<?php if ($woocommerce->cart->get_cart_contents_count() > 0): ?>
								<span class="badge"><?php echo $woocommerce->cart->get_cart_contents_count(); ?></span>
							<?php endif; ?>
						</a>
					</div>
				</div>
			</div>
		</header><!-- #masthead -->