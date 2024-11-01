<?php
/**
 * Plugin Name: WebDevise Elementor Blog widgets
 * Description: Elementor Blog posts templates widget.
 * Plugin URI:  https://web-devise.com/
 * Version:     1.0.4
 * Author:      WEB Devise
 * Text Domain: web-devise-ebptw
 * Requires at least: 4.8
 * Tested up to: 5.8.1
 * Requires PHP: 5.6
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( plugin_dir_path( __FILE__ ) . 'inc/elementor/elementor.php' );

/**
 * Registering image sizes
 *
 */
if ( ! function_exists( 'devise_setup' ) ) {
	function devise_setup() {
		add_image_size( 'blog-grid', 400, 200, [ 'center', 'center' ] );
		add_image_size( 'blog-list', 300, 200, true );
		add_image_size( 'blog-list-mobile', 500, 400 );
	}
}
add_action( 'after_setup_theme', 'devise_setup' );

/**
 * Loading Bootstrap 5 if not loaded previously
 *
 */
if ( ! function_exists( 'devise_load_bootstrap' ) ) {
	function devise_load_bootstrap() {
		$style = 'bootstrap';
		if ( ( ! wp_style_is( $style, 'queue' ) ) && ( ! wp_style_is( $style, 'done' ) ) ) {
			wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'inc/elementor/assets/css/bootstrap.min.css' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'devise_load_bootstrap' );

/**
 * Custom thumbnail size for mobile
 *
 */
if ( ! function_exists( 'devise_image_sources' ) ) {
	function devise_image_sources( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {

		$image_size_name = 'blog-list-mobile';
		$breakpoint = 500;

		$upload_dir = wp_upload_dir();

		$img_url = $upload_dir['baseurl'] . '/' . str_replace( basename( $image_meta['file'] ), $image_meta['sizes'][$image_size_name]['file'], $image_meta['file'] );

		$sources[ $breakpoint ] = array(
			'url'        => $img_url,
			'descriptor' => 'w',
			'value'      => $breakpoint,
		);
		return $sources;
	}
}
add_filter( 'wp_calculate_image_srcset', 'devise_image_sources', 10, 5 );

/**
 * Load Textdomain
 *
 * Load plugin localization files.
 */
if ( ! function_exists( 'devise_ebptw_i18n' ) ) {
	function devise_ebptw_i18n() {
		load_plugin_textdomain( 'web-devise-ebptw', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}
add_action( 'init', 'devise_ebptw_i18n' );
