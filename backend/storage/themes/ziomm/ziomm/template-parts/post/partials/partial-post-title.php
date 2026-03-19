<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<h3 class="vlt-post-title">

	<?php if ( is_sticky() ) { echo '<i class="icon-star"></i>'; } ?>

	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

</h3>
<!-- /.vlt-post-title -->