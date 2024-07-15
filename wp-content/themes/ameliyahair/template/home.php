<?php
/* Template Name: Home */
get_header();
?>
<section class="Banner-section">

	<div class="slider-section owl-carousel owl-theme">
		<?php
		if (have_rows('home_section_repeater')) :
			while (have_rows('home_section_repeater')) : the_row();
				$home_banner_image = get_sub_field('home_banner_image');
				$home_h3_text = get_sub_field('home_h3_text');
				$home_text = get_sub_field('home_text');
				$home_banner_link = get_sub_field('home_banner_link');
		?>
				<div class="main-slider item">
					<div class="container">
						<div class="main_inner_slider">
							<div class="image-banner">
								<img src="<?php echo $home_banner_image['url']; ?>" alt="">
							</div>
							<div class="banner-ception">
								<h1><?php echo $home_h3_text; ?></h1>
								<p><?php echo $home_text; ?></p>
								<a href="<?php echo $home_banner_link['url']; ?>"><?php echo $home_banner_link['title']; ?></a>
							</div>
						</div>
					</div>
				</div>
		<?php
			endwhile;
		endif;
		?>
	</div>

</section>

<section class="blog-post_details">
    <div class="container">
        <div class="three-post-box">
            <?php
            function get_woocommerce_categories_with_info(){
                $args = array(
                    'taxonomy'     => 'product_cat',
                    'order' => 'DESC',
                    'product_category_not_in' => array('16')
                );
                $categories = get_terms($args);
                if (!empty($categories) && !is_wp_error($categories)) {
                    foreach ($categories as $category) {
                        echo '<div class="post-box">';
                            echo '<div class="image-box">';
                                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                $image = wp_get_attachment_image($thumbnail_id, 'full');
                                echo $image;
                            echo '</div>'; // Close image-box
                            echo '<div class="post-details">';
                                echo '<div class="post-caption">';
                                    echo '<h2 class="category-title">' . $category->name . '</h2>';
                                    $category_link = get_term_link($category->slug, 'product_cat');
                                    echo '<div class="category-description"><p>' . $category->description . '</p></div>';
                                echo '</div>'; // Close post-caption
                                echo '<div class="post-button">';
                                    echo '<a href="' . esc_url($category_link) . '">Einkaufen<span class="right-arrow"><img src="https://devwp1.websiteserverhost.biz/ameliyahair/wp-content/uploads/2024/02/Amelia-Hair_13.12.2022-1-xd.svg"></span></a>';
                                echo '</div>'; // Close post-button
                            echo '</div>'; // Close post-details
                        echo '</div>'; // Close post-box
                    }
                }
            }
            get_woocommerce_categories_with_info();
            ?>
        </div> <!-- Close three-post-box -->
    </div> <!-- Close container -->
</section> <!-- Close blog-post_details -->

<?php get_footer(); ?>