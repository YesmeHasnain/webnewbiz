<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$size = 'ziomm-1920x960';

?>

<div class="vlt-post-media-title vlt-post-media-title--style-2 jarallax">

	<?php the_post_thumbnail( $size, array( 'class' => 'jarallax-img', 'loading' => 'lazy' ) ); ?>

	<div class="vlt-post-media-title__overlay"></div>

	<div class="container">

		<div class="row">

			<div class="col-lg-8 offset-lg-2">

				<div class="lax" data-lax-translate-y="0 0, (-elh*2) elh" data-lax-opacity="0 1, (-elh*2) 0" data-lax-anchor=".vlt-post-media-title">

					<?php get_template_part( 'template-parts/single-post/partials/partial-post', 'meta' ); ?>

					<?php get_template_part( 'template-parts/single-post/partials/partial-post', 'title' ); ?>

				</div>

			</div>

		</div>

	</div>

</div>
<!-- /.vlt-post-media-title -->