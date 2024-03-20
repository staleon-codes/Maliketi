<?php
/**
 * Posts functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Header;

use Motta\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts initial
 *
 */
class Topbar {
	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Custom template tags of header
	 *
	 * @package Motta
	 *
	 * @since 1.0.0
	 *
	 * @param $items
	 */
	public static function items( $items ) {
		if ( empty( $items ) ) {
			return;
		}

		$args = [];

		foreach ( $items as $item ) {
			$item['item'] = $item['item'] ? $item['item'] : key( \Motta\Options::topbar_items_option() );

			switch ( $item['item'] ) {
				case 'primary-menu':
						wp_nav_menu( array(
							'theme_location' 	=> '__no_such_location',
							'menu'           	=> Helper::get_option('topbar_primary_menu'),
							'container'      	=> 'nav',
							'container_id'   	=> 'topbar-primary-menu',
							'container_class'   => 'topbar-navigation topbar-primary-menu',
							'menu_class'     	=> 'nav-menu menu',
							'depth'          	=> 3,
						) );
					break;

				case 'secondary-menu':
						wp_nav_menu( array(
							'theme_location' 	=> '__no_such_location',
							'menu'           	=> Helper::get_option('topbar_secondary_menu'),
							'container'      	=> 'nav',
							'container_id'   	=> 'topbar-secondary-menu',
							'container_class'   => 'topbar-navigation topbar-secondary-menu',
							'menu_class'     	=> 'nav-menu menu',
							'depth'          	=> 3,
						) );
					break;

				case 'language':
					$args_languages = Helper::get_languages($args);
					if( $args_languages ) {
						\Motta\Theme::set_prop( 'modals', 'preferences' );
						get_template_part( 'template-parts/header/preferences', '', $args_languages );
					}
					break;

				case 'currency':
					$args_currencies = \Motta\WooCommerce\Currency::get_currencies($args);
					if( $args_currencies ) {
						\Motta\Theme::set_prop( 'modals', 'preferences' );
						$args_currencies['preferences_classes'] = 'motta-button--text motta-button--currency';
						get_template_part( 'template-parts/header/preferences', '', $args_currencies );
					}
					break;

				case 'language-currency':
					$args['icon'] = 'flag';
					$args = Helper::get_languages($args, 'flag');
					$args = \Motta\WooCommerce\Currency::get_currencies($args, 'flag');

					if( $args ) {
						\Motta\Theme::set_prop( 'modals', 'preferences' );
						get_template_part( 'template-parts/header/preferences', '', $args );
					}
					break;

				case 'socials':
					get_template_part( 'template-parts/header/socials', '', $args );
					break;

				case 'hamburger':
					\Motta\Theme::set_prop( 'panels', $item['item'] );
					$args['data_target'] = $item['item'] . '-panel';
					get_template_part( 'template-parts/header/hamburger', '', $args );
					break;

				default:
					do_action( 'motta_header_topbar_item', $item['item'] );
					break;
			}
		}
	}
}
