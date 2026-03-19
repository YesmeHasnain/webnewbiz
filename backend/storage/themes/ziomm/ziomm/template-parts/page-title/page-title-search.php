<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$column_content_class = is_active_sidebar( 'blog_sidebar' ) ? 'col-lg-7' : 'col-lg-7 offset-lg-2';

?>

<div class="vlt-page-title vlt-page-title--style-1">

	<div class="container">

		<div class="row">

			<div class="<?php echo ziomm_sanitize_class( $column_content_class ); ?>">

				<h1 class="vlt-page-title__title"><?php esc_html_e( 'Search Results', 'ziomm' ); ?></h1>

				<?php echo ziomm_get_breadcrumbs(); ?>

			</div>

		</div>

	</div>

</div>
<!-- /.vlt-page-title -->