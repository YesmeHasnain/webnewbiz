<?php
/**
 * Item meta template.
 *
 * @var $args
 * @var $opts
 * @package visual-portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	// Show Title.
	if ( $opts[ 'show_title' ] && $args[ 'title' ] ) {
		echo '<div data-follow-info-title>' . esc_html( $args[ 'title' ] ) . '</div>';
	}

	$categories = '';

	// Show Categories.
	if ( $opts['show_categories'] && $args['categories'] && ! empty( $args['categories'] ) ) {

		echo '<div data-follow-info-content>';

			// phpcs:ignore
			$count = $opts[ 'categories_count' ];

			// phpcs:ignore
			foreach ( $args[ 'categories' ] as $category ) {

				if ( ! $count ) {
					break;
				}
				$categories .= esc_html( $category[ 'label' ] ) . ', ';

				$count--;
			}

			echo rtrim( $categories, ', ' );

		echo '</div>';

	}

	?>

</div>