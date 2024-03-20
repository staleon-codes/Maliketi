<?php
/**
 * Blog functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Header;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Woocommerce initial
 *
 */
class Manager {
		/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'template_hooks' ) );
	}

	/**
	 * Add template hooks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function template_hooks() {
		if( \Motta\Helper::get_option( 'campaign_bar' ) && \Motta\Helper::get_option( 'campaign_bar_position' ) == 'before' ) {
			add_action( 'motta_before_header', array( $this, 'campaign_bar' ) );
		}

		if( \Motta\Helper::get_option( 'topbar' ) ) {
			add_action( 'motta_before_header', array( $this, 'topbar' ) );
		}

		if ( 'none' !== \Motta\Helper::get_option( 'header_sticky' ) ) {
			add_action( 'motta_before_header', array( $this, 'sticky_minimized' ) );
		}

		add_action( 'motta_header', array( $this, 'header' ) );

		if( \Motta\Helper::get_option( 'campaign_bar' ) && \Motta\Helper::get_option( 'campaign_bar_position' ) == 'after' ) {
			add_action( 'motta_after_header', array( $this, 'campaign_bar' ) );
		}

		add_filter( 'motta_header_container_classes', array( $this, 'container_class' ) );
		add_filter( 'motta_topbar_container_classes', array( $this, 'container_class' ) );

		add_filter( 'nav_menu_link_attributes', array( $this, 'menu_links' ), 20, 4 );
		add_filter( 'nav_menu_item_title', array( $this, 'menu_items' ), 20, 4 );
	}

	/**
	 * Display header top bar
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function topbar() {
		if( ! apply_filters( 'motta_get_topbar', \Motta\Helper::get_option( 'topbar' ) ) ) {
			return;
		}

		$items = array(
			'left_items' => (array) \Motta\Helper::get_option( 'topbar_left' ),
			'center_items' => (array) \Motta\Helper::get_option( 'topbar_center' ),
			'right_items' => (array) \Motta\Helper::get_option( 'topbar_right' )
		);

		get_template_part( 'template-parts/header/topbar', '', $items );
	}

	/**
	 * Displays header content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function header() {
		$show_header = ! get_post_meta( \Motta\Helper::get_post_ID(), 'motta_hide_header_section', true );
		if ( ! apply_filters( 'motta_get_header', $show_header ) ) {
			return;
		}

		$header 		= \Motta\Header\Main::instance();
		$classes = $header->sticky_classes();
		$classes .= ' header-' .  $header->get_layout();
		echo '<div class="site-header__desktop site-header__section ' . esc_attr( $classes ) . '">';
		$header->render();
		$header->sticky_render();
		echo '</div>';

		$header_mobile 	= \Motta\Header\Mobile::instance();
		$classes = $header_mobile->sticky_classes();
		$classes .= ' header-' .  $header_mobile->get_layout();
		echo '<div class="site-header__mobile site-header__section ' . esc_attr( $classes ) . '">';
		$header_mobile->render();
		$header_mobile->sticky_render();
		echo '</div>';
	}

	/**
	 * Display header campaign bar
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function campaign_bar() {
		if( ! apply_filters( 'motta_get_campaign_bar', \Motta\Helper::get_option( 'campaign_bar' ) ) ) {
			return;
		}

		get_template_part( 'template-parts/header/campaign-bar', '', (array) \Motta\Helper::get_option( 'campaign_items' ) );
	}

	/**
	 * Header class container in header version
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function container_class( $classes ) {
		$layout 	= \MOtta\Header\Main::get_layout();
		$present 	= \Motta\Helper::get_option( 'header_present' );

		$layout = 'prebuild' == $present ? $layout : 'custom';

		if ( $present == 'custom' ) {
			$classes = \Motta\Helper::get_option( 'header_container' );
			$classes = empty( $classes ) ? 'container' : $classes;
		} else {
			if ( $layout == 'v3' || $layout == 'v9' ) {
				$classes = 'motta-container';
			} else {
				$classes = 'container';
			}
		}

		return $classes;
	}

	/**
	 * Display sticky header
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function classes( $classes ) {
		$background = apply_filters( 'motta_get_header_background', '' );
		if ( 'transparent' == $background && \Motta\Header\Main::get_layout() == 'v12' ) {
			$text_color = apply_filters( 'motta_get_header_text_color', '' );

			if ( $text_color != 'default' ) {
				$classes .= ' header-transparent-text-' . $text_color;
			}

			$classes .= ' header-' . $background;
		}


		echo esc_attr( apply_filters( 'motta_site_header_class', $classes ) );
	}

	/**
	 * Display the site header sticky minimized
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function sticky_minimized() {
		$show_header = ! get_post_meta( \Motta\Helper::get_post_ID(), 'motta_hide_header_section', true );
		$background = get_post_meta( \Motta\Helper::get_post_ID(), 'motta_header_background', true );

		if ( ! apply_filters( 'motta_get_sticky_header', $show_header ) ) {
			return;
		}

		if ( 'transparent' == $background && \Motta\Header\Main::get_layout() == 'v12' ) {
			return;
		}

		echo '<div id="site-header-minimized"></div>';
	}

	/**
	 * Add arrow menu item
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function menu_items( $title, $menu_item, $args, $depth ) {
		// Only support main navigations.
		if ( empty($args->theme_location) || ! in_array( $args->theme_location, array( 'primary-menu', 'secondary-menu', 'category-menu' ) ) ) {
			return $title;
		}

		$layout = \Motta\Header\Main::get_layout();
		$primary_caret = $secondary_caret = false;

		if ( in_array( $layout, array( 'v3' ) ) ) {
			$secondary_caret = true;
		} elseif ( \Motta\Helper::get_option( 'header_primary_menu_caret' ) ) {
			$primary_caret = true;
		} elseif ( \Motta\Helper::get_option( 'header_secondary_menu_caret' ) ) {
			$secondary_caret = true;
		}

		$data_grid = get_post_meta( $menu_item->ID, '_menu_item_mega_grid', true );

		if ( ( in_array( 'menu-item-has-children', $menu_item->classes ) || $data_grid ) && $depth == 0 ) {
			if ( $primary_caret && $args->theme_location == 'primary-menu' ) {
				$title .= \Motta\Icon::get_svg( 'select-arrow', 'ui', 'class=caret' );
			}

			if ( $secondary_caret && $args->theme_location == 'secondary-menu' ) {
				$title .= \Motta\Icon::get_svg( 'select-arrow', 'ui', 'class=caret' );
			}
		}

		return $title;
	}

	/**
	 * Add arrow menu item
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function menu_links( $atts, $item, $args, $depth ) {
		if ( empty( $args->theme_location ) ) {
			return $atts;
		}

		if ( $item->title ) {
			$atts['data-title'] = $item->title ? $item->title : '';
		}

		if( ! class_exists('Motta\Addons\Modules\Mega_Menu\Walker')) {
			return $atts;
		}

		$walker = \Motta\Addons\Modules\Mega_Menu\Walker::get_mega_data();
		$menu_image = '';

		if ( isset( $walker['mt_menu_image'] ) && ! empty( $walker['mt_menu_image'] ) ) {
			$menu_image = $walker['mt_menu_image'];
		} else {
			if ( $item->type == 'taxonomy' ) {
				$image_id  = absint( get_term_meta( $item->object_id, 'motta_page_header_bg_id', true ) );

				if ( $image_id ) {
					$image          = wp_get_attachment_image_src( $image_id, 'full' );
					$menu_image 	= $image ? $image[0] : '';
				}
			}
		}

		if ( $menu_image ) {
			$atts['data-image'] = $menu_image;
		}

		return $atts;
	}

	/**
	 * check the sticky header.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function has_sticky() {
		if ( 'none' !== \Motta\Helper::get_option( 'header_sticky' ) || 'none' !== \Motta\Helper::get_option( 'header_mobile_sticky' ) ) {
			return true;
		}

		return false;
	}

}
