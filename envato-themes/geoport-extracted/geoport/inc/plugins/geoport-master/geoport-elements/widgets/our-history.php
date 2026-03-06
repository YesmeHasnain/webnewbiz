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

class Our_History extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'section-history';
	}

	public function get_title() {
		return 'Our History';   // title to show on elementor
	}

	public function get_icon() {
		return 'fal fa-route'; // eicon-posts-ticker-> eicon ow asche icon to show on elelmentor <i class="fal fa-route"></i>
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
				'label' => esc_html__( 'Our History', 'geoport' ),   //section name for controler view
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
			'journey_order',
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
		$this->end_controls_section();
	}


	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();

		$post_number = $settings['post_number'];
		$journey_order = $settings['journey_order'];

		$grid_query= null;
	    $args = array(
	       'post_type'      => 'journey',
	       'post_status'    => 'publish',
	       'order' => $journey_order,
	       'posts_per_page' => $post_number,
	    );
		 
		$grid_query = new \WP_Query( $args );

		if ( $grid_query->have_posts() ) : 
?>

<div class="history-list-wrap">
   <div class="history-list-bar"></div>
		<?php 
			$i = 0;
			while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 
		   $geoport_journey_info = get_post_meta( get_the_ID(), '_geoport_journey', true );
		   if ( !empty($geoport_journey_info['journey_date']) ){
		      $journey_date = $geoport_journey_info['journey_date'];
		   } else {
		      $journey_date = '';
		   }
		   $i++;
		   if ( $i % 2 == 0 ) {
		   	$order_class = '';
		   	$img_anim = 'fadeInLeft';
		   	$txt_anim = 'fadeInRight';
		   } else {
		   	$img_anim = 'fadeInRight';
		   	$txt_anim = 'fadeInLeft';
		   	$order_class = 'order-0 order-md-2';
		   }

	  	?>
	   <div class="single-history-wrap">
          <div class="row align-items-center justify-content-center">
              <div class="col-md-6 <?php echo esc_attr( $order_class ); ?>">
                  <div class="history-list-img wow <?php echo esc_attr( $img_anim ); ?>" data-wow-delay="0.2s">
                     <?php the_post_thumbnail(); ?>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="history-list-content wow <?php echo esc_attr( $txt_anim ); ?>" data-wow-delay="0.2s">
                     <span><?php echo $journey_date; ?></span>
                     <h2><?php the_title(); ?></h2>
                     <?php the_content(); ?>
                  </div>
              </div>
          </div>
      </div>
	   <?php endwhile; wp_reset_postdata(); ?>
</div>

<?php endif;   //main if end
	}
}