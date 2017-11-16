<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/downloads/archive-product.php.
 *
 * HOWEVER, on occasion Downloads will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author      EssFaiv
 * @package     Downloads/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header( 'downloads' ); ?>

	<?php
		/**
		 * Hook downloads_before_main_content.
		 */
		do_action( 'downloads_before_main_content' );
	?>

	<header class="downloads-header">

		<?php if ( apply_filters( 'downloads_show_page_title', true ) ) : ?>

			<h1 class="downloads-header__title page-title"><?php downloads_page_title(); ?></h1>

		<?php endif; ?>

		<?php
			/**
			 * Hook downloads_archive_description.
			 */
			do_action( 'downloads_archive_description' );
		?>

	</header>

		<?php
			/**
			 * Hook downloads_before_downloads_have_posts.
			 */

			$files_page_id = downloads_get_page_id( 'files' );
			if ( post_password_required( $files_page_id ) ) :
				echo get_the_password_form();
			else :

			do_action( 'downloads_before_downloads_have_posts' );
		?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * Hook downloads_before_loop.
				 */
				do_action( 'downloads_before_downloads_loop' );
			?>

			<?php downloads_loop_start(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/**
						 * Hook downloads_shop_loop.
						 */
						do_action( 'downloads_loop' );
					?>

					<?php downloads_get_template_part( 'content', 'download' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php downloads_loop_end(); ?>

			<?php
				/**
				 * Hook downloads_after_shop_loop.
				 */
				do_action( 'downloads_after_loop' );
			?>

		<?php elseif ( ! downloads_subcategories( array( 'before' => downloads_product_loop_start( false ), 'after' => downloads_product_loop_end( false ) ) ) ) : ?>

			<?php
				/**
				 * Hook downloads_not_found.
				 */
				do_action( 'downloads_not_found' );
			?>

		<?php endif; ?>

		<?php
			/**
			 * Hook downloads_after_downloads_have_posts.
			 */
			do_action( 'downloads_after_downloads_have_posts' );

			endif;
		?>

	<?php
		/**
		 * Hook downloads_after_main_content.
		 */
		do_action( 'downloads_after_main_content' );
	?>

	<?php
		/**
		 * Hook downloads_sidebar.
		 */
		do_action( 'downloads_sidebar' );
	?>

<?php get_footer( 'downloads' ); ?>
