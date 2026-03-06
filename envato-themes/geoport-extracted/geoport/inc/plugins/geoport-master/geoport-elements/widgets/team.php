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

class Our_Team extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'section-team';
	}

	public function get_title() {
		return 'Team';   // title to show on elementor
	}

	public function get_icon() {
		return 'fal fa-users-medical';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Team Grid', 'geoport' ),   //section name for controler view
			]
		);

	
		$this->add_control(
			'post_number',
			[
				'label' => esc_html__( 'Number of team', 'geoport' ),
				'description' => esc_html__( 'Give -1 for all post', 'geoport' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'team_orderby',
			[
				'label' => __( 'Order by', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' =>ifinger_post_orderby_options(),
			]
		);
		$this->add_control(
			'team_order',
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
			'team_slider_switch',
			[
				'label' => __( 'Team Switch', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => __( 'Team Grid', 'geoport' ),
					'2' => __( 'Team Slider', 'geoport' ),
				],
			]
		);
		$this->add_control(
			'team_columns',
			[
				'label' => __( 'Columns', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'6' => __( '2', 'geoport' ),
					'4' => __( '3', 'geoport' ),
					'3' => __( '4', 'geoport' ),
					'2' => __( '6', 'geoport' ),
				],
				'condition' => [
					'team_slider_switch'  => '1',
				],
			]
		);

		$this->add_control(
			'slider_navi',
			[
			   'label'   => __( 'Slider Navigation', 'geoport' ),
			   'type'    => Controls_Manager::SELECT,
			   'default' => 'true',
			   'options' => [
			   	'false'  => __( 'No', 'geoport' ),
			   	'true'   => __( 'Yes', 'geoport' ),
			   ],
			   'condition' => [
					'team_slider_switch'  => '2',
				],
			]
		);
		$this->add_control(
			'show_item',
			[
			    'label'       => __( 'Show on large device', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => '3',
			    'options' => [
			     	'1'  => __( '1', 'geoport' ),
			     	'2'  => __( '2', 'geoport' ),
			     	'3' => __( '3', 'geoport' ),
			     	'4' => __( '4', 'geoport' ),
			     	'6' => __( '6', 'geoport' ),
			    ],
			    'condition' => [
					'team_slider_switch' => '2',
				],
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
					'team_slider_switch' => '2',
				],
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
			    ],
			    'condition' => [
					'team_slider_switch' => '2',
				],
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
					'team_slider_switch' => '2',
				],
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
					'team_slider_switch' => '2',
				],
			]
		);

		$this->end_controls_section();
	}


	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();

		$post_number = $settings['post_number'];
		$team_orderby = $settings['team_orderby'];
		$team_order = $settings['team_order'];
		$team_slider_switch = $settings['team_slider_switch'];

		$team_columns = $settings['team_columns'];

		$slider_navi = $settings['slider_navi'];
		$show_item = $settings['show_item'];
		$show_desktop = $settings['show_desktop'];
		$show_tablet = $settings['show_tablet'];
		$show_tab = $settings['show_tab'];
		$show_phone = $settings['show_phone'];

		$e_uniqid     = uniqid();

		if ( $team_slider_switch == 1 ) {
			$class = 'team-grid';
		} else {
			$class = 'team-slider-'.$e_uniqid;			
		}

		$grid_query= null;
	   $args = array(
	      'post_type'      => 'team',
	      'post_status'    => 'publish',
	      'orderby' => $team_orderby,
	      'order' => $team_order,
	      'posts_per_page' => $post_number,
	   );
		$grid_query = new \WP_Query( $args );

		if ( $grid_query->have_posts() ) : 
			if ( $team_slider_switch = 2 ) {
?>

<script type="text/javascript">
   jQuery(document).ready(function(){
		// testimonial-active
		jQuery(<?php echo "'.team-slider-$e_uniqid'"; ?>).slick({

			infinite: true,
			speed: 1000,
			arrows: <?php echo esc_attr( $slider_navi ); ?>,
			autoplay: true,
			prevArrow: '<button type="button" class="slick-prev"><i class="far fa-arrow-left"></i></button>',
			nextArrow: '<button type="button" class="slick-next"><i class="far fa-arrow-right"></i></button>',
			slidesToShow: <?php echo esc_attr( $show_item ); ?>,
			slidesToScroll: 2,
			responsive: [
				{
				   breakpoint: 1200,
				   settings: {
				   	slidesToShow: <?php echo esc_attr( $show_desktop ); ?>,
						slidesToScroll: 2,
				     	infinite: true,
				   }
				},
				{
				   breakpoint: 992,
				   settings: {
					   slidesToShow: <?php echo esc_attr( $show_tablet ); ?>,
						slidesToScroll: 2,
						arrows: false,
				   }
				},
				{
				   breakpoint: 767,
				   settings: {
					   slidesToShow: <?php echo esc_attr( $show_tab ); ?>,
						slidesToScroll: 1,
					   arrows: false,
				   }
				},
				{
				   breakpoint: 575,
				   settings: {
					   slidesToShow: <?php echo esc_attr( $show_phone ); ?>,
						slidesToScroll: 1,
					   arrows: false,
				   }
				},
			]
		});

   });
</script> 

<?php } ?>

<div class="team-area">
	<div class="row team-active <?php echo esc_attr( $class ); ?>">
		<?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 
		   $geoport_team_info = get_post_meta( get_the_ID(), '_geoport_team', true );
		   if ( !empty($geoport_team_info['team_designation']) ){
		      $team_designation = $geoport_team_info['team_designation'];
		   } else {
		      $team_designation = '';
		   }
	  	?>
	   <div class="col-lg-<?php echo esc_attr( $team_columns ); ?> col-md-6">
         <div class="single-team">
         	<?php if(has_post_thumbnail()) : ?>
           	<div class="team-thumb">
               <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
           	</div>
           	<?php endif; ?>
           	<div class="team-overlay-content">
               <span><?php echo $team_designation; ?></span>
               <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
           	</div>
       	</div>
	   </div>
	   <?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>
<?php endif;   //main if end
	}
}