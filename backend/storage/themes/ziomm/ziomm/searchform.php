<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<form class="vlt-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">

	<input type="text" name="s" placeholder="<?php esc_attr_e( 'Search...', 'ziomm' ); ?>" value="<?php echo get_search_query(); ?>">

	<button><i class="icon-search"></i></button>

</form>
<!-- /.vlt-search-form -->