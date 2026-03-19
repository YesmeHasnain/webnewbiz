<?php
$unique_id = uniqid( 'xcency-search-form-' );
$search_placeholder = xcency_option('search_placeholder');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr($unique_id); ?>">
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'xcency' ); ?></span>
		<input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr($search_placeholder);?>" value="<?php echo get_search_query(); ?>" required name="s" />
	</label>
	<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>

</form>