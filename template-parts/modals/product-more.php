<?php
/**
 * Template part for displaying more content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Motta
 */
?>
<div id="product-more-popup" class="product-more__popup modal">
	<div class="modal__backdrop"></div>
	<div class="product-more__content">
		<div class="product-more__top">
			<div class="product-more__close modal__button-close"><?php echo \Motta\Icon::get_svg( 'close' ); ?></div>
		</div>
		<div class="product-more__buttons">
			<?php
				echo do_shortcode('[wcboost_compare_button]');
				echo do_shortcode('[wcboost_wishlist_button]');
				\Motta\WooCommerce\Single_Product::share_button('share-mini', esc_html__('Share this item', 'motta') );
			?>
		</div>
	</div>
</div>