<?php
/**
 * Item image template.
 *
 * @var $args
 * @var $opts
 *
 * @package @@plugin_name
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$link_data = array(
	'href' => $args['url'],
	'target' => $args['url_target'],
	'rel' => $args['url_rel'],
);

$badge = $opts['show_date'] ||
	$opts['show_categories'] && $args['categories'] && ! empty( $args['categories'] );

$allow_links = isset( $allow_links ) ? $allow_links : false;
$count = $opts['categories_count'];

?>

	<div class="vp-portfolio__item-img-wrap">

		<div class="vp-portfolio__item-img">

			<?php if ( $badge ) : ?>

				<div class="vlt-badge">

					<?php

						if ( $opts['show_categories'] && $args['categories'] && ! empty( $args['categories'] ) ) {

							echo '<span class="vp-portfolio__item-meta-categories">';

							foreach ( $args['categories'] as $category ) {

								if ( ! $count ) {
									break;
								}

								$link_data = array(
									'href' => $allow_links ? $category['url'] : false,
									'fallback' => 'span',
								);

								?>
								<span class="vp-portfolio__item-meta-category">
									<?php
									visual_portfolio()->include_template( 'global/link-start', $link_data );
									echo esc_html( $category['label'] );
									visual_portfolio()->include_template( 'global/link-end', $link_data );
									?>
								</span>
								<?php
								$count--;
							}

							echo '</span>';

						}

						if ( $opts['show_date'] ) {
							echo '<span class="vp-portfolio__item-meta-date">' . esc_html( $args['published'] ) . '</span>';
						}

					?>

				</div>

			<?php endif; ?>

			<?php visual_portfolio()->include_template( 'global/link-start', $link_data ); ?>

			<?php

				// Show Image.
				visual_portfolio()->include_template(
					'items-list/item-parts/image',
					array( 'image' => $args['image'] )
				);

			?>

			<?php visual_portfolio()->include_template( 'global/link-end', $link_data ); ?>

		</div>

	</div>
