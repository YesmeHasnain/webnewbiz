<div class="sticky_form rts-search-popup">
	<div class="sticky_form_full">
	<form role="search" class="bs-search search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-wrap">
    	<label class="screen-reader-text">
    		<?php echo esc_html__( 'Search for:', 'startli' ); ?>
    	</label>
        <input type="search" placeholder="<?php esc_attr_e( 'Searching...', 'startli' ); ?>" name="s" class="search-input" value="<?php echo esc_attr( get_search_query() ); ?>" />
        <button type="submit"  value="Search"><i class="rt-search"></i></button>
    </div>
</form>
	</div><i class=" rt-xmark close-search sticky_search sticky_form_search"></i>
</div>
