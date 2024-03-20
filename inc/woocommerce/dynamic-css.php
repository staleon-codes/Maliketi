<?php
/**
 * Style functions and definitions.
 *
 * @package Motta
 */

namespace Motta\WooCommerce;

use Motta\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Dynamic_CSS {
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
		add_action( 'motta_wc_inline_style', array( $this, 'add_static_css' ) );
	}

	/**
	 * Get get style data
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function add_static_css( $parse_css ) {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return $parse_css;
		}

		$parse_css .= $this->shop_static_css();
		$parse_css .= $this->product_card_static_css();
		$parse_css .= $this->single_product_static_css();

		return $parse_css;
	}

	/**
	 * Get CSS code of settings for shop.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function shop_static_css() {
		$static_css = '';

		if( ! Helper::is_catalog() ) {
			return $static_css;
		}

		if ( 'standard' == Helper::get_option( 'shop_page_header' ) ) {
			$page_header_height             = Helper::get_option( 'shop_page_header_height' );
			$page_header_mobile_height      = Helper::get_option( 'shop_page_header_mobile_height' );
			$page_header_textcolor          = Helper::get_option( 'shop_page_header_textcolor' );
			$page_header_textcolor_custom   = Helper::get_option( 'shop_page_header_textcolor_custom' );

			if ( is_product_taxonomy() ) {
				$term_id  = get_queried_object_id();
				$motta_page_header_textcolor                  = get_term_meta( $term_id, 'motta_page_header_textcolor', true );
				$motta_page_header_textcolor_custom           = get_term_meta( $term_id, 'motta_page_header_textcolor_custom', true );

				if ( $motta_page_header_textcolor == 'custom' ) {
					$page_header_textcolor         = $motta_page_header_textcolor;
					$page_header_textcolor_custom  = $motta_page_header_textcolor_custom;
				}
			}

			$static_css .= '.page-header--products .page-header__content {
				height: ' . intval( $page_header_height ) . 'px;
			}';

			if( $page_header_textcolor == 'custom' ) {
				$static_css .= '.page-header--text-custom {
					--motta-text-color: ' . $page_header_textcolor_custom . ';
				}';
			}

			$static_css .= '@media (max-width: 767px) { .page-header--products .page-header__content {
				height: ' . intval( $page_header_mobile_height  ) . 'px;
			} }';
		}

		if( Helper::get_option('catalog_product_description') ) {
			$static_css .= '.catalog-view-list {
				--mt-product-description-lines: ' . Helper::get_option('catalog_product_description_lines') . ';
			}';
		}

		return $static_css;
	}

	/**
	 * Get CSS code of settings for product card.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function product_card_static_css() {
		$static_css = '';

		// Product Title.
		if ( Helper::get_option( 'product_card_title_lines' ) != '' ) {
			if ( Helper::get_option( 'product_card_title_lines' ) == '2' ) {
				$min_height = '2.25rem';
			}
			if ( Helper::get_option( 'product_card_title_lines' ) == '3' ) {
				$min_height = '3.375rem';
			}
			if ( Helper::get_option( 'product_card_title_lines' ) == '4' ) {
				$min_height = '4.5rem';
			}
			$static_css .= 'ul.products li.product .woocommerce-loop-product__title {height: ' . $min_height . ';overflow: hidden}';
		}

		// Product Badges.
		if ( ( $color = Helper::get_option( 'badges_sale_bg' ) ) ) {
			$static_css .= '.woocommerce-badges .onsale {background-color: ' . $color . '}';
		}

		if ( ( $color = Helper::get_option( 'badges_sale_text_color' ) ) ) {
			$static_css .= '.woocommerce-badges .onsale {color: ' . $color . '}';
		}

		if ( ( $color = Helper::get_option( 'badges_new_bg' ) ) ) {
			$static_css .= '.woocommerce-badges .new {background-color: ' . $color . '}';
		}

		if ( ( $color = Helper::get_option( 'badges_new_text_color' ) ) ) {
			$static_css .= '.woocommerce-badges .new {color: ' . $color . '}';
		}

		if ( ( $color = Helper::get_option( 'badges_featured_bg' ) ) ) {
			$static_css .= '.woocommerce-badges .featured {background-color: ' . $color . '}';
		}

		if ( ( $color = Helper::get_option( 'badges_featured_text_color' ) ) ) {
			$static_css .= '.woocommerce-badges .featured {color: ' . $color . '}';
		}

		if ( ( $color = Helper::get_option( 'badges_soldout_bg' ) ) ) {
			$static_css .= '.woocommerce-badges .sold-out {background-color: ' . $color . '}';
		}

		if ( ( $color = Helper::get_option( 'badges_soldout_text_color' ) ) ) {
			$static_css .= '.woocommerce-badges .sold-out {color: ' . $color . '}';
		}

		$custom_badge_css = '';
		if ( ( $color = Helper::get_option( 'badges_custom_bg' ) ) ) {
			$custom_badge_css = '--id--badge-custom-bg: ' . $color  . ';';
		}

		if ( ( $color = Helper::get_option( 'badges_custom_color' ) ) ) {
			$custom_badge_css .= '--id--badge-custom-color: ' . $color . ';';
		}

		if( ! empty( $custom_badge_css ) ) {
			$static_css .= '.woocommerce-badges .custom {' . $custom_badge_css . '}';
		}

		return $static_css;
	}

	/**
	 * Get CSS code of settings for single product.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function single_product_static_css() {
		$static_css = '';

		if( \Motta\Helper::get_option('product_sale_image') ) {
			$static_css .= '.motta-single-product-sale { background-image: url(' . esc_url( \Motta\Helper::get_option('product_sale_image') ) . '); }';
		}

		if( \Motta\Helper::get_option('product_sale_bgcolor') ) {
			$static_css .= '.motta-single-product-sale { background-color: ' . \Motta\Helper::get_option('product_sale_bgcolor') . '; }';
		}

		if( \Motta\Helper::get_option('product_sale_textcolor') ) {
			$static_css .= '.motta-single-product-sale { color: ' . \Motta\Helper::get_option('product_sale_textcolor') . '; }';
		}

		if( Helper::get_option('product_description') ) {
			$static_css .= '.single-product div.product {
				--mt-product-description-lines: ' . Helper::get_option('product_description_lines') . ';
			}';
		}

		return $static_css;
	}
}
