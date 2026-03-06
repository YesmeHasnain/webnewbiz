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

class Section_Services extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'section-service';
	}

	public function get_title() {
		return 'Service';   // title to show on elementor
	}

	public function get_icon() {
		return 'fal fa-briefcase'; //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Service posts', 'geoport' ),   //section name for controler view
			]
		);
		$this->add_control(
			'post_number',
			[
				'label' => esc_html__( 'Number of Item', 'geoport' ),
				'description' => esc_html__( 'Give -1 for all post', 'geoport' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
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
			'hide_post_cats',
			[
				'label' => __( 'Hide Post category', 'geoport' ),
				'type'  => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'geoport' ),
				'label_off' => __( 'Hide', 'geoport' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'grid_columns',
			[
			    'label'       => __( 'Service columns', 'geoport' ),
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
	}

	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();
		$post_number = $settings['post_number'];
		$content_excerpt = $settings['post_excerpt'];
		$post_orderby = $settings['post_orderby'];
		$post_order = $settings['post_order'];
		$grid_columns = $settings['grid_columns'];
		$hide_post_cats = $settings['hide_post_cats'];

		if ($grid_columns == '6') {
		  $crop_img = 'geoport-770-460';
		} elseif ($grid_columns == '4') {
		  $crop_img = 'geoport-770-460';
		} elseif ($grid_columns == '3') {
		  $crop_img = 'geoport-770-460';
		} else {
		  $crop_img = 'full';
		}

		$grid_query= null;
	   $args = array(
	      'post_type'      => 'service',
	      'post_status'    => 'publish',
	      'orderby' => $post_orderby,
	      'order' => $post_order,
	      'posts_per_page' => $post_number,
	   );
		 
		$grid_query = new \WP_Query( $args );

		if ( $grid_query->have_posts() ) : 
		?>

		<div class="row">
			<?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; ?>
			<div class="col-lg-<?php echo esc_attr( $grid_columns ); ?> col-md-6 service-shortcode">
			   <div class="single-video-services">
			      <div class="video-services-thumb">
			         <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $crop_img ); ?></a>
			      </div>
			      <div class="video-services-content">
			     		<?php 
		            $terms = wp_get_post_terms( $post->ID, 'service_category');
		            if (!empty($terms) || !empty($event_time)) { ?>
			         <div class="video-services-tag">
			            <?php 
		              		foreach ($terms as $term) {
							   echo '<a href="'.get_term_link($term->slug, 'service_category').'">'.$term->name.'</a>';
							}
		               ?>
			         </div>
			      	<?php } ?>
			         <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			         <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
			      </div>
			   </div>
			</div>
		   <?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php endif;   //main if end
	}
}