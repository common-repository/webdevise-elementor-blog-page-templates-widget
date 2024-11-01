<?php
namespace ElementorBlogPageWidget;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Register custom category for widgets
	 * @access public
	 */
	public function widget_categories( $el_manager ) {
		$el_manager->add_category(
			'web_devise_elements',
			[
				'title' => __( 'WEB Devise Elements', 'web-devise-ebptw' ),
			]
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @access public
	 */
	public function register_widgets() {
		foreach ( glob( WDBLOG_DEVISE_ELEMENTOR_DIR . '/widgets/*/widget.php' ) as $filename ) {
			if ( empty( $filename ) || ! is_readable( $filename ) ) {
				continue;
			}
			require $filename;
		}
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register categories
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_categories' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

	}
}

// Instantiate Plugin Class
Plugin::instance();
