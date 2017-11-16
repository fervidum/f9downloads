<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/download/content-product.php.
 *
 * HOWEVER, on occasion Download will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author  EssFaiv
 * @package Downloads/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<li <?php post_class(); ?>>
	<?php
	/**
	 * Hook downloads_before_loop_item.
	 */
	do_action( 'downloads_before_loop_item' );

	/**
	 * Hook downloads_before_loop_item_title.
	 */
	do_action( 'downloads_before_loop_item_title' );

	/**
	 * Hook downloads_loop_item_title.
	 *
	 * @hooked downloads_template_loop_title - 10
	 */
	do_action( 'downloads_loop_item_title' );

	/**
	 * Hook downloads_after_loop_item_title.
	 */
	do_action( 'downloads_after_loop_item_title' );

	/**
	 * Hook downloads_before_loop_item_type.
	 */
	do_action( 'downloads_before_loop_item_type' );

	/**
	 * Hook downloads_loop_item_type.
	 *
	 * @hooked downloads_template_loop_type - 10
	 */
	do_action( 'downloads_loop_item_type' );

	/**
	 * Hook downloads_after_loop_item_type.
	 */
	do_action( 'downloads_after_loop_item_type' );

	/**
	 * Hook downloads_before_loop_item_size.
	 */
	do_action( 'downloads_before_loop_item_size' );

	/**
	 * Hook downloads_loop_item_size.
	 *
	 * @hooked downloads_template_loop_size - 10
	 */
	do_action( 'downloads_loop_item_size' );

	/**
	 * Hook downloads_after_loop_item_size.
	 */
	do_action( 'downloads_after_loop_item_size' );

	/**
	 * Hook downloads_before_loop_item_action.
	 */
	do_action( 'downloads_before_loop_item_action' );

	/**
	 * Hook downloads_loop_item_action.
	 *
	 * @hooked downloads_template_loop_action - 10
	 */
	do_action( 'downloads_loop_item_action' );

	/**
	 * Hook downloads_after_loop_item_action.
	 */
	do_action( 'downloads_after_loop_item_action' );

	/**
	 * Hook downloads_after_loop_item.
	 */
	do_action( 'downloads_after_loop_item' );
	?>
</li>
