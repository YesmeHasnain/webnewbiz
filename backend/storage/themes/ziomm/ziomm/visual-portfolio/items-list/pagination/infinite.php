<?php
/**
 * Default pagination template.
 *
 * @var $args
 * @package visual-portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="<?php echo esc_attr( $args['class'] ); ?> vp-pagination__style-ziomm" data-vp-pagination-type="<?php echo esc_attr( $args['type'] ); ?>">
	<div class="vp-pagination__item">
		<a class="vp-pagination__load-more vlt-btn vlt-btn--secondary vlt-btn--effect vlt-btn--icon-right" href="<?php echo esc_url( $args['next_page_url'] ); ?>">
			<span class="vp-pagination__load-more-load"><?php echo esc_html( $args['text_load'] ); ?><i class="icon-reload"></i></span>
			<span class="vp-pagination__load-more-loading"><?php echo esc_html( $args['text_loading'] ); ?><i class="icon-reload right spin"></i></span>
			<span class="vp-pagination__load-more-no-more"><?php echo esc_html( $args['text_end_list'] ); ?></span>
		</a>
	</div>
</div>