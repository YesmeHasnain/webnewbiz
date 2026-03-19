<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */
if (!defined('ABSPATH')){
    exit(); //exit if access directly
}

if (!class_exists('Glint_Tags')){
    class Glint_Tags{
		
		
		private static $instance;

	    /**
	     * get instance
	     * @since 1.0.0
	     * */
	    public static function getInstance(){
		    if (null ==  self::$instance){
			    self::$instance = new self();
		    }
		    return self::$instance;
	    }

		/*-------------------------------
		    DAY LINK TO ARCHIVE PAGE
		---------------------------------*/
	    static function glint_day_link() {
	        $archive_year   = get_the_time('Y');
	        $archive_month  = get_the_time('m');
	        $archive_day    = get_the_time('d');
	        return get_day_link( $archive_year, $archive_month, $archive_day);
	    }

	    /**
	     * Prints HTML with meta information for the current post-date/time.
	     */
	    public static function posted_on() {
		    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		    }

		    $time_string = sprintf( $time_string,
			    esc_attr( get_the_date( DATE_W3C ) ),
			    esc_html( get_the_date() ),
			    esc_attr( get_the_modified_date( DATE_W3C ) ),
			    esc_html( get_the_modified_date() )
		    );


		    $posted_on = sprintf(
		    /* translators: %s: post date. */
			    //esc_html_x( 'Posted on %s', 'post date', 'glint' ),
			    '<a href="' . esc_url( self::glint_day_link() ) . '" rel="bookmark">' . $time_string . '</a>'
		    );

		    echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	    }

	    public static function posted_by() {
		    $byline = sprintf(
		    /* translators: %s: post author. */
			    esc_html_x( '%s', 'post author', 'glint' ),'<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
		    );

		    echo '<span class="author vcard"> ' . $byline . '</span>'; // WPCS: XSS OK.

	    }

	    /**
	     * Displays an optional post thumbnail.
	     *
	     * Wraps the post thumbnail in an anchor element on index views, or a div
	     * element when on single views.
	     */
	    public static function post_thumbnail() {
		    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			    return;
		    }

		    if ( is_singular() ) :
			    ?>

                <div class="post-thumbnail">
				    <?php the_post_thumbnail(); ?>
                </div><!-- .post-thumbnail -->

		    <?php else : ?>

                <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				    <?php
				    the_post_thumbnail( 'post-thumbnail', array(
					    'alt' => the_title_attribute( array(
						    'echo' => false,
					    ) ),
				    ) );
				    ?>
                </a>

		    <?php
		    endif; // End is_singular().
	    }
		
		 public static function tags() {
	     ?>
		    <?php if ( has_tag() ): ?>
                <div class="tag-list-wrapper">
                    <ul class="navs navs-tag">
	                    <li class="navs__item">
	                        <h4 class="navs__item-tag"><?php esc_html_e( 'tag', 'glint' ); ?></h4>
	                    </li>
	                    <?php
	                     if ( get_the_tag_list()) {
	                            echo get_the_tag_list('<li>',
	                                ' </li><li>','</li>');
	                         }
	                   ?>
	                </ul>
                </div>
           <?php endif; ?>

	  <?php  }

    }//end class


}
