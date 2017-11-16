<?php
/**
 * Downloads Functions
 *
 * Functions available on both the front-end and admin.
 *
 * @author      EssFaiv
 * @package     Downloads/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get template part (for templates like the files-loop).
 *
 * DOWNLOADS_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @access public
 * @param mixed $slug Slug.
 * @param string $name (default: '').
 */
function downloads_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/downloads/slug-name.php
	if ( $name && ! DOWNLOADS_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}-{$name}.php", Downloads()->template_path() . "{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( Downloads()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
		$template = Downloads()->plugin_path() . "/templates/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/downloads/slug.php
	if ( ! $template && ! DOWNLOADS_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}.php", Downloads()->template_path() . "{$slug}.php" ) );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'downloads_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get other templates (e.g. login form) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name Template name.
 * @param array  $args (default: array()).
 * @param string $template_path (default: '').
 * @param string $default_path (default: '').
 */
function downloads_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = downloads_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		downloads_doing_it_wrong( __FUNCTION__, sprintf( __( '%s não existe.', 'downloads' ), '<code>' . $located . '</code>' ), '2.1' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'downloads_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'downloads_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'downloads_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *      yourtheme       /   $template_path  /    $template_name
 *      yourtheme       /   $template_path
 *      $default_path   /   $template_path
 *
 * @access public
 * @param string $template_name Template name.
 * @param string $template_path (default: '').
 * @param string $default_path (default: '').
 * @return string
 */
function downloads_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = F9members()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = Downloads()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template || DOWNLOADS_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'downloads_locate_template', $template, $template_name, $template_path );
}


/**
 * Wrapper for members_doing_it_wrong.
 *
 * @param string $function Function.
 * @param string $message Mensagem.
 * @param string $version Version.
 */
function downloads_doing_it_wrong( $function, $message, $version ) {
	$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

	if ( is_ajax() ) {
		do_action( 'doing_it_wrong_run', $function, $message, $version );
		error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
	} else {
		$allowed_html = array(
			'b' => array(),
		);
		_doing_it_wrong( esc_html( $function ), wp_kses( $message, $allowed_html ), esc_html( $version ) );
	}
}

/**
 * Retrieve page id - used for downloads. returns -1 if no page is found.
 *
 * @param string $page Page slug.
 * @return int
 */
function downloads_get_page_id( $page ) {

	$page = apply_filters( 'downloads_get_' . $page . '_page_id', get_option( 'downloads_' . $page . '_page_id' ) );

	return $page ? absint( $page ) : -1;
}

/**
 * Retrieve the extension by mime type.
 *
 * @param string $filename File name ou path.
 * @return string Extension of mime type.
 */
function downloads_filetype( $filename ) {
	$check_file = wp_check_filetype( $filename );
	return $check_file['ext'];
}

/**
 * Retrieve file size
 *
 * @param string $attached_file File name ou path.
 * @return string File size.
 */
function downloads_filesize( $attached_file ) {
	return str_replace( 'KB', 'kB', str_replace( ' ', '', size_format( filesize( $attached_file ) ) ) );
}

function downloads_get_file_id() {
	global $post;
	return get_post_meta( $post->ID, 'file', true );
}

function downloads_get_file() {
	$file_id = downloads_get_file_id();
	return get_attached_file( $file_id );
}

function downloads_get_the_type() {
	return downloads_filetype( downloads_get_file() );
}

function downloads_get_the_size() {
	return downloads_filesize( downloads_get_file() );
}

function downloads_file_url() {
	$file = get_post( downloads_get_file_id() );
	return $file->guid;
}

function f9downloads_page_files_url() {
	$files_page_id = downloads_get_page_id( 'files' );
	$post = get_post( $files_page_id );
	return get_permalink( $post );
}
