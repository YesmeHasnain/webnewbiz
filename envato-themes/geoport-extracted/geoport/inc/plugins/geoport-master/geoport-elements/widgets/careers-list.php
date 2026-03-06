<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Careers_List extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'job-list';
	}

	public function get_title() {
		return 'Job List';   // title to show on elementor
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'geoport-elements' ];    // category of the widget
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
				'label' => esc_html__( 'Job List', 'geoport' ),   //section name for controler view
			]
		);
		$this->add_control(
			'post_number',
			[
				'label' => esc_html__( 'Number of List', 'geoport' ),
				'description' => esc_html__( 'Give -1 for all post', 'geoport' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'job_applybutton',
			[
				'label' => __( 'Apply Button', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'yes' => __( 'Show', 'geoport' ),
					'no' => __( 'Hide', 'geoport' ),
				],
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		/* = Add Item Styling
		========================================*/

		/* = Table Header Styling
		========================================*/
		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Table Item Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'table_header_text_color',
			[
				'label' => __( 'Table Item Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001447',
				'selectors' => [
					'{{WRAPPER}} .job_board_list_content_area .job_item_list' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		/* = Table Body Styling
		========================================*/
		$this->start_controls_section(
			'table_body_style',
			[
				'label' => esc_html__( 'Table Body Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'table_body_title_color',
			[
				'label' => __( 'Title Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .job_board_list_content_area .job_item_list .job_item.title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'table_body_title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .job_board_list_content_area .job_item_list .job_item.title',
			]
		);
		$this->add_control(
			'table_body_text_color',
			[
				'label' => __( 'Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .job_board_list_content_area .job_item_list .job_item' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tbody_text_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .job_board_list_content_area .job_item_list .job_item',
			]
		);
		$this->add_control(
			'table_body_btnbg_color',
			[
				'label' => __( 'Button Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .job_item.apply-btn a' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'table_body_btn_color',
			[
				'label' => __( 'Button Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001447',
				'selectors' => [
					'{{WRAPPER}} .job_item.apply-btn a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'table_body_btn_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .job_item.apply-btn a',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();

		$post_number = $settings['post_number'];
		$job_applybutton = $settings['job_applybutton'];

		$job_grid_query= null;
		$args = array(
		  'post_type'      => 'geoport_job_list',
		  'post_status'    => 'publish',
		  'posts_per_page' => $post_number,
		  'order' => 'ASC',
		);

		$job_grid_query = new \WP_Query( $args );

		if ( $job_grid_query->have_posts() ) : 
		?>
		<div class="row">
		    <div class="col-12">
		    	<div class="job_board_list_area">
					<div class="job_board_list_content_area">
		        		<?php 
		        			while ( $job_grid_query->have_posts() ) : $job_grid_query->the_post(); global $post; 

						   $geoport_joblist = get_post_meta( get_the_ID(), '_geoport_joblist', true );
						   if ( !empty($geoport_joblist['job_list']) ){
						    	$job_list = $geoport_joblist['job_list'];
						   } else {
						    	$job_list = '';
						   }
					  	?>
					  	<div class="job_item_list">
			            	<?php if (is_array($job_list)) {
				            	foreach ($job_list as $key => $value) { ?>
			                        <div class="job_item title"><?php the_title(); ?></div>
			                        <div class="job_item"><?php echo esc_html( $value['text2'] ); ?></div>
			                        <div class="job_item"><?php echo esc_html( $value['text3'] ); ?></div>
			                        <div class="job_item"><?php echo esc_html( $value['text4'] ); ?></div>
			                        <?php if( $job_applybutton == 'yes' ){ ?>
				                        <div class="job_item apply-btn">
				                        	<?php if( !empty( $value['btn_url'] ) ){ ?>
				                        		<a href="<?php echo esc_url( $value['btn_url'] ); ?>"><?php echo esc_html( $value['btn_txt'] ); ?></a>
				                        	<?php } ?>
				                        </div>
			                        <?php } ?>
				            	<?php } ?>
			            	<?php } ?>
		            	</div>
			            <?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif;   //main if end
	}
}