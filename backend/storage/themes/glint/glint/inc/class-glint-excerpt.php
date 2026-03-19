<?php
/**
 * @source https://gist.github.com/bgallagh3r/8546465
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
*/

class Glint_Excerpt {

    // Default length (by WordPress)
    public static $length = 55;

    // So you can call: my_excerpt('short');
    public static $types = array(
		'short'   => 25,
		'regular' => 55,
		'long'    => 100,
		'promo'   =>15
    );

    public static $more = true;

    /**
    * Sets the length for the excerpt,
    * then it adds the WP filter
    * And automatically calls the_excerpt();
    *
    * @param string $new_length
    * @return void
    * @author Baylor Rae'
    */
    public static function length($new_length = 55, $more = true) {
	    Glint_Excerpt::$length = $new_length;
	    Glint_Excerpt::$more = $more;

        add_filter( 'excerpt_more', 'Glint_Excerpt::auto_excerpt_more' );

        add_filter('excerpt_length', 'Glint_Excerpt::new_length');

	    Glint_Excerpt::output();
    }

    // Tells WP the new length
    public static function new_length() {
        if( isset(Glint_Excerpt::$types[Glint_Excerpt::$length]) )
            return Glint_Excerpt::$types[Glint_Excerpt::$length];
        else
            return Glint_Excerpt::$length;
    }

    // Echoes out the excerpt
    public static function output() {
        the_excerpt();
    }

    public static function continue_reading_link() {

        return '<div class="btn-wrapper"><a href="'.esc_url( get_permalink() ).'" class="boxed-btn btn-rounded">'.esc_html__("  Read More",'glint').'</a></div>';
    }

    public static function auto_excerpt_more( ) {
        if (Glint_Excerpt::$more) :
            return ' &hellip;' . Glint_Excerpt::continue_reading_link();
        else :
            return ' &hellip;';
        endif;
    }

}

// An alias to the class
function Glint_Excerpt($length = 55, $more=true) {
	Glint_Excerpt::length($length, $more);
}