<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$column_content_class = is_active_sidebar( 'shop_sidebar' ) ? 'col-lg-7' : 'col-lg-7 offset-lg-2';

$args = [
	'delimiter' => '<span class="sep">/</span>',
	'wrap_before' => '<nav class="vlt-breadcrumbs">',
	'wrap_after' => '</nav>',
];

?>

<div class="vlt-page-title vlt-page-title--style-1">

	<div class="container">

		<div class="row">

			<div class="<?php echo ziomm_sanitize_class( $column_content_class ); ?>">

				<h1 class="vlt-page-title__title"><?php woocommerce_page_title(); ?></h1>

				<?php woocommerce_breadcrumb( $args ); ?>

			</div>

		</div>

	</div>

</div>
<!-- /.vlt-page-title -->