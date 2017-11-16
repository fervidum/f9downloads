<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Template Loader
 *
 * @class       Downloads_Template
 * @version     1.1.0
 * @package     Downloads/Classes
 * @category    Class
 * @author      EssFaiv
 */
class Downloads_Template_Loader {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'template_include',  array( __CLASS__, 'template_loader' ) );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. downloads looks for theme.
	 * overrides in /theme/downloads/ by default.
	 *
	 * For beginners, it also looks for a downloads.php template first. If the user adds.
	 * this to the theme (containing a downloads() inside) this will be used for all.
	 * downloads templates.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public static function template_loader( $template ) {
		if ( is_embed() ) {
			return $template;
		}

		if ( $default_file = self::get_template_loader_default_file() ) {
			/**
			 * Filter hook to choose which files to find before Downloads does it's own logic.
			 *
			 * @var array
			 */
			$search_files = self::get_template_loader_files( $default_file );
			$template     = locate_template( $search_files );

			if ( ! $template || DOWNLOADS_TEMPLATE_DEBUG_MODE ) {
				$template = Downloads()->plugin_path() . '/templates/' . $default_file;
			}
		}

		return $template;
	}

	/**
	 * Get the default filename for a template.
	 *
	 * @since  3.0.0
	 * @return string
	 */
	private static function get_template_loader_default_file() {
		if ( is_post_type_archive( 'download' ) || is_page( downloads_get_page_id( 'files' ) ) ) {
			$default_file = 'archive-download.php';
		} else {
			$default_file = '';
		}
		return $default_file;
	}

	/**
	 * Get an array of filenames to search for a given template.
	 *
	 * @since  3.0.0
	 * @param  string $default_file The default file name.
	 * @return string[]
	 */
	private static function get_template_loader_files( $default_file ) {
		$search_files   = apply_filters( 'downloads_template_loader_files', array(), $default_file );
		$search_files[] = 'downloads.php';

		$search_files[] = $default_file;
		$search_files[] = Downloads()->template_path() . $default_file;

		return array_unique( $search_files );
	}
}

Downloads_Template_Loader::init();
