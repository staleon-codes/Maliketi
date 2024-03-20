<?php
/**
 * The template part for displaying Featured Posts
 *
 * @package Motta
 */

$featured_content = new WP_Query( array(
    'post__in'  => $args
) );

if ( ! $featured_content->have_posts() ) {
	return;
}

$columns = \Motta\Helper::get_option( 'blog_featured_posts_columns' );

$classes = 'motta-featured-posts';
$classes .= ' featured-posts--columns-' . $columns;
$classes .= ' featured-posts--position-' . \Motta\Helper::get_option( 'blog_featured_position' );

?>
<div id="motta-featured-posts" class="<?php echo esc_attr( $classes ) ?>" data-columns="<?php echo esc_attr( $columns ) ?>">
	<div class="featured-posts__heading">
		<h3 class="featured-posts__title"><?php esc_html_e( 'Featured Posts', 'motta' ); ?></h3>
		<a class="featured-posts__link motta-button--subtle" href="<?php echo esc_url( \Motta\Helper::get_option( 'blog_featured_link_url' ) ) ?>"><?php esc_html_e( 'See All', 'motta' ) ?></a>
	</div>
	<div class="featured-posts__content">
		<div class="featured-posts__container swiper-container">
			<div class="featured-posts__wrapper swiper-wrapper">
				<?php
					while ( $featured_content->have_posts() ) : $featured_content->the_post();
						get_template_part( 'template-parts/content/content', 'featured' );
					endwhile;
				?>
			</div>
		</div>
		<?php
			echo \Motta\Icon::get_svg( 'left', 'ui', array( 'class' => 'swiper-button motta-swiper-button-prev' ) );
			echo \Motta\Icon::get_svg( 'right', 'ui', array( 'class' => 'swiper-button motta-swiper-button-next' ) );
		?>
	</div>
</div>
<?php
wp_reset_postdata();