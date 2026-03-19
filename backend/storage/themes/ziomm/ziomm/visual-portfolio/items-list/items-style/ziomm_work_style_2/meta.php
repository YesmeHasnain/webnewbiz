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

$templates_data = array(
	'args' => $args,
	'opts' => $opts,
	'allow_links' => true,
);

$show_meta = $opts['show_title'] && $args['title'];

if ( $show_meta ) : ?>

	<div class="vp-portfolio__item-meta">

		<?php

			// Title.
			visual_portfolio()->include_template( 'items-list/item-parts/title', $templates_data );

			// Read More.
			visual_portfolio()->include_template( 'items-list/item-parts/read-more', $templates_data );

		?>

	</div>

<?php endif; ?>