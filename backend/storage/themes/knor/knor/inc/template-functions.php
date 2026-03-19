<?php if (!defined('ABSPATH')) die('Direct access forbidden.');

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package knor
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
 

/** Echo Variable **/

function knor_return( $s ) {
   return $s;
}


/** Get Tag List **/

if( !function_exists('knor_post_tags')){
	
	function knor_post_tags() {
		$terms = get_terms( array(
			'taxonomy'    => 'post_tag',
			'hide_empty'  => false,
			'posts_per_page' => -1, 
		) );
		$cat_list = [];
		foreach($terms as $post) {
		$cat_list[$post->term_id]  = [$post->name];
		}
		return $cat_list;
	}
}

/** Post Read Time **/

function knor_reading_time() {
	
	global $post;
	
	$content = get_post_field( 'post_content', $post->ID );
	$word_count = str_word_count( strip_tags( $content ) );
	$readingtime = ceil($word_count / 200);
	if ($readingtime == 1) {
	$timer = " min read";
	} else {
	$timer = " min read";
	}
	$totalreadingtime = $readingtime . $timer;
	return $totalreadingtime;
}



/** Post View **/
 
function knor_get_post_view() {

	$count = get_post_meta( get_the_ID(), 'post_views_count', true );
	return "$count";
}


function knor_set_post_view() {

	$key = 'post_views_count';
	$post_id = get_the_ID();
	$count = (int) get_post_meta( $post_id, $key, true );
	$count++;

	update_post_meta( $post_id, $key, $count );

}


// return embed code video url
// ----------------------------------------------------------------------------------------
function knor_video_embed($url){
    //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
	$embed_url = '';
    if(strpos($url, 'facebook.com/') !== false) {
		
        //it is FB video
        $embed_url = esc_url('https://www.facebook.com/plugins/video.php?href='.rawurlencode($url).'&show_text=1&width=200');
		
    }else if(strpos($url, 'vimeo.com/') !== false) {
        //it is Vimeo video
        $video_id = explode("vimeo.com/",$url)[1];
        if(strpos($video_id, '&') !== false){
            $video_id = explode("&",$video_id)[0];
        }
		
        $embed_url = esc_url('https://player.vimeo.com/video/'.$video_id);
			
    }else if(strpos($url, 'youtube.com/') !== false) {
        //it is Youtube video
        $video_id = explode("v=",$url)[1];
        if(strpos($video_id, '&') !== false){
            $video_id = explode("&",$video_id)[0];
        }
		$embed_url =esc_url('https://www.youtube.com/embed/'.$video_id);
		
    }else if(strpos($url, 'youtu.be/') !== false){
        //it is Youtube video
        $video_id = explode("youtu.be/",$url)[1];
        if(strpos($video_id, '&') !== false){
            $video_id = explode("&",$video_id)[0];
        }
        $embed_url =esc_url('https://www.youtube.com/embed/'.$video_id);
    }
	
	else{
        //for new valid video URL
    }
    return $embed_url;
}  


//*** Prev Next Post ***//

if(!function_exists('knor_theme_post_navigation')) {
  function knor_theme_post_navigation() { 

    $previous_post       = get_previous_post();
    $prev_thumbnail      = (is_object($previous_post) && !empty($previous_post)) ? get_the_post_thumbnail($previous_post->ID):'';
    $next_post           = get_next_post();
    $next_post_thumbnail = (is_object($next_post) && !empty($next_post)) ? get_the_post_thumbnail($next_post->ID):'';
    $col_class           = ($previous_post && $next_post) ? 'col-sm-6':'col-sm-12';
    if($previous_post || $next_post):
  ?>
    
	<div class="theme_blog_navigation__Wrap">
    <div class="row">

      <?php if ($previous_post): ?>
      <div class="<?php echo esc_attr($col_class); ?>">
        <div class="theme_blog_Nav post_nav_Left <?php echo (empty($prev_thumbnail)) ? 'no-thumb':''; ?>">
          <?php if(!empty($prev_thumbnail)): ?>
            <div class="theme_blog_nav_Img prev_nav_left_Img">
              <?php echo wp_kses_post($prev_thumbnail); ?>
            </div>
          <?php endif; ?>
          <div class="theme_blog_nav_Inner">
            <div class="theme_blog_nav_Label">
			
				<?php $blog_prev_title = knor_get_option('blog_prev_title');  ?>
				<?php echo esc_html($blog_prev_title); ?>
			
			</div>
            <h3 class="theme_blog_nav_Title"><?php previous_post_link('%link', '%title'); ?></h3>
          </div>
        </div>

      </div>
	  
      <?php endif; ?>
      <?php if ($next_post): ?>
	  
      <div class="<?php echo esc_attr($col_class); ?>">
	  
        <div class="theme_blog_Nav post_nav_Right <?php echo (empty($next_post_thumbnail)) ? 'no-thumb':''; ?>">
          <?php if(!empty($next_post_thumbnail)): ?>
            <div class="theme_blog_nav_Img prev_nav_Right_Img">
             <?php echo wp_kses_post($next_post_thumbnail); ?>
            </div>
          <?php endif; ?>
          <div class="theme_blog_Inner">
            <div class="theme_blog_nav_Label">
			
			<?php $blog_next_title = knor_get_option('blog_next_title');  ?>
			<?php echo esc_html($blog_next_title); ?>
			
			</div>
            <h3 class="theme_blog_nav_Title"><?php next_post_link('%link', '%title'); ?></h3>
          </div>
        </div>
      </div>
      <?php endif; ?>


    </div>
    </div>

  <?php endif;
  }
}

//*** Categories Count custom markup ***//

function knor_categories_postcount_filter ($variable) {
   $variable = str_replace('(', '<span class="post_count"> ', $variable);
   $variable = str_replace(')', ' </span>', $variable);
   return $variable;
}
add_filter('wp_list_categories','knor_categories_postcount_filter');




