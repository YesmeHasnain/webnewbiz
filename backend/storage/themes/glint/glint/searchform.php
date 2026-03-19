<?php
/**
 * Template for displaying search forms in Glint
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */
?>

<form role="search" method="get" class="search-form header-search" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html( 'Search for:', 'glint' )?></span>
		<input type="search" class="glint-header-input" placeholder="<?php echo esc_attr( 'Search', 'glint' ) ?>" value="<?php echo get_search_query() ?>" name="s" />
	</label>
	<button type="submit" class="submit-btn"><i class="fa fa-search"></i></button>
</form>