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

class Form_Tab extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'form-tab';
	}

	public function get_title() {
		return 'Form Tab';   // title to show on elementor
	}

	public function get_icon() {
		return 'eicon-product-tabs';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Form Tab', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'form_tab_version',
			[
				'label' => __( 'Form Tab Version', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1'  => __( 'Style 1', 'geoport' ),
					'2'  => __( 'Style 2', 'geoport' ),
				],
			]
		);

		$this->add_control(
			'tab_items',
			[
				'label' => __( 'Tab Items', 'geoport' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'tab_title_icon',
						'label' => __( 'Title Icon', 'geoport' ),
						'type' => Controls_Manager::ICON,
						'default' => 'Slide Title Here',
						'label_block' => true,
					],
					[
						'name' => 'tab_title',
						'label' => __( 'Title', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'Tab Title',
						'label_block' => true,
					],
					[
						'name' => 'tab_form_title',
						'label' => __( 'Form Title', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'Tab Form Title',
						'label_block' => true,
					],
					[
						'name' => 'tab_form_subtitle',
						'label' => __( 'Form Sub Title', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'Tab Form Sub Title',
						'label_block' => true,
					],
					[
						'name' => 'tab_text',
						'label' => __( 'Form Shortcode', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->add_control(
			'form_tab_side_img',
			[
				'label' => __( 'Tab Side Image', 'geoport' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'label_block' => true,
				'condition' => [
					'form_tab_version' => '2',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_contact_tabs',
			[
				'label' => esc_html__( 'Tab Style Settings', 'geoport' ),   //section name for controler view
			    'condition' => [
					'form_tab_version' => '2',
				],				
			]
		);

		$this->add_control(
			'tab_btn_margin_area',
			[
			    'label'       => __( 'Buttom Margin Top', 'chariton' ),
			    'type' => Controls_Manager::TEXT,
			    'default' => '-70px',
				'selectors' => [
					'{{WRAPPER}} .third-cta-wrap .nav-tabs' => 'margin-top: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_btn_bg_color',
			[
				'label' => __( 'Button Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001241',
				'selectors' => [
					'{{WRAPPER}} .third-cta-wrap .nav-tabs .nav-link' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_btn_text_color',
			[
				'label' => __( 'Button Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .third-cta-wrap .nav-tabs .nav-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_btn_active_bg_color',
			[
				'label' => __( 'Active Button Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .third-cta-wrap .nav-tabs .nav-item a.nav-link.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_btn_active_text_color',
			[
				'label' => __( 'Active Button Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .third-cta-wrap .nav-tabs .nav-item a.nav-link.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_btn_request_bg_color',
			[
				'label' => __( 'Request Button Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .third-cta-form .btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_btn_request_text_color',
			[
				'label' => __( 'Request Button Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .third-cta-form .btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/* = Form Tab Styling
		========================================*/

		$this->start_controls_section(
			'form_tab_style',
			[
				'label' => esc_html__( 'Form Tab Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		/* = Title 1 Styling
		========================================*/
		$this->add_control(
			'title1_text_color',
			[
				'label' => __( 'Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .section-title h2 span.tl1' => 'color: {{VALUE}};',
				],
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
		$this->end_controls_section();

	}


	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();
		$tab_items = $settings['tab_items'];
		$form_tab_version = $settings['form_tab_version'];

		if ( $form_tab_version == 1 ) {
?>

<div class="form-tab-wrap">
	<?php if ( is_array($tab_items) ) { ?>
	<ul class="nav nav-tabs cta-nav-tabs" id="myTab" role="tablist">
		<?php 
			$i = 0;
			foreach ($tab_items as $key => $value) {
				$i++;
				$tab_title_icon = $value['tab_title_icon'];
				$tab_title = $value['tab_title'];
				
				$tab_id = "abc-$i";

   			if ( $i == 1) {
   				$ac = 'active';
   				$as = 'true';
   			} else {
   				$ac = '';
   				$as = 'false';
   			}
	  	?>
	   <li class="nav-item">
	      <a class="nav-link <?php echo esc_attr( $ac ); ?>" id="<?php echo esc_attr( $tab_id ); ?>-tab" data-toggle="tab" href="#<?php echo esc_attr( $tab_id ); ?>" role="tab" aria-controls="<?php echo esc_attr( $tab_id ); ?>" aria-selected="<?php echo esc_attr( $as ); ?>">
	      	<i class="<?php echo esc_attr( $tab_title_icon ); ?>"></i>
	      	<?php echo esc_html( $tab_title ); ?>
	      </a>
	   </li>
	   <?php } ?>
	</ul>
	<?php } if ( is_array( $tab_items ) ) { ?>
	<div class="tab-content" id="myTabContent">
		<?php 
			$i = 0;
			foreach ($tab_items as $key => $value) {
				$i++;
				$tab_form_title = $value['tab_form_title'];
				$tab_form_subtitle = $value['tab_form_subtitle'];
				$tab_text = $value['tab_text'];
				$tab_title = $value['tab_title'];
				$tab_id = "abc-$i";
   			if ( $i == 1 ) {
   				$sac = 'show active';
   			} else {
   				$sac = '';
   			}
		?>
		<div class="tab-pane fade <?php echo esc_attr( $sac ); ?>" id="<?php echo esc_attr( $tab_id ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tab_id ); ?>-tab">
	      <div class="cta-wrap s-cta-wrap">
	      	<div class="section-title mb-30">
            	<h2><span><?php echo esc_attr( $tab_form_subtitle ); ?></span><?php echo esc_attr( $tab_form_title ); ?></h2>
            </div>
	         <?php echo do_shortcode( $tab_text ); ?>
	      </div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>

<?php } else { 
	
	$form_tab_side_img = $settings['form_tab_side_img']['url'];
?>

<div class="third-cta-wrap">
	<?php if ( is_array($tab_items) ) { ?>
   <ul class="nav nav-tabs" id="myTab" role="tablist">
   	<?php 
			$i = 0;
			foreach ($tab_items as $key => $value) {
				$i++;
				$tab_title_icon = $value['tab_title_icon'];
				$tab_title = $value['tab_title'];
				
				$tab_id = "abc-$i";

   			if ( $i == 1) {
   				$ac = 'active';
   				$as = 'true';
   			} else {
   				$ac = '';
   				$as = 'false';
   			}
	  	?>
      <li class="nav-item">
	      <a class="nav-link <?php echo esc_attr( $ac ); ?>" id="<?php echo esc_attr( $tab_id ); ?>-tab" data-toggle="tab" href="#<?php echo esc_attr( $tab_id ); ?>" role="tab" aria-controls="<?php echo esc_attr( $tab_id ); ?>" aria-selected="<?php echo esc_attr( $as ); ?>">
	      	<i class="<?php echo esc_attr( $tab_title_icon ); ?>"></i>
	      	<?php echo esc_html( $tab_title ); ?>
	      </a>
	   </li>
	   <?php } ?>
   </ul>
   <?php } if ( is_array( $tab_items ) ) { ?>
   <div class="tab-content" id="myTabContent">
   	<?php 
			$i = 0;
			foreach ($tab_items as $key => $value) {
				$i++;
				$tab_form_title = $value['tab_form_title'];
				$tab_form_subtitle = $value['tab_form_subtitle'];
				$tab_text = $value['tab_text'];
				$tab_title = $value['tab_title'];
				$tab_id = "abc-$i";
   			if ( $i == 1 ) {
   				$sac = 'show active';
   			} else {
   				$sac = '';
   			}
		?>
      <div class="tab-pane fade <?php echo esc_attr( $sac ); ?>" id="<?php echo esc_attr( $tab_id ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tab_id ); ?>-tab">
         <?php echo do_shortcode( $tab_text ); ?>
         <?php if(!empty( $form_tab_side_img )){ ?>
         	<img src="<?php echo esc_url( $form_tab_side_img ); ?>" class="third-cta-img" alt="img">
         <?php } ?>
      </div>
      <?php } ?>
   </div>
   <?php } ?>
</div>

<?php }
	}
}