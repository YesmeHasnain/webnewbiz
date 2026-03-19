<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<div class="vlt-isotope-grid" data-columns="2" data-layout="masonry" data-x-gap="30|30" data-y-gap="30|30">

	<div class="grid-sizer"></div>

	<?php

		while ( have_posts() ) : the_post();

			echo '<div class="grid-item">';

			get_template_part( 'template-parts/post/post', 'style-3' );

			echo '</div>';

		endwhile;

	?>

</div>
<!-- /.vlt-isotope-grid -->