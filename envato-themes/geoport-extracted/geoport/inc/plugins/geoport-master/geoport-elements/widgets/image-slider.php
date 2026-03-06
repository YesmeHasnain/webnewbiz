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

class Image_Slider extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'image-slider';
	}

	public function get_title() {
		return 'Image Slider';   // title to show on elementor
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
				'label' => esc_html__( 'Image Slider', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'image_slide_items',
			[
				'label' => __( 'Slider Items', 'geoport' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'slide_image',
						'label' => __( 'Image', 'geoport' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'label_block' => true,
					],
					[
						'name' => 'slide_title',
						'label' => __( 'Title', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'Slide Title Here',
						'label_block' => true,
					],
					[
						'name' => 'link_text',
						'label' => __( 'Link Text', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
					[
						'name' => 'link',
						'label' => __( 'Link', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
					[
						'name' => 'icon',
						'label' => __( 'Icon', 'geoport' ),
						'type' => Controls_Manager::ICON,
						'label_block' => true,
					],
				],
				'title_field' => '{{{ slide_title }}}',
			]
		);

		$this->add_control(
			'slider',
			[
			    'label'   => __( 'Slider', 'geoport' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => 'no',
			    'options' => [
			     	'no'  => __( 'No', 'geoport' ),
			     	'yes' => __( 'Yes', 'geoport' ),
			    ],
			]
		);
		$this->add_control(
			'show_item',
			[
			   'label'       => __( 'Show on large device', 'geoport' ),
			   'type' => Controls_Manager::SELECT,
			   'default' => '2',
			   'options' => [
			     	'1' => __( '1', 'geoport' ),
			     	'2' => __( '2', 'geoport' ),
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
			'slider-large-cp',
			[
			   'label'   => __( 'Large Center Padding', 'geoport' ),
			   'type'    => Controls_Manager::TEXT,
			   'default' => '180px',
			   'condition' => [
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_desktop',
			[
			   'label'       => __( 'Show on desktop', 'geoport' ),
			   'type' => Controls_Manager::SELECT,
			   'default' => '2',
			   'options' => [
			     	'1'  => __( '1', 'geoport' ),
			     	'2'  => __( '2', 'geoport' ),
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
			'slider-desktop-cp',
			[
			   'label'   => __( 'Desktop Center Padding', 'geoport' ),
			   'type'    => Controls_Manager::TEXT,
			   'default' => '100px',
			   'condition' => [
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_tablet',
			[
			    'label'       => __( 'Show on Tablet', 'geoport' ),
			    'type' => Controls_Manager::SELECT,
			    'default' => '1',
			    'options' => [
			     	'1'  => __( '1', 'geoport' ),
			     	'2'  => __( '2', 'geoport' ),
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
			'slider-tablet-cp',
			[
			   'label'   => __( 'Tablet Center Padding', 'geoport' ),
			   'type'    => Controls_Manager::TEXT,
			   'default' => '100px',
			   'condition' => [
					'slider' => 'yes',
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
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider-tab-cp',
			[
			   'label'   => __( 'Tab Center Padding', 'geoport' ),
			   'type'    => Controls_Manager::TEXT,
			   'default' => '80px',
			   'condition' => [
					'slider' => 'yes',
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
					'slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider-mobile-cp',
			[
			   'label'   => __( 'Mobile Center Padding', 'geoport' ),
			   'type'    => Controls_Manager::TEXT,
			   'default' => '0px',
			   'condition' => [
					'slider' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}


	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();

		$image_slide_items = $settings['image_slide_items'];

		$slider = $settings['slider'];
		$show_item = $settings['show_item'];
		$show_desktop = $settings['show_desktop'];
		$show_tablet = $settings['show_tablet'];
		$show_tab = $settings['show_tab'];
		$show_phone = $settings['show_phone'];
		$slider_large_cp = $settings['slider-large-cp'];
		$slider_desktop_cp = $settings['slider-desktop-cp'];
		$slider_tablet_cp = $settings['slider-tablet-cp'];
		$slider_tab_cp = $settings['slider-tab-cp'];
		$slider_mobile_cp = $settings['slider-mobile-cp'];

	if ( $slider == 'yes' ) {

		$e_uniqid     = uniqid();
?>

<script type="text/javascript">
    jQuery(document).ready(function(){
	
		jQuery(<?php echo "'.images-slider-$e_uniqid'"; ?>).slick({
			dots: false,
			infinite: true,
			speed: 1000,
			arrows: false,
			autoplay: true,
			prevArrow: '<button type="button" class="slick-prev"><i class="far fa-arrow-left"></i></button>',
			nextArrow: '<button type="button" class="slick-next"><i class="far fa-arrow-right"></i></button>',
			centerMode: true,
			centerPadding: "<?php echo esc_attr( $slider_large_cp ); ?>",
			slidesToShow: <?php echo esc_attr( $show_item ); ?>,
			slidesToScroll: 1,
			responsive: [
			    {
			      breakpoint: 1500,
			      settings: {
			        	slidesToShow: <?php echo esc_attr( $show_desktop ); ?>,
						slidesToScroll: 1,
						centerPadding: "<?php echo esc_attr( $slider_desktop_cp ); ?>",
			        	infinite: true,
			      }
			    },
			    {
			      breakpoint: 1200,
			      settings: {
				      slidesToShow: <?php echo esc_attr( $show_tablet ); ?>,
						slidesToScroll: 1,
						centerPadding: "<?php echo esc_attr( $slider_tablet_cp ); ?>",
			      	infinite: true,
			      }
			    },
			    {
			      breakpoint: 1025,
			      settings: {
				      slidesToShow: <?php echo esc_attr( $show_tab ); ?>,
						slidesToScroll: 1,
						centerPadding: "<?php echo esc_attr( $slider_tab_cp ); ?>",
			      }
			    },
			    {
			      breakpoint: 767,
			      settings: {
				      slidesToShow: <?php echo esc_attr( $show_phone ); ?>,
						slidesToScroll: 1,
						centerPadding: "<?php echo esc_attr( $slider_mobile_cp ); ?>",
				      arrows: false,
			      }
			    },
			    {
			      breakpoint: 575,
			      settings: {
				      slidesToShow: <?php echo esc_attr( $show_phone ); ?>,
						slidesToScroll: 1,
						centerPadding: "<?php echo esc_attr( $slider_mobile_cp ); ?>",
				      arrows: false,
			      }
			    },
			  ]
		});

    });
</script> 

<div class="row <?php echo "images-slider-$e_uniqid"; ?> image-slider-wrap">
	<?php 
		if ( is_array($image_slide_items) ) {
			foreach ($image_slide_items as $key => $value) {
  	?>
    <div class="single-services-carousel">
        <div class="services-carousel-thumb">
        	<?php if ( !empty( $value['slide_image'] ) ) { ?>
            	<img src="<?php echo esc_url( $value['slide_image']['url'] ); ?>" alt="img">
        	<?php } ?>
            <div class="scarousel-overlay">
            	<?php if ( !empty( $value['icon'] ) ) { ?>
                <div class="services-carousel-icon">
                    <i class="<?php echo esc_attr( $value['icon'] ); ?>"></i>
                </div>
                <?php } ?>
                <div class="scarousel-content">
                    <h4><a href="<?php echo esc_url( $value['link'] ); ?>"><?php echo esc_html( $value['slide_title'] ); ?></a></h4>
                    <a href="<?php echo esc_url( $value['link'] ); ?>"><i class="far fa-arrow-right"></i><?php echo esc_html( $value['link_text'] ); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php } }  ?>
</div>

<?php } else { ?>

<div class="row image-grid">
	<?php 
		if ( is_array( $image_slide_items ) ) {

			$slide_items_count = count( $image_slide_items );
			
			if ( $slide_items_count < 2 ) {
				$cols = '12';
			} elseif ( $slide_items_count < 3 ) {
				$cols = '6';
			} elseif ( $slide_items_count < 4 ) {
				$cols = '4';
			} elseif ( $slide_items_count < 5 ) {
				$cols = '3';
			} elseif ( $slide_items_count < 6 ) {
				$cols = '2';
			} elseif ( $slide_items_count < 7 ) {
				$cols = '2';
			} else {
				$cols = '3';
			}

			foreach ( $image_slide_items as $key => $value ) {
  	?>
  	<div class="col-md-<?php echo esc_attr( $cols ); ?>">
	    <div class="single-services-carousel">
	        <div class="services-carousel-thumb">
	        	<?php if ( !empty( $value['slide_image'] ) ) { ?>
	            	<img src="<?php echo esc_url( $value['slide_image']['url'] ); ?>" alt="img">
	        	<?php } ?>
	            <div class="scarousel-overlay">
	            	<?php if ( !empty( $value['icon'] ) ) { ?>
	                <div class="services-carousel-icon">
	                    <i class="<?php echo esc_attr( $value['icon'] ); ?>"></i>
	                </div>
	                <?php } ?>
	                <div class="scarousel-content">
	                    <h4><a href="<?php echo esc_url( $value['link'] ); ?>"><?php echo esc_html( $value['slide_title'] ); ?></a></h4>
	                    <a href="<?php echo esc_url( $value['link'] ); ?>"><i class="far fa-arrow-right"></i><?php echo esc_html( $value['link_text'] ); ?></a>
	                </div>
	            </div>
	        </div>
	    </div>
    </div>
    <?php } }  ?>
</div>

<?php }

	}
}