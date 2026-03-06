<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Section_Blog extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'section-blog';
	}

	public function get_title() {
		return 'Blog';   // title to show on elementor
	}

	public function get_icon() {
		return 'fal fa-edit';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
	}

	public function get_categories() {
		return [ 'geoport-elements' ];    // category of the widget
	}
     public function get_script_depends() {
		return [ 'geoport-elementor-editor' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/
	protected function register_controls() {
		
	//start of a control box
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Blog Grid', 'geoport' ),   //section name for controler view
			]
		);
		$this->add_control(
			'post_number',
			[
				'label' => esc_html__( 'Number of Item', 'geoport' ),
				'description' => esc_html__( 'Give -1 for all post', 'geoport' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
			]
		);
		$this->add_control(
			'post_excerpt',
			[
				'label' => esc_html__( 'Post Excerpt', 'geoport' ),
				'description' => esc_html__( 'Post content excerpt', 'geoport' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
			]
		);
		$this->add_control(
			'post_orderby',
			[
				'label' => __( 'Order by', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' =>ifinger_post_orderby_options(),
			]
		);
		$this->add_control(
			'post_order',
			[
				'label' => __( 'Order', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => __( 'Ascending Order', 'geoport' ),
					'DESC' => __( 'Descending', 'geoport' ),
				],
			]
		);
		$this->add_control(
			'hide_post_cat',
			[
				'label' => __( 'Hide Post Category', 'geoport' ),
				'type'  => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'geoport' ),
				'label_off' => __( 'Hide', 'geoport' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'hide_post_admin',
			[
				'label' => __( 'Hide Post Admin', 'geoport' ),
				'type'  => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'geoport' ),
				'label_off' => __( 'Hide', 'geoport' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'hide_post_comments',
			[
				'label' => __( 'Hide Post Comments', 'geoport' ),
				'type'  => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'geoport' ),
				'label_off' => __( 'Hide', 'geoport' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
		   'blog_style',
		   [
		   	'label'       => __( 'Blog Style', 'geoport' ),
		   	'type' => Controls_Manager::SELECT,
		   	'default' => '1',
		   	'options' => [
		     		'1'   => __( 'Style 1', 'geoport' ),
		     		'2'  => __( 'Style 2', 'geoport' ),
		     	],
		   ]
		);
		$this->add_control(
			'blog_columns',
			[
			    'label'       => __( 'Blog columns', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => '4',
			    'options' => [
			     	'6'   => __( '2', 'geoport' ),
			     	'4'   => __( '3', 'geoport' ),
			     	'3'   => __( '4', 'geoport' ),
			    ],
			]
		);

		$this->end_controls_section();

		/* = Post Styling
		========================================*/
		$this->start_controls_section(
			'blog_post_cat_style',
			[
				'label' => esc_html__( 'Category Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		/* = Title Styling
		========================================*/
		$this->add_control(
			'cat_bg_color',
			[
				'label' => __( 'Category Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .blog-post-tag a' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cat_bg_box_shadow',
				'label' => __( 'Box Shadow', 'geoport' ),
				'selector' => '{{WRAPPER}} .blog-post-tag a',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title1_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title h2 span.tl1',
			]
		);
		// Categories Hover 
		$this->add_control(
			'cat_hover_color',
			[
				'label' => __( 'Hover Style', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'cat_bg_hover_color',
			[
				'label' => __( 'Hover Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#cee2ff',
				'selectors' => [
					'{{WRAPPER}} .blog-post-tag a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cat_bgh_box_shadow',
				'label' => __( 'Box Shadow', 'geoport' ),
				'selector' => '{{WRAPPER}} .blog-post-tag a:hover',
			]
		);
		$this->add_control(
			'cat_font_hove_color',
			[
				'label' => __( 'Hover Font Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .blog-post-tag a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		/* = Post Styling
		========================================*/
		$this->start_controls_section(
			'blog_post_title_style',
			[
				'label' => esc_html__( 'Content Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		/* = Title Styling
		========================================*/
		$this->add_control(
			'title_font_color',
			[
				'label' => __( 'Font Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .blog-content h5 a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .blog-content h5 a',
			]
		);
		$this->add_control(
			'icon_font_color',
			[
				'label' => __( 'Icon Font Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .blog-meta ul li i' => 'color: {{VALUE}};',
				],
			]
		);
		/* = Title Styling
		========================================*/
		$this->add_control(
			'link_font_hover_color',
			[
				'label' => __( 'Link hover Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .blog-content h5 a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .blog-meta ul li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

	}

	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings_for_display();

		$post_number = $settings['post_number'];
		$content_excerpt = $settings['post_excerpt'];
		$post_orderby = $settings['post_orderby'];
		$post_order = $settings['post_order'];
		$blog_columns = $settings['blog_columns'];
		$blog_style = $settings['blog_style'];

		$hide_post_cat = $settings['hide_post_cat'];
		$hide_post_admin = $settings['hide_post_admin'];
		$hide_post_comments = $settings['hide_post_comments'];

		if ($blog_columns == '6') {
		  $crop_img = 'geoport-770-460';
		} elseif ($blog_columns == '4') {
		  $crop_img = 'geoport-770-460';
		} elseif ($blog_columns == '3') {
		  $crop_img = 'geoport-770-460';
		} else {
		  $crop_img = 'full';
		}

		$grid_query= null;
	   $args = array(
	      'post_type'      => 'post',
	      'post_status'    => 'publish',
	      'orderby' => $post_orderby,
	      'order' => $post_order,
	      'posts_per_page' => $post_number,
	   );
		 
		$grid_query = new \WP_Query( $args );

		if ( $grid_query->have_posts() ) :
?>

<script type="text/javascript">
   jQuery(document).ready(function(){
		// blog
		jQuery('.blog-post-thumb-active').slick({
			autoplay:true,
			autoplaySpeed:1500,
			dots: false,
			infinite: true,
			arrows: true,
			speed: 1500,
			slidesToShow: 1,
			slidesToScroll: 1,
			fade: true,
			prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
			nextArrow: '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
		});
   });
</script> 

<?php if ( $blog_style == '2' ) { ?>

<div class="row">
	<?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post;

		if(has_post_thumbnail()) {
			$cat_class = 'has-thumb';
		} else {
			$cat_class = 'no-thumb';
		} 

		if ( $hide_post_cat == true ) {
			$cat_switch = 'cat-yes';
		} else {
			$cat_switch = 'cat-no';
		}
		

	   $default_post_metadata = get_post_meta( get_the_ID(), '_geoport_post', true);

		if (!empty($default_post_metadata['post_format_type'] )) {
		  $post_format_type = $default_post_metadata['post_format_type'];
		} else {
		  $post_format_type = '';
		}

		if (!empty($default_post_metadata['video_type'] )) {
		  $video_type = $default_post_metadata['video_type'];
		} else {
		  $video_type = '';
		}
		if (!empty($default_post_metadata['video_link'] )) {
		  $video_link = $default_post_metadata['video_link'];
		} else {
		  $video_link = '';
		}
		if (!empty($default_post_metadata['audio_link'] )) {
		  $audio_link = $default_post_metadata['audio_link'];
		} else {
		  $audio_link = '';
		}  
		if (!empty($default_post_metadata['geoport_quote_icon'] )) {
		  $geoport_quote_icon = $default_post_metadata['geoport_quote_icon'];
		} else {
		  $geoport_quote_icon = '';
		}  

		if (!empty($default_post_metadata['gallery_list'] )) {
		  $gallery_list = $default_post_metadata['gallery_list'];
		} else {
		  $gallery_list = '';
		} 

		if ($gallery_list) {
		   $ids = explode(",",$gallery_list);
		} else {
		   $ids = '';
		}
	    
  	?>

	<div class="col-lg-<?php echo esc_attr( $blog_columns ); ?> col-md-6 blog-shortcode">
	  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	   <div class="single-blog-post mb-30">

	   		<?php if ($post_format_type == 'geoport-video') { ?>

			   <div class="blog-thumb position-relative">
		         <?php the_post_thumbnail( $crop_img ); ?>
		         <a href="<?php echo esc_url( $video_link ); ?>" class="video-i popup-video"><i class="fas fa-play"></i></a>
	         </div>
	        <div class="blog-content <?php echo esc_attr( $cat_switch ); ?>">
	        	<?php if ( $hide_post_cat == 'yes' ) { ?>
               <div class="blog-post-tag <?php echo esc_attr( $cat_class ); ?>">
                  <?php the_category(' '); ?>
               </div>
               <?php } ?>
               <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
               <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
               <div class="blog-meta">
                  <ul>
                  	<?php if ( $hide_post_admin == 'yes' ) { ?>
                     	<li>
                     		<i class="far fa-user"></i><?php esc_html_e('By ', 'geoport'); ?>
                     		<?php the_author_posts_link(); ?>
                     	</li>
                     <?php } if ( $hide_post_comments =='yes' ) { ?>
                     	<li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                  	<?php } ?>
                  </ul>
               </div>
            </div>

	    	<?php } elseif ($post_format_type == 'geoport-gallery') { ?>

	         <div class="bpost-thumb blog-thumb-active">
	         	<div class="slide-post">
		            <?php the_post_thumbnail( $crop_img ); ?>
		         </div>
	          	<?php 
	            if (!empty($ids)) {
	              foreach ($ids as $key => $value) {
	                $src = wp_get_attachment_image_src( $value, "geoport-770-460" ); ?>
	            <div class="slide-post">
	            	<img src="<?php echo esc_url($src[0]); ?>" alt="<?php esc_attr_e( 'Post gallery image', 'geoport' ); ?>">
	            </div>
	          	<?php } 
	          	} ?>
	        	</div>
        	<div class="blog-content <?php echo esc_attr( $cat_switch ); ?>">
        		<?php if ( $hide_post_cat == 'yes' ) { ?>
                <div class="blog-post-tag <?php echo esc_attr( $cat_class ); ?>">
                  <?php the_category(' '); ?>
                </div>
           		<?php } ?>
               <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
               <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
               <div class="blog-meta">
                  <ul>
                  	<?php if ( $hide_post_admin == 'yes' ) { ?>
                     	<li>
                     		<i class="far fa-user"></i><?php esc_html_e('By ', 'geoport'); ?>
                     		<?php the_author_posts_link(); ?>
                     	</li>
                     <?php } if ( $hide_post_comments == 'yes' ) { ?>
                     	<li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                  	<?php } ?>
                  </ul>
               </div>
            </div>

	    <?php } elseif ($post_format_type == 'geoport-audio') { ?>

	      <div class="bpost-thumb embed-responsive embed-responsive-16by9">
            <iframe src="<?php echo esc_url( $audio_link ); ?>"></iframe>
         </div>
         <div class="blog-content <?php echo esc_attr( $cat_switch ); ?>">
         	<?php if ( $hide_post_cat == 'yes' ) { ?>
            <div class="blog-post-tag <?php echo esc_attr( $cat_class ); ?>">
               <?php the_category(' '); ?>
            </div>
            <?php } ?>
            <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
            <div class="blog-meta">
               <ul>
               	<?php if ( $hide_post_admin == 'yes' ) { ?>
                  	<li>
                  		<i class="far fa-user"></i><?php esc_html_e('By ', 'geoport'); ?>
                  		<?php the_author_posts_link(); ?>
                  	</li>
                  <?php } if ( $hide_post_comments =='yes' ) { ?>
                  	<li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
               	<?php } ?>
               </ul>
            </div>
         </div>

	   <?php } elseif( $post_format_type == 'geoport-nothing' ){
			
      } elseif ($post_format_type == 'geoport-quote') { 
      	$src = get_the_post_thumbnail_url();
        	$attachment = wp_get_attachment_image_src( $geoport_quote_icon, 'full' );
        	$icon_img    = ($attachment) ? $attachment[0] : $geoport_quote_icon;
      ?>
         <div class="bpost-content quote-post" data-background="<?php echo esc_url( $src ); ?>">
	         <div class="quote-icon">
	            <img src="<?php echo esc_url( $icon_img ); ?>" alt="<?php esc_html_e( 'quote icon', 'geoport' ); ?>">
	         </div>
            <div class="fix">
	            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	            <div class="bpost-meta">
		         	<ul>
	               	<?php if ( $hide_post_admin == 'yes' ) { ?>
	                  	<li>
	                  		<i class="far fa-user"></i><?php esc_html_e('By ', 'geoport'); ?>
	                  		<?php the_author_posts_link(); ?>
	                  	</li>
	                  <?php } if ( $hide_post_comments =='yes' ) { ?>
	                  	<li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
	               	<?php } ?>
	               </ul>
	            </div>
            </div>
         </div>

	      <?php } else { ?>

	      <?php if(has_post_thumbnail()) { ?>
         	<div class="blog-thumb">
            	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $crop_img ); ?></a>
         	</div>                                                                                        
         <?php } ?>
         <div class="blog-content <?php echo esc_attr( $cat_switch ); ?>">
         	<?php if ( $hide_post_cat == 'yes' ) { ?>
            <div class="blog-post-tag <?php echo esc_attr( $cat_class ); ?>">
               <?php the_category(' '); ?>
            </div>
        	<?php } ?>
            <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
            <div class="blog-meta">
               <ul>
	            	<?php if ( $hide_post_admin == 'yes' ) { ?>
	               	<li>
	               		<i class="far fa-user"></i><?php esc_html_e('By ', 'geoport'); ?>
	               		<?php the_author_posts_link(); ?>
	               	</li>
	               <?php } if ( $hide_post_comments =='yes' ) { ?>
	               	<li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
	            	<?php } ?>
	            </ul>
            </div>
         </div>
	      <?php } ?>
	      <?php if ( is_sticky() ) {
	        echo '<sup class="meta-featured-post"> <i class="fal fa-thumbtack"></i> ' . esc_html__( 'Sticky', 'geoport' ) . ' </sup>';
	      } ?>
	   </div>
		</article>
	</div>

   <?php endwhile; wp_reset_postdata(); ?>
</div>

<?php } elseif ( $blog_style == '1' ) { ?> 
	
<div class="row blog-shortcode">
	<?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 

	   $default_post_metadata = get_post_meta( get_the_ID(), '_geoport_post', true);

		if (!empty($default_post_metadata['post_format_type'] )) {
		  $post_format_type = $default_post_metadata['post_format_type'];
		} else {
		  $post_format_type = '';
		}

		if (!empty($default_post_metadata['video_type'] )) {
		  $video_type = $default_post_metadata['video_type'];
		} else {
		  $video_type = '';
		}
		if (!empty($default_post_metadata['video_link'] )) {
		  $video_link = $default_post_metadata['video_link'];
		} else {
		  $video_link = '';
		}
		if (!empty($default_post_metadata['audio_link'] )) {
		  $audio_link = $default_post_metadata['audio_link'];
		} else {
		  $audio_link = '';
		}  
		if (!empty($default_post_metadata['geoport_quote_icon'] )) {
		  $geoport_quote_icon = $default_post_metadata['geoport_quote_icon'];
		} else {
		  $geoport_quote_icon = '';
		}  

		if (!empty($default_post_metadata['gallery_list'] )) {
		  $gallery_list = $default_post_metadata['gallery_list'];
		} else {
		  $gallery_list = '';
		} 

		if ($gallery_list) {
		    $ids = explode(",",$gallery_list);
		} else {
		    $ids = '';
		}

  	?>
    <div class="col-lg-<?php echo esc_attr( $blog_columns ); ?> col-md-6">
	   <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	      <div class="bsingle-post shortcode-panel mb-30">
	      <?php if ($post_format_type == 'geoport-video') { ?>

	         <div class="bpost-thumb position-relative">
	         	<?php the_post_thumbnail( $crop_img ); ?>
	         	<a href="<?php echo esc_url( $video_link ); ?>" class="video-i popup-video"><i class="fas fa-play"></i></a>
	         </div>
	         <div class="bpost-content">
	        <?php if ( $hide_post_cat == 'yes' ) { ?>
          	<div class="b-tag">
            	<?php the_category(' '); ?>
          	</div>
      		<?php } ?>
          <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="bpost-meta mb-20">
            <ul>
              <?php if (!empty($blog_post_date)) { ?>
                <li><span><i class="<?php echo esc_attr(  $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span></li>
              <?php } if (!empty($blog_post_views)) {
                if(function_exists('geoport_set_PostViews')) { ?>
                <li><span><i class="fal fa-eye"></i><?php echo geoport_get_PostViews(get_the_ID()); ?> <?php esc_html_e( 'Views', 'geoport' ); ?></span></li>
              <?php }
              } if (!empty($blog_post_comments)) { ?>
                <li><a href="<?php the_permalink(); ?>"><i class="<?php echo esc_attr( $comments ); ?>"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
          <?php if ( $blog_post_admin == 'true') { ?>
            <div class="bpost-avatar">
              <div class="bavatar-img">
                <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
              </div>
              <div class="bavatar-info">
                <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
              </div>
            </div>
          <?php } ?>
          <div class="b-readmore">
            <a href="<?php the_permalink(); ?>"><i class="<?php echo esc_attr( $readmore ); ?>"></i><?php esc_html_e( 'Read More', 'geoport' ); ?></a>
          </div>
        </div>

	      <?php } elseif ($post_format_type == 'geoport-gallery') { ?>

	        <div class="bpost-thumb blog-post-thumb-active">
	          <div class="slide-post">
	            <?php the_post_thumbnail( $crop_img ); ?>
	          </div>
	          <?php 
	            if (!empty($ids)) {
	              foreach ($ids as $key => $value) {
	                $src = wp_get_attachment_image_src( $value, "geoport-770-460" ); ?>
	          <div class="slide-post">
	              <img src="<?php echo esc_url($src[0]); ?>" alt="<?php esc_attr_e( 'post gallery image', 'geoport' ); ?>">
	          </div>
	          <?php } 
	          } ?>
	        </div>
	         <div class="blog-content">
	         	<?php if ( $hide_post_cat == 'yes' ) { ?>
               	<div class="blog-post-tag">
                	<?php the_category(' '); ?>
               	</div>
           		<?php } ?>
               <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
               <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
               <div class="blog-meta">
                  <ul>
                  	<?php if ( !empty( $blog_post_admin ) ) { ?>
                     	<li>
                     		<i class="far fa-user"></i><?php esc_html_e('By ', 'geoport'); ?>
                     		<?php the_author_posts_link(); ?>
                     	</li>
                     <?php } if (!empty($blog_post_comments)) { ?>
                     	<li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                  	<?php } ?>
                  </ul>
               </div>
            </div>
	      <?php } elseif ($post_format_type == 'geoport-audio') { ?>
	         <div class="bpost-thumb embed-responsive embed-responsive-16by9">
	            <iframe src="<?php echo esc_url( $audio_link ); ?>"></iframe>
	         </div>
	         <div class="blog-content">
	         	<?php if ( $hide_post_cat == 'yes' ) { ?>
                <div class="blog-post-tag">
                  <?php the_category(' '); ?>
                </div>
            	<?php } ?>
               <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
               <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
               <div class="blog-meta">
                  <ul>
                  	<?php if ( !empty( $blog_post_admin ) ) { ?>
                     	<li>
                     		<i class="far fa-user"></i><?php esc_html_e('By ', 'geoport'); ?>
                     		<?php the_author_posts_link(); ?>
                     	</li>
                     <?php } if (!empty($blog_post_comments)) { ?>
                     	<li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                  	<?php } ?>
                  </ul>
               </div>
            </div>
	      <?php } elseif ($post_format_type == 'geoport-quote') { 
	        $src = get_the_post_thumbnail_url();
	        $attachment = wp_get_attachment_image_src( $geoport_quote_icon, 'full' );
	        $icon_img    = ($attachment) ? $attachment[0] : $geoport_quote_icon;
	      ?>
	        <div class="bpost-content quote-post" data-background="<?php echo esc_url( $src ); ?>">
	          <div class="quote-icon">
	            <img src="<?php echo esc_url( $icon_img ); ?>" alt="<?php esc_html_e( 'quote icon', 'geoport' ); ?>">
	          </div>
	          <div class="fix">
	            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	          </div>
	        </div>
	    <?php } else { ?>
	        <?php if(has_post_thumbnail()) { ?>
	          <div class="blog-thumb">
	            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $crop_img ); ?></a>
	          </div>                                                                                        
	        <?php } ?>
	        <div class="bpost-content">
	        	<?php if ( $hide_post_cat == 'yes' ) { ?>
	          	<div class="b-tag">
	            	<?php the_category(' '); ?>
	          	</div>
	          	<?php } ?>
	          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	          <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
	            <div class="bpost-avatar">
	              <div class="bavatar-img">
	                <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
	              </div>
	              <div class="bavatar-info">
	                <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
	              </div>
	            </div>
	          <div class="b-readmore">
	            <a href="<?php the_permalink(); ?>"><i class="fal fa-arrow-right"></i><?php esc_html_e( 'Read More', 'geoport' ); ?></a>
	          </div>
	        </div>
	      <?php } ?>
	      <?php if ( is_sticky() ) {
	        echo '<sup class="meta-featured-post"> <i class="fal fa-thumbtack"></i> ' . esc_html__( 'Sticky', 'geoport' ) . ' </sup>';
	      } ?>
	    </div>
	  </article>
	</div>
    <?php endwhile; wp_reset_postdata(); ?>
</div>

<?php } else { ?>

<div class="row">
	<?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 

	    $default_post_metadata = get_post_meta( get_the_ID(), '_geoport_post', true);

		if (!empty($default_post_metadata['post_format_type'] )) {
		  $post_format_type = $default_post_metadata['post_format_type'];
		} else {
		  $post_format_type = '';
		}

		if (!empty($default_post_metadata['video_type'] )) {
		  $video_type = $default_post_metadata['video_type'];
		} else {
		  $video_type = '';
		}
		if (!empty($default_post_metadata['video_link'] )) {
		  $video_link = $default_post_metadata['video_link'];
		} else {
		  $video_link = '';
		}
		if (!empty($default_post_metadata['audio_link'] )) {
		  $audio_link = $default_post_metadata['audio_link'];
		} else {
		  $audio_link = '';
		}  
		if (!empty($default_post_metadata['geoport_quote_icon'] )) {
		  $geoport_quote_icon = $default_post_metadata['geoport_quote_icon'];
		} else {
		  $geoport_quote_icon = '';
		}  

		if (!empty($default_post_metadata['gallery_list'] )) {
		  $gallery_list = $default_post_metadata['gallery_list'];
		} else {
		  $gallery_list = '';
		} 

		if ($gallery_list) {
		    $ids = explode(",",$gallery_list);
		} else {
		    $ids = '';
		}
  	?>

	<div class="col-lg-<?php echo esc_attr( $blog_columns ); ?> col-md-6 blog-shortcode">
	  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    <div class="single-blog-post mb-30">
	    <?php if ($post_format_type == 'geoport-video') { ?>

		    <div class="bpost-thumb position-relative">
	         <?php the_post_thumbnail( $crop_img ); ?>
	         <a href="<?php echo esc_url( $video_link ); ?>" class="video-i popup-video"><i class="fas fa-play"></i></a>
	        </div>

	        <div class="blog-content">
                <div class="blog-meta mb-10">
                    <ul>
                    	<?php if (!empty($blog_post_date)) { ?>
                        <li><i class="far fa-calendar-alt"></i><?php echo esc_html ( get_the_date() ); ?></li>
                        <?php } if (!empty($blog_post_comments)) { ?>
                        <li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                    	<?php } ?>
                    </ul>
                </div>
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
                <div class="blog-avatar">
                    <div class="blog-avatar-img">
                        <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
                    </div>
                    <div class="blog-avatar-info">
                        <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
                    </div>
                </div>
            </div>

	    <?php } elseif ($post_format_type == 'geoport-gallery') { ?>
	        <div class="single-blog-post blog-post-thumb-active">
	          <div class="slide-post">
	            <?php the_post_thumbnail( $crop_img ); ?>
	          </div>
	          <?php 
	            if (!empty($ids)) {
	              foreach ($ids as $key => $value) {
	                $src = wp_get_attachment_image_src( $value, "geoport-770-460" ); ?>
	          <div class="slide-post">
	              <img src="<?php echo esc_url($src[0]); ?>" alt="<?php esc_attr_e( 'post gallery image', 'geoport' ); ?>">
	          </div>
	          <?php } 
	          } ?>
	        </div>
	        <div class="blog-content">
                <div class="blog-meta mb-10">
                    <ul>
                    	<?php if (!empty($blog_post_date)) { ?>
                        <li><i class="far fa-calendar-alt"></i><?php echo esc_html ( get_the_date() ); ?></li>
                        <?php } if (!empty($blog_post_comments)) { ?>
                        <li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                    	<?php } ?>
                    </ul>
                </div>
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
                <div class="blog-avatar">
                    <div class="blog-avatar-img">
                        <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
                    </div>
                    <div class="blog-avatar-info">
                        <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
                    </div>
                </div>
            </div>

	    <?php } elseif ($post_format_type == 'geoport-audio') { ?>

         <div class="single-blog-post embed-responsive embed-responsive-16by9">
            <iframe src="<?php echo esc_url( $audio_link ); ?>"></iframe>
         </div>

         <div class="blog-content">
             <div class="blog-meta mb-10">
                 <ul>
                 	<?php if (!empty($blog_post_date)) { ?>
                     <li><i class="far fa-calendar-alt"></i><?php echo esc_html ( get_the_date() ); ?></li>
                     <?php } if (!empty($blog_post_comments)) { ?>
                     <li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                 	<?php } ?>
                 </ul>
             </div>
             <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
             <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
             <div class="blog-avatar">
                 <div class="blog-avatar-img">
                     <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
                 </div>
                 <div class="blog-avatar-info">
                     <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
                 </div>
             </div>
         </div>

	    <?php } elseif( $post_format_type == 'geoport-quote' ) { ?>
	    <?php 
	    	$src = get_the_post_thumbnail_url();
	    	$attachment = wp_get_attachment_image_src( $geoport_quote_icon, 'full' );
	        $icon_img    = ($attachment) ? $attachment[0] : $geoport_quote_icon;
	    ?>
	        <div class="blog-content quote-post" data-background="<?php echo esc_url( $src ); ?>">
	        	<div class="quote-icon">
	            <img src="<?php echo esc_url( $icon_img ); ?>" alt="<?php esc_html_e( 'quote icon', 'geoport' ); ?>">
	        	</div>
	        	<div class="fix">
	            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	            <div class="bpost-meta">
	               <ul>
	               	<?php if (!empty($blog_post_date)) { ?>
	                  	<li><span><i class="fal fa-calendar-alt"></i><?php echo esc_html ( get_the_date() ); ?></span></li>
	               	<?php } 
	                	if (!empty($blog_post_views)) { 
	                		if(function_exists('geoport_set_PostViews')) { ?>
	                  		<li><span><i class="fal fa-eye"></i><?php echo geoport_get_PostViews(get_the_ID()); ?> <?php esc_html_e( 'Views', 'geoport' ); ?></span></li>
	                  <?php }
	                  } if (!empty($blog_post_comments)) { ?>
	                  	<li><a href="<?php the_permalink(); ?>"><i class="fal fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li>
	                  <?php } ?>
	               </ul>
	            </div>
	          </div>
	        </div>
	      <?php } else { ?>
	        <?php if(has_post_thumbnail()) { ?>
	          <div class="blog-thumb">
	            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $crop_img ); ?></a>
	          </div>                                                                                        
	        <?php 
	        $have_thumb = 'have-thumb';
	    	} else { $have_thumb = 'no-thumb'; } ?>
	         <div class="blog-content <?php echo esc_attr( $have_thumb ); ?>">
                <div class="blog-meta mb-10">
                    <ul>
                    	<?php if (!empty($blog_post_date)) { ?>
                        <li><i class="far fa-calendar-alt"></i><?php echo esc_html ( get_the_date() ); ?></li>
                        <?php } if (!empty($blog_post_comments)) { ?>
                        <li><i class="far fa-comments"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                    	<?php } ?>
                    </ul>
                </div>
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
                <div class="blog-avatar">
                    <div class="blog-avatar-img">
                        <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
                    </div>
                    <div class="blog-avatar-info">
                        <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
                    </div>
                </div>
            </div>
	      <?php } ?>
	      <?php if ( is_sticky() ) {
	        echo '<sup class="meta-featured-post"> <i class="fal fa-thumbtack"></i> ' . esc_html__( 'Sticky', 'geoport' ) . ' </sup>';
	      } ?>
	    </div>
	  </article>
	</div>

    <?php endwhile; wp_reset_postdata(); ?>
</div>

<?php } ?>
<?php endif;   //main if end

	}
}