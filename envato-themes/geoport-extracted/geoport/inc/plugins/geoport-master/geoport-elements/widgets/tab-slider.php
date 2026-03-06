<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Repeater;
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

class Tab_Slider extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'tab-slider';
	}

	public function get_title() {
		return 'Tab Slider';   // title to show on elementor
	}

	public function get_icon() {
		return 'fal fa-sliders-h';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Tab Image Slider', 'geoport' ),   //section name for controler view
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'slide_title',
			[
				'label' => esc_html__( 'Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Tab Slider Title', 'geoport' ),
			]
		);

		$repeater->add_control(
			'slide_image',
			[
				'label' => esc_html__( 'Upload Image', 'geoport' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'icon',
			[
				'name' => 'icon',
				'label' => __( 'Icon', 'geoport' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
			]
		);

		$this->add_control(
			'tab_slide_items',
			[
				'label' => __( 'Tab Slider Items', 'landiox' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ slide_title }}}',
			]
		);

		$this->add_control(
			'show_item',
			[
			    'label'       => __( 'Show tab item', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => '4',
			    'options' => [
			     	'2' => __( '2', 'geoport' ),
			     	'3' => __( '3', 'geoport' ),
			     	'4' => __( '4', 'geoport' ),
			     	'5' => __( '5', 'geoport' ),
			     	'6' => __( '6', 'geoport' ),
			    ],
			]
		);

		$this->add_control(
			'autoplay',
			[
			    'label'       => __( 'Auto Play', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'true',
			    'options' => [
			     	'true' => __( 'Yes', 'geoport' ),
			     	'false' => __( 'No', 'geoport' ),
			    ],
			]
		);

		$this->end_controls_section();


		/* = Add Item Styling
		========================================*/

		$this->start_controls_section(
			'tab_style',
			[
				'label' => esc_html__( 'Tab Style Settings', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_bg_color',
			[
				'label' => __( 'Tab Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgb(0, 29, 103, 0.8)',
				'selectors' => [
					'{{WRAPPER}} .single-portfolio-nav h5' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-portfolio-nav h5',
			]
		);

		$this->add_control(
			'icon_heading',
			[
				'label' => __( 'Icon Settings', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .single-portfolio-nav h5 i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .single-portfolio-nav h5 i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		/* = Add Item Styling
		========================================*/

		$this->start_controls_section(
			'activ_tab_style',
			[
				'label' => esc_html__( 'Active Tab Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'active_bg_color',
			[
				'label' => __( 'Active Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .single-portfolio-nav.slick-slide.slick-current.slick-center h5' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_font_color',
			[
				'label' => __( 'Active Font Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .single-portfolio-nav.slick-slide.slick-current.slick-center h5' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-portfolio-nav.slick-slide.slick-current.slick-center h5 i' => 'color: {{VALUE}};',
				],
			]
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tab_slide_items = $settings['tab_slide_items'];
		$show_item       = $settings['show_item'];
		$autoplay        = $settings['autoplay'];
		?>

		<script type="text/javascript">
		    jQuery(document).ready(function(){
				// slide-portfolio
				jQuery( '.slide-portfolio-active' ).slick({
				  	slidesToShow: 1,
				  	slidesToScroll: 1,
				  	arrows: false,
				  	fade: true,
				  	autoplay: <?php echo esc_attr( $autoplay ); ?>,
		    		autoplaySpeed: 2500,
		    		speed: 1500,
				  	asNavFor: '.slide-portfolio-nav'
				});

				jQuery( '.slide-portfolio-nav' ).slick({
				   slidesToShow: <?php echo esc_attr( $show_item ); ?>,
				   slidesToScroll: 1,
				   asNavFor: '.slide-portfolio-active',
				   dots: false,
				   arrows: false,
				   centerMode: true,
				   centerPadding: '0px',
				   focusOnSelect: true,
					responsive: [
						{
							breakpoint: 1500,
							settings: {
								slidesToShow: 3,
								slidesToScroll: 1,
								infinite: true,
							}
						},
						{
							breakpoint: 1200,
							settings: {
								slidesToShow: 3,
								slidesToScroll: 1,
								infinite: true,
							}
						},
						{
							breakpoint: 992,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2,
							}
						},
						{
							breakpoint: 767,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1,
								arrows: false,
							}
						},
						{
							breakpoint: 575,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1,
								arrows: false,
							}
						},
					]
				});

		    });
		</script> 

		<!-- slide-portfolio -->
		<div class="slide-portfolio position-relative">
			<?php if ( is_array( $tab_slide_items ) ) { ?>
		      <div class="slide-portfolio-active">
		      	<?php foreach ( $tab_slide_items as $key => $value ) { 
		      		$image = $value['slide_image']['url'];
		      	?>
		         <div class="single-portfolio">
		         	<?php if( !empty( $image ) ){ ?>
		            	<img src="<?php echo esc_url( $image ); ?>" alt="img">
		         	<?php } ?>
		         </div>
		         <?php } ?>
		      </div>
			<?php } if ( is_array( $tab_slide_items ) ) { ?>
		   <div class="slide-portfolio-nav">
		   	<?php foreach ( $tab_slide_items as $key => $value ) { 
		   		$icon = $value['icon'];
		   		$title = $value['slide_title'];
		   	?>
		      <div class="single-portfolio-nav">
		         <h5><i class="<?php echo esc_attr( $icon ); ?>"></i><?php echo esc_html( $title ); ?></h5>
		      </div>
		      <?php } ?>
		   </div>
		   <?php } ?>
		</div>
		<!-- slide-portfolio-end -->
		<?php
	}
}