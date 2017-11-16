<?php
/**
 * Plugin Name: Downloads
 * Description: Manager for download files.
 * Version: 1.0.0
 *
 * Text Domain: downloads
 *
 * @package  Downloads
 * @category Core
 * @author   EssFaiv
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Downloads' ) ) :

	final class Downloads {

		/**
		 * The single instance of the class.
		 *
		 * @var Downloads
		 */
		protected static $_instance = null;

		/**
		 * Main Downloads Instance.
		 *
		 * Ensures only one instance of Downloads is loaded or can be loaded.
		 *
		 * @static
		 * @see Downloads()
		 * @return Downloads - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Setup class.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'after_setup_theme',      array( $this, 'include_template_functions' ), 11 );
			add_action( 'init',                   array( __CLASS__, 'register_post_type' ), 5 );
			add_filter( 'post_password_required', array( $this, 'login_required' ), 10, 2 );
			add_filter( 'the_password_form',      array( $this, 'form_login' ), 10, 2 );
		}

		/**
		 * Define Downloads Constants.
		 */
		private function define_constants() {
			$this->define( 'DOWNLOADS_ABSPATH', dirname( __FILE__ ) . '/' );
			$this->define( 'DOWNLOADS_TEMPLATE_DEBUG_MODE', false );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or frontend.
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Function used to Init Downloads Template Functions - This makes them pluggable by plugins and themes.
		 */
		public function include_template_functions() {
			include_once( DOWNLOADS_ABSPATH . 'includes/downloads-template-functions.php' );
		}

		/**
		 * Include functions.
		 */
		public function includes() {
			include_once( DOWNLOADS_ABSPATH . 'includes/downloads-functions.php' );

			if ( $this->is_request( 'frontend' ) ) {
				$this->frontend_includes();
			}
		}

		/**
		 * Include required frontend files.
		 */
		public function frontend_includes() {
			include_once( DOWNLOADS_ABSPATH . 'includes/downloads-template-hooks.php' );
			include_once( DOWNLOADS_ABSPATH . 'includes/class-downloads-template-loader.php' ); // Template Loader
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'downloads_template_path', 'downloads/' );
		}

	//		public static function admin_menu() {
	//			add_menu_page( __( 'Área Restrita', 'ceb' ), __( 'Área Restrita', 'ceb' ), 'edit_posts', 'edit.php?post_type=download', '', 'dashicons-admin-network', 5 );
	//			add_submenu_page( 'edit.php?post_type=download', __( 'Arquivos', 'ceb' ), __( 'Arquivos', 'ceb' ), 'edit_posts', 'edit.php?post_type=download' );
	//		}

		/**
		 * Register core post type.
		 */
		public static function register_post_type() {
			register_post_type( 'download',
				apply_filters( 'restricted_register_post_type_download',
					array(
						'description'         => 'The latest downloads',
						'has_archive'         => ( $files_page_id = downloads_get_page_id( 'files' ) ) && get_post( $files_page_id ) ? urldecode( get_page_uri( $files_page_id ) ) : 'files',
						'rewrite'             => array( 'slug' => 'download' ),
						'capability_type'     => 'post',
						'supports'            => array( 'title' ),
						'public'              => true,
						//'show_ui'             => false,
						'show_in_menu'        => false,
						'exclude_from_search' => true,
						'labels' => array(
							'name'               => __( 'Arquivos', 'ceb'),
							//'add_new'            => 'Add New',
							'add_new_item'       => __( 'Adicionar Novo Arquivo', 'ceb' ),
							'edit'               => 'Edit',
							'edit_item'          => 'Edit Download',
							'new_item'           => 'New Download',
							'view'               => 'View Download',
							'view_item'          => 'View Download',
							'search_items'       => 'Search Downloads',
							'not_found'          => __( 'Nenhum download encontrado', 'ceb' ),
							'not_found_in_trash' => 'No downloads found in Trash',
						),
					)
				)
			);
		}

		public function login_required( $required, $post ) {
			$is_members = 'private' === $post->post_status && get_post_meta( $post->ID, '_members', true );
			if ( ! is_user_logged_in() && $is_members ) {
				$required = true;
			}
			return $required;
		}

		public function form_login( $output ) {
			global $post;
			if ( 'download' === $post->post_type ) {
				$files_page_id = downloads_get_page_id( 'files' );
				$post = get_post( $files_page_id );
				$is_members = 'private' === $post->post_status && get_post_meta( $post->ID, '_members', true );
				if ( ! is_user_logged_in() && $is_members ) {
					$args = array(
						'echo'     => false,
						'redirect' => get_permalink( $post ),
					);
					$output = members_login_form( $args );
				}
			}
			return $output;
		}
	}
endif;

/**
 * Main instance of Downloads.
 *
 * Returns the main instance of Downloads to prevent the need to use globals.
 *
 * @return Downloads
 */
function Downloads() {
	return Downloads::instance();
}

Downloads();
