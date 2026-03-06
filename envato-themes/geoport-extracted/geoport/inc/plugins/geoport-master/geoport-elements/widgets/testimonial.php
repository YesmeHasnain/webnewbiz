<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Testimonial extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'section-testimonial';
	}

	public function get_title() {
		return 'Testimonial';   // title to show on elementor
	}

	public function get_icon() {
		return 'fal fa-quote-left';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Testimonial', 'geoport' ),   //section name for controler view
			]
		);
	
		$this->add_control(
			'post_number',
			[
				'label' => esc_html__( 'Number of testimonial', 'geoport' ),
				'description' => esc_html__( 'Give -1 for all post', 'geoport' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);
		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' =>ifinger_post_orderby_options(),
			]
		);
		$this->add_control(
			'order',
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
			  'review_disable',
			  [
			     'label'       => __( 'Review Disable ?', 'geoport' ),
			     'type' => Controls_Manager::SELECT,
			     'default' => 'no',
			     'options' => [
			     	'no' => __( 'No', 'geoport' ),
			     	'yes'  => __( 'Yes', 'geoport' ),
			     ],
			  ]
		);
		$this->add_control(
			  'slider',
			  [
			     'label'   => __( 'Slider', 'geoport' ),
			     'type'    => Controls_Manager::SELECT,
			     'default' => 'no',
			     'options' => [
			     	'no'     => __( 'No', 'geoport' ),
			     	'yes'    => __( 'Yes', 'geoport' ),
			     ],
			  ]
		);
		$this->add_control(
			'slider_navi',
			[
			   'label'   => __( 'Slider Navigation', 'geoport' ),
			   'type'    => Controls_Manager::SELECT,
			   'default' => 'false',
			   'options' => [
			   	'false'  => __( 'No', 'geoport' ),
			   	'true'   => __( 'Yes', 'geoport' ),
			   ],
			]
		);
		$this->add_control(
			'grid_columns',
			[
			    'label'     => __( 'Grid Columns', 'geoport' ),
			    'type'      => Controls_Manager::SELECT,
			    'default'   => '12',
			    'options'   => [
			     	'12'      => __( '1 Columns', 'geoport' ),
			     	'6'       => __( '2 Columns', 'geoport' ),
			     	'4'       => __( '3 Columns', 'geoport' ),
			     	'3'       => __( '4 Columns', 'geoport' ),
			    ],
			    'condition' => [
					'slider'  => 'no',
				],
			]
		);

		$this->add_control(
			'show_item',
			[
			    'label'       => __( 'Show on large device', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => '4',
			    'options' => [
			     	'1'  => __( '1', 'geoport' ),
			     	'2'  => __( '2', 'geoport' ),
			     	'3' => __( '3', 'geoport' ),
			     	'4' => __( '4', 'geoport' ),
			     	'6' => __( '6', 'geoport' ),
			    ],
			    'condition' => [
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider_cp',
			[
			    'label'   => __( 'Center Padding', 'geoport' ),
			    'type'    => Controls_Manager::TEXT,
			    'default' => '0px',
			    'description' => __( 'Center Padding is slider gap fixer', 'geoport' ),
			]
		);
		$this->add_control(
			'show_desktop',
			[
			    'label'   => __( 'Show on desktop', 'geoport' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => '3',
			    'options' => [
			     	'1' => __( '1', 'geoport' ),
			     	'2' => __( '2', 'geoport' ),
			     	'3' => __( '3', 'geoport' ),
			     	'4' => __( '4', 'geoport' ),
			     	'5' => __( '5', 'geoport' ),
			    ],
			    'condition' => [
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider_cp_desktop',
			[
			    'label'   => __( 'Center Padding For Desktop', 'geoport' ),
			    'type'    => Controls_Manager::TEXT,
			    'default' => '0px',
			    'description' => __( 'Center Padding is slider gap fixer', 'geoport' ),
			]
		);
		$this->add_control(
			'show_tablet',
			[
			    'label'   => __( 'Show on Tablet', 'geoport' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => '2',
			    'options' => [
			     	'1' => __( '1', 'geoport' ),
			     	'2' => __( '2', 'geoport' ),
			     	'3' => __( '3', 'geoport' ),
			     	'4' => __( '4', 'geoport' ),
			     	'5' => __( '5', 'geoport' ),
			    ],
			    'condition' => [
					'slider'  => 'yes',
				],
			]
		);
		$this->add_control(
			'slider_cp_tablet',
			[
			    'label'   => __( 'Center Padding For Tablet', 'geoport' ),
			    'type'    => Controls_Manager::TEXT,
			    'default' => '0px',
			    'description' => __( 'Center Padding is slider gap fixer', 'geoport' ),
			]
		);
		$this->add_control(
			'show_tab',
			[
			    'label'       => __( 'Show on tab', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => '1',
			    'options' => [
			     	'1'  => __( '1', 'geoport' ),
			     	'2'  => __( '2', 'geoport' ),
			     	'3' => __( '3', 'geoport' ),
			    ],
			    'condition' => [
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider_cp_tab',
			[
			    'label'   => __( 'Center Padding For Tab', 'geoport' ),
			    'type'    => Controls_Manager::TEXT,
			    'default' => '0px',
			    'description' => __( 'Center Padding is slider gap fixer', 'geoport' ),
			]
		);
		$this->add_control(
			'show_phone',
			[
			    'label'       => __( 'Show on phone', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => '1',
			    'options' => [
			     	'1' => __( '1', 'geoport' ),
			     	'2' => __( '2', 'geoport' ),
			     	'3' => __( '3', 'geoport' ),
			    ],
			    'condition' => [
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider_cp_phone',
			[
			    'label'   => __( 'Center Padding For Phone', 'geoport' ),
			    'type'    => Controls_Manager::TEXT,
			    'default' => '0px',
			    'description' => __( 'Center Padding is slider gap fixer', 'geoport' ),
			]
		);

		$this->end_controls_section();
	}


	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();

		$post_number = $settings['post_number'];
		$orderby = $settings['orderby'];
		$order = $settings['order'];

		$review_disable = $settings['review_disable'];
		$slider = $settings['slider'];
		$slider_navi = $settings['slider_navi'];
		$show_item = $settings['show_item'];
		$slider_cp = $settings['slider_cp'];
		$show_desktop = $settings['show_desktop'];
		$slider_cp_desktop = $settings['slider_cp_desktop'];
		$show_tablet = $settings['show_tablet'];
		$slider_cp_tablet = $settings['slider_cp_tablet'];
		$show_tab = $settings['show_tab'];
		$slider_cp_tab = $settings['slider_cp_tab'];
		$show_phone = $settings['show_phone'];
		$slider_cp_phone = $settings['slider_cp_phone'];

		$grid_query= null;
	    $args = array(
	       'post_type'      => 'testimonial',
	       'post_status'    => 'publish',
	       'orderby' => $orderby,
	       'order' => $order,
	       'posts_per_page' => $post_number,
	    );
		 
		$grid_query = new \WP_Query( $args );

		if ( $grid_query->have_posts() ) : 


	if ( $slider == 'yes' ) {

		$e_uniqid     = uniqid();
?>

<script type="text/javascript">
    jQuery(document).ready(function(){
		// testimonial-active
		jQuery(<?php echo "'.testi-slider-$e_uniqid'"; ?>).slick({
		  dots: false,
		  infinite: true,
		  speed: 600,
		  prevArrow: '<button type="button" class="slick-prev"><i class="far fa-angle-left"></i></button>',
		  nextArrow: '<button type="button" class="slick-next"><i class="far fa-angle-right"></i></button>',
		  arrows: <?php echo esc_attr( $slider_navi ); ?>,
		  centerMode: true,
		  centerPadding: "<?php echo esc_attr( $slider_cp ); ?>",
		  slidesToShow: <?php echo esc_attr( $show_item ); ?>,
		  responsive: [
		    {
		      breakpoint: 1700,
		      settings: {
		        slidesToShow: <?php echo esc_attr( $show_desktop ); ?>,
				slidesToScroll: 1,
				centerPadding: "<?php echo esc_attr( $slider_cp_desktop ); ?>",
		      }
		    },
		    {
		      breakpoint: 1500,
		      settings: {
		        slidesToShow: <?php echo esc_attr( $show_tablet ); ?>,
				slidesToScroll: 1,
				centerPadding: "<?php echo esc_attr( $slider_cp_tablet ); ?>",
		      }
		    },
		    {
		      breakpoint: 1200,
		      settings: {
		        slidesToShow: <?php echo esc_attr( $show_tab ); ?>,
				slidesToScroll: 1,
				centerPadding: "<?php echo esc_attr( $slider_cp_tab ); ?>",
		      }
		    },
		    {
		      breakpoint: 992,
		      settings: {
		        slidesToShow: <?php echo esc_attr( $show_tab ); ?>,
				slidesToScroll: 1,
				centerPadding: "<?php echo esc_attr( $slider_cp_tab ); ?>",
		      }
		    },
		    {
		      breakpoint: 768,
		      settings: {
		        slidesToShow: <?php echo esc_attr( $show_phone ); ?>,
		        slidesToScroll: 1,
				arrows: false,
				centerPadding: "<?php echo esc_attr( $slider_cp_phone ); ?>",
		      }
		    },
		    {
		      breakpoint: 575,
		      settings: {
		        slidesToShow: <?php echo esc_attr( $show_phone ); ?>,
		        slidesToScroll: 1,
				arrows: false,
				centerPadding: '0px',
		      }
		    },
		  ]
		});

    });
</script> 

<div class="row <?php echo "testi-slider-$e_uniqid"; ?> testimonial-active">
	<?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 

	   $ifinger_testimonial_info = get_post_meta( get_the_ID(), '_ifinger_testimonial', true );

	   if (!empty($ifinger_testimonial_info['rate_giver_name'])) {
	      $rate_giver_name = $ifinger_testimonial_info['rate_giver_name'];
	   } else {
	      $rate_giver_name = '';
	   } if (!empty($ifinger_testimonial_info['rate_giver_designation'])) {
	      $designation = $ifinger_testimonial_info['rate_giver_designation'];
	   } else {
	      $designation = '';
	   } if (!empty($ifinger_testimonial_info['rate_giver_organization'])) {
	      $organization = $ifinger_testimonial_info['rate_giver_organization'];
	   } else {
	   	$organization = '';
	   } if (!empty($ifinger_testimonial_info['rate_giver_organization_link'])) {
	      $organization_link = $ifinger_testimonial_info['rate_giver_organization_link'];
	   } else {
	      $organization_link = '';
	   } if (!empty($ifinger_testimonial_info['rewiew_setting'])) {
	      $rewiew_setting = $ifinger_testimonial_info['rewiew_setting'];
	   } else {
	      $rewiew_setting = '';
	   }

  	?>
   <!-- <div class="col-xl-4"> -->
      <div class="single-testimonial">
         <div class="testi-content">
            <div class="testimonial-top-wrap">
               <h2><?php the_title(); ?></h2>
               <?php if( $review_disable != 'yes' ){ ?>
               <div class="testi-rating">
                  <?php 
		   			   for ($i=0; $i <=4 ; $i++) {
		   			   	if ($i < $rewiew_setting) {
		   			      	$full = 'fas';
		   			    	} else {
		   			      	$full = 'far';
		   			    	}
		   			   	echo "<i class=\"$full fa-star\"></i>";
		   			   }
		   			?>
               </div>
               <?php } ?>
            </div>
            <?php the_content(); ?>
         </div>
         <div class="testi-avatar">
            <div class="avatar-img">
               <?php the_post_thumbnail(); ?>
            </div>
            <div class="testi-avatar-info">
               <h6><?php echo esc_html( $rate_giver_name ); ?></h6>
               <span><?php echo esc_html( $designation ); ?> 
               	<a href="<?php echo esc_url( $organization_link ); ?>"><?php echo esc_html( $organization ); ?></a>
               </span>
            </div>
         </div>
      </div>
   <!-- </div> -->

   <?php endwhile; wp_reset_postdata(); ?>
</div>

<?php } else { 

	$testi_grid = $settings['grid_columns'];

?>

<div class="row testimonial-grid">
	<?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 

	   $ifinger_testimonial_info = get_post_meta( get_the_ID(), '_ifinger_testimonial', true );

	   if (!empty($ifinger_testimonial_info['rate_giver_name'])) {
	      $rate_giver_name = $ifinger_testimonial_info['rate_giver_name'];
	   } else {
	      $rate_giver_name = '';
	   } if (!empty($ifinger_testimonial_info['rate_giver_designation'])) {
	      $designation = $ifinger_testimonial_info['rate_giver_designation'];
	   } else {
	      $designation = '';
	   } if (!empty($ifinger_testimonial_info['rate_giver_organization'])) {
	      $organization = $ifinger_testimonial_info['rate_giver_organization'];
	   } else {
	   	$organization = '';
	   } if (!empty($ifinger_testimonial_info['rate_giver_organization_link'])) {
	      $organization_link = $ifinger_testimonial_info['rate_giver_organization_link'];
	   } else {
	      $organization_link = '';
	   } if (!empty($ifinger_testimonial_info['rewiew_setting'])) {
	      $rewiew_setting = $ifinger_testimonial_info['rewiew_setting'];
	   } else {
	      $rewiew_setting = '';
	   }

  	?>
   <div class="col-lg-<?php echo esc_attr( $testi_grid ); ?> col-md-6">
      <div class="single-testimonial">
         <div class="testi-content">
            <div class="testimonial-top-wrap">
               <h2><?php the_title(); ?></h2>
               <?php if( $review_disable != 'yes' ){ ?>
               <div class="testi-rating">
                  <?php 
		   			   for ($i=0; $i <=4 ; $i++) {
		   			   	if ($i < $rewiew_setting) {
		   			      	$full = 'fas';
		   			    	} else {
		   			      	$full = 'far';
		   			    	}
		   			   	echo "<i class=\"$full fa-star\"></i>";
		   			   }
		   			?>
               </div>
               <?php } ?>
            </div>
            <?php the_content(); ?>
         </div>
         <div class="testi-avatar">
            <div class="avatar-img">
               <?php the_post_thumbnail(); ?>
            </div>
            <div class="testi-avatar-info">
               <h6><?php echo esc_html( $rate_giver_name ); ?></h6>
               <span>
               	<?php echo esc_html( $designation ); ?> 
               	<a href="<?php echo esc_url( $organization_link ); ?>"><?php echo esc_html( $organization ); ?></a>
               </span>
            </div>
         </div>
      </div>
   </div>
   <?php endwhile; wp_reset_postdata(); ?>
</div>

<?php } endif;   //main if end

	}
}