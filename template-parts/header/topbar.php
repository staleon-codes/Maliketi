<?php
/**
 * Template part for displaying the topbar
 *
 * @package Motta
 */

 $topbar_class = '';
if ( \Motta\Helper::get_option( 'mobile_topbar' ) ) {
	$topbar_class = 'topbar-mobile';
	$topbar_class .= ' topbar-mobile--keep-' . \Motta\Helper::get_option( 'mobile_topbar_section' );
}
?>
<div id="topbar" class="topbar <?php echo esc_attr( $topbar_class ); ?>">
	<div class="topbar-container <?php echo esc_attr( apply_filters( 'motta_topbar_container_classes', 'container' ) ) ?>">
		<?php if ( isset( $args['left_items'][0]['item'] ) && ! empty( $args['left_items'][0]['item'] ) ) : ?>
			<div class="topbar-items topbar-left-items">
				<?php \Motta\Header\Topbar::items( $args['left_items']); ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $args['center_items'][0]['item'] ) && ! empty( $args['center_items'][0]['item'] ) ) : ?>
			<div class="topbar-items topbar-center-items">
				<?php \Motta\Header\Topbar::items( $args['center_items'] ); ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $args['right_items'][0]['item'] ) && ! empty( $args['right_items'][0]['item'] ) ) : ?>
			<div class="topbar-items topbar-right-items">
				<?php \Motta\Header\Topbar::items( $args['right_items'] ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
