<?php
/**
 * Help Center functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Help_Center;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Header initial
 *
 */
class Article {
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
		add_filter('motta_content_single_template_part', array( $this, 'content_template_part' ) );

		add_action('motta_after_post_content', array( $this, 'get_sidebar' ) );
	}

	/**
	 * Content Template part
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function content_template_part() {
		return 'help';
	}

	/**
	 * Get Sidebar
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_sidebar() {
		if( \Motta\Helper::get_option('help_center_single_hide_sidebar') ) {
			return;
		}
		$terms = get_the_terms( get_the_ID(), 'motta_help_cat' );
		if( is_wp_error( $terms ) || empty( $terms ) ) {
			return;
		}
		$number = intval(\Motta\Helper::get_option('help_center_single_sidebar_posts_number'));
		if( $number <= 1 ) {
			return;
		}
		$number = $number - 1;
		$args = array (
			'posts_per_page' => $number,
			'post_type'   => 'motta_help_article',
			'exclude' => [get_the_ID()],
			'tax_query' => array(
				array(
					'taxonomy' => 'motta_help_cat',
					'field' => 'term_id',
					'terms' => $terms[0]->term_id,
				)
			)
		);
		$posts = get_posts($args);

		if( empty( $posts ) ) {
			return;
		}
		$output = '<div class="motta-help-sidebar">';
		$output .= sprintf('<ul class="motta-help-sidebar__current hidden-md hidden-lg"><li>%s</li></ul>', get_the_title());
		$output .= '<ul class="motta-help-sidebar__list">';
		$output .= sprintf('<li class="current-post"><a href="%s">%s</a></li>', get_the_permalink(), get_the_title());
		foreach ( $posts as $post ) {
			$output .= sprintf('<li><a href="%s">%s</a></li>',  get_the_permalink( $post->ID ), get_the_title($post->ID));
		}
		$output .= '</ul></div>';

		echo !empty($output) ? $output : '';
	}

	/**
	 * Get entry title
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function get_single_title($tag = 'h2') {
		if( \Motta\Helper::get_option('help_center_single_hide_title') ) {
			return;
		}
		the_title( '<'.$tag.' class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></'.$tag.'>' );
	}

		/**
	 * Get Content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function get_loop_content() {
		$content = get_the_excerpt();
		$content = empty( $content ) ? get_the_content() : $content;
		$lenght = intval(\Motta\Helper::get_option('help_article_length'));
		$content = \Motta\Helper::get_content_limit( $lenght, '', $content );
		$content = apply_filters('motta_help_article_content', $content);
		if( ! empty( $content ) ) {
			echo sprintf('<div class="help-short-description">%s</div>', $content);
		}
	}

	/**
	 * Get Content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function get_loop_title() {
		echo apply_filters('motta_help_article_title', get_the_title());;
	}

}
