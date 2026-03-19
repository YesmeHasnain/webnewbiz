<?php
/**
 * Search Form template
 *
 * @package EARLS
 * @author Template Path
 * @version 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Restricted' );
}
?>
<div class="single-sidebar-box">
	<div class="sidebar-search-box">
        <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post" class="search-form">
            <input type="text" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php echo esc_attr__( 'Enter Search Keywords', 'earls' ); ?>" >
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>
        </form>
	</div>
</div>