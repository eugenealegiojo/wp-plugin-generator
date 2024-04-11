<?php

namespace TenPlugin;

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 */
	private const MINIMUM_PHP_VERSION = '7.4';

	/**
	 * Class instance
	 *
	 * @since 1.0.0
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		if ( $this->is_compatible() ) {
			$this->define_constants();

            register_activation_hook( TENPLUGIN_FILE, [ $this, 'activate' ] );
		    register_deactivation_hook( TENPLUGIN_FILE, [ $this, 'deactivate' ] );
	
			add_action( 'plugins_loaded', [ $this, 'load' ] );
		}
	}

    /**
     * Define plugin constants.
     *
     * @since 1.0.0
     */
	public function define_constants() {
		define( 'TENPLUGIN_FILE', trailingslashit( dirname( __DIR__, 1 ) ) . 'ten-plugin.php' );
		define( 'TENPLUGIN_DIR', trailingslashit( plugin_dir_path( TENPLUGIN_FILE ) ) );
		define( 'TENPLUGIN_URL', trailingslashit( plugin_dir_url( __DIR__ ) ) );
		define( 'TENPLUGIN_ASSETS_URL', TENPLUGIN_URL . 'assets' );
		define( 'TENPLUGIN_BUILD_DIR', TENPLUGIN_DIR . 'build' );
		define( 'TENPLUGIN_BUILD_URL', TENPLUGIN_URL . 'build' );
		define( 'TENPLUGIN_VERSION', '1.0.0' );
	}

    /**
     * Load the plugin core files/classes/hooks.
     */
    public fuction load(){

    }

	/**
	 * Compatibility checks.
	 *
	 * @since 1.0.0
	 */
	public function is_compatible() {

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}

		return true;
	}

	/**
	 * Admin notice on a minimum required PHP version.
	 *
	 * @since 1.0.0
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ten-plugin' ),
			'<strong>' . esc_html__( 'Ten Plugin', 'ten-plugin' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ten-plugin' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

    public function activate(){
        // Do something during activation.
    }

    public function deactivate(){
        // Do something during deactivation.
    }
}
