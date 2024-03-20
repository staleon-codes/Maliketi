<?php
/**
 * Template part for displaying the category menu
 *
 * @package Motta
 */

$display = ( isset( $args['display'] ) && ! empty( $args['display'] ) ) ? $args['display'] : 'both';
$type = ( isset( $args['type'] ) && ! empty( $args['type'] ) ) ? $args['type'] : '';
$title = ( isset( $args['title'] ) && ! empty( $args['title'] ) ) ? $args['title'] : '';
$icon = ( isset( $args['icon'] ) && ! empty( $args['icon'] ) ) ? $args['icon'] : 'v1';

$icon_html = \Motta\Icon::get_svg( 'categories', 'ui', 'class=header-category__icon' );
if ( $icon == 'v2' ) {
	$icon_html = \Motta\Icon::get_svg( 'categories-v2', 'ui', 'class=header-category__icon' );
}

$spacing = ( isset( $args['spacing'] ) && ! empty( $args['spacing'] ) ) ? $args['spacing'] : '';
$style = $spacing ? 'style=margin-left:'. $spacing .'px' : '';

$class_button = ( isset( $args['class_button'] ) && ! empty( $args['class_button'] ) ) ? $args['class_button'] : '';

if ( get_post_meta( \Motta\Helper::get_post_ID(), 'header_category_menu_display', true ) == 'onpageload' ) {
	$args['classes'] .= ' header-category--show header-category--open motta-open';
}

?>
<div class="header-category-menu <?php echo esc_attr( $args['classes'] ) ?>" <?php echo esc_attr( $style )?>>
	<div class="header-category__title <?php echo esc_attr( $class_button ) ?>">
		<?php
			if ( $display != 'text' ) {
				echo ! empty( $icon_html ) ? $icon_html : '';
			}

			if( $display == 'both' || $display == 'text' ) {
				echo \Motta\Icon::get_svg( 'select-arrow', 'ui', 'class=header-category__arrow' );

				if ( ! empty( $title ) ) {
					echo '<span class="header-category__name">'. $title .'</span>';
				}
			}
		?>
	</div>
	<div class="header-category__content">
		<?php
			if ( has_nav_menu( 'category-menu' ) ) {
				if ( isset( $args['mega_menu'] ) && $args['mega_menu'] && class_exists( '\Motta\Addons\Modules\Mega_Menu\Walker' ) ) {
					wp_nav_menu( apply_filters( 'motta_navigation_header_category_content', array(
						'theme_location' 	=> 'category-menu',
						'container'      	=> 'nav',
						'container_class'   => 'header-category__menu',
						'menu_class'     	=> 'menu',
						'walker'			=> new \Motta\Addons\Modules\Mega_Menu\Walker()
					) ) );
				} else {
					wp_nav_menu( apply_filters( 'motta_navigation_header_category_content', array(
						'theme_location' 	=> 'category-menu',
						'container'      	=> 'nav',
						'container_class'   => 'header-category__menu',
						'menu_class'     	=> 'menu',
					) ) );
				}
			}
		?>
	</div>
</div>
