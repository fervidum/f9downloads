<?php
/**
 * Downloads Template
 *
 * Functions for the templating system.
 *
 * @author   EssFaiv
 * @category Core
 * @package  Downloads/Functions
 * @version  1.0.0
 */

/** Loop ******************************************************************/

if ( ! function_exists( 'downloads_page_title' ) ) {

	/**
	 * Function downloads_page_title.
	 *
	 * @param  bool $echo If must be print (Default: true).
	 * @return string
	 */
	function downloads_page_title( $echo = true ) {

		$files_page_id = downloads_get_page_id( 'files' );
		$page_title    = get_the_title( $files_page_id );

		$page_title = apply_filters( 'downloads_page_title', $page_title );

		if ( $echo ) {
			echo esc_html( $page_title );
		} else {
			return $page_title;
		}
	}
}

if ( ! function_exists( 'downloads_loop_start' ) ) {

	/**
	 * Output the start of a product loop. By default this is a UL.
	 *
	 * @param bool $echo
	 * @return string
	 */
	function downloads_loop_start( $echo = true ) {
		ob_start();
		$GLOBALS['downloads_loop']['loop'] = 0;
		downloads_get_template( 'loop/loop-start.php' );
		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

if ( ! function_exists( 'downloads_loop_end' ) ) {

	/**
	 * Output the end of a product loop. By default this is a UL.
	 *
	 * @param bool $echo
	 * @return string
	 */
	function downloads_loop_end( $echo = true ) {
		ob_start();

		downloads_get_template( 'loop/loop-end.php' );

		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

if ( ! function_exists( 'downloads_template_loop_title' ) ) {

	/**
	 * Show the product title in the file loop. By default this is an H2.
	 */
	function downloads_template_loop_title() {
		echo '<h2 class="downloads-loop-file__title">' . get_the_title() . '</h2>';
	}
}

/**
 * Insert the opening anchor tag for downloads in the loop.
 */
function downloads_template_loop_link_open() {
	echo '<a href="' . get_the_permalink() . '" class="downloads-LoopFile-link downloads-loop-file__link">';
}

/**
 * Insert the opening anchor tag for products in the loop.
 */
function downloads_template_loop_link_close() {
	echo '</a>';
}
