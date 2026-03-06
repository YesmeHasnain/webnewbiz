<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Photo_Gallery extends Widget_Base {

	public function get_name() {
		return 'photo-gallery';
	}

	public function get_title() {
		return 'Photo Gallery';   // title to show on geoport
	}

	public function get_icon() {
		return 'fal fa-images';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Photo Gallery', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'gallery',
			[
				'label' => __( 'Add Images', 'geoport' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
			]
		);
		$this->add_control(
			'gallery_columns',
			[
				'label' => __( 'Columns', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'6' => __( '2', 'geoport' ),
					'4' => __( '3', 'geoport' ),
					'3' => __( '4', 'geoport' ),
					'2' => __( '6', 'geoport' ),
				],
			]
		);
		
		$this->end_controls_section();

		//End  of a control box

		/* = Add Item Styling
		========================================*/

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'btn_border_radius',
			[
				'label' => __( 'Border radius', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .video-thumb a, .pluse-animation .popup-video::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
	//end of control box 

	protected function render() {				//to show on the fontend 
		
		$settings = $this->get_settings_for_display();

		$gallery_columns = $settings['gallery_columns'];

	?>
		<div class="row">
			<?php foreach ( $settings['gallery'] as $image ) { ?>
            <div class="col-lg-<?php echo esc_attr( $gallery_columns ); ?> col-md-6 custom-gallery-p">
               <div class="single-gallery-thumb fix mb-10">
                  <a href="<?php echo esc_url($image['url']); ?>" class="geoport-popup-image"><img src="<?php echo esc_url($image['url']); ?>" alt="<?php esc_attr_e( 'Gallery Image' ); ?>"></a>
               </div>
            </div>
        	<?php } ?>
        </div>
	<?php
	}
}