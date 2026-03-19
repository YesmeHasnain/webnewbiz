<?php	
namespace Amedea\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */

class amedea__diagonal_media2 extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'diagonal_media2';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Diagonal Media 2', 'amedea' );
	}
	
	/**
	* Retrieve the widget icon.
	*
	* @since 1.0.0
	*
	* @return string Widget icon.
	*/

	public function get_icon() {
		return 'eicon-image-before-after';
	}

	/**
	* Retrieve the list of scripts the widget depended on.
	*
	* Used to set scripts dependencies required to run the widget.
	*
	* @since 1.0.0
	*
	* @return array Widget scripts dependencies.
	*/

	public function get_script_depends() {
		return [ 'gsap' , 'splitting' , 'imagesloaded' , 'amedea-diagonal-media2' ];
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends() {
		return [ 'diagonal-media' ];
	}
	
	/**
	* Retrieve the list of categories the widget belongs to.
	*
	* Used to determine where to display the widget in the editor.
	*
	* Note that currently Elementor supports only one category.
	* When multiple categories passed, Elementor uses the first one.
	*
	* @since 1.0.0
	*
	* @return array Widget categories.
	*/

	public function get_categories() {
		return [ 'amedea-category' , 'amedea-diagonal-media-category' ];
	}


	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	 
	protected function register_controls() {

		$this->start_controls_section(
			'section_content0',
			[
				'label' => esc_html__( 'Color', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'style',
			array(
			  'label'       => esc_html__('Text Color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'dark',
			  'label_block' => true,
			  'options' => array(
				'light' => esc_html__('Light', 'amedea'),
				'dark' => esc_html__('Dark', 'amedea'),
			  )
			)
		  );
	 
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$repeater = new \Elementor\Repeater();
				
		$repeater->add_control(
		  'image_1',
			[
				'label' => __( 'Image #1', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);
		
		$repeater->add_control(
			'version_1',
			array(
			  'label'       => esc_html__('Version', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'image',
			  'label_block' => true,
			  'options' => array(
				'image' => esc_html__('Image', 'amedea'),
				'video' => esc_html__('Video', 'amedea'),
			  )
			)
		  );
		
		$repeater->add_control(
		  'image_2',
			[
				'label' => __( 'Image #2', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);
		
		$repeater->add_control(
			'version_2',
			array(
			  'label'       => esc_html__('Version', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'image',
			  'label_block' => true,
			  'options' => array(
				'image' => esc_html__('Image', 'amedea'),
				'video' => esc_html__('Video', 'amedea'),
			  )
			)
		  );
		
		$repeater->add_control(
		  'image_3',
			[
				'label' => __( 'Image #3', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);
		
		$repeater->add_control(
			'version_3',
			array(
			  'label'       => esc_html__('Version', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'image',
			  'label_block' => true,
			  'options' => array(
				'image' => esc_html__('Image', 'amedea'),
				'video' => esc_html__('Video', 'amedea'),
			  )
			)
		  );
		
		$repeater->add_control(
		  'image_4',
			[
				'label' => __( 'Image #4', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);
		
		$repeater->add_control(
			'version_4',
			array(
			  'label'       => esc_html__('Version', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'image',
			  'label_block' => true,
			  'options' => array(
				'image' => esc_html__('Image', 'amedea'),
				'video' => esc_html__('Video', 'amedea'),
			  )
			)
		  );
				
		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Rise', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$repeater->add_control(
			'title-middle',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'like', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$repeater->add_control(
			'title-last',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Lions', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);

		$this->add_control(
		  'images',
		  array(
			'label'     => esc_html__('Items', 'amedea'),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default' => [
					[
						'title' => esc_html__( 'You', 'amedea' ),
						'title-middle' => esc_html__( 'are', 'amedea' ),
						'title-last' => esc_html__( 'many', 'amedea' ),
						'image_1' => \Elementor\Utils::get_placeholder_image_src(),
						'version_1' => 'image',
						'image_2' => \Elementor\Utils::get_placeholder_image_src(),
						'version_2' => 'image',
						'image_3' => \Elementor\Utils::get_placeholder_image_src(),
						'version_3' => 'image',
						'image_4' => \Elementor\Utils::get_placeholder_image_src(),
						'version_4' => 'image',

					],
					[
						'title' => esc_html__( 'You', 'amedea' ),
						'title-middle' => esc_html__( 'are', 'amedea' ),
						'title-last' => esc_html__( 'many', 'amedea' ),
						'image_1' => \Elementor\Utils::get_placeholder_image_src(),
						'version_1' => 'image',
						'image_2' => \Elementor\Utils::get_placeholder_image_src(),
						'version_2' => 'image',
						'image_3' => \Elementor\Utils::get_placeholder_image_src(),
						'version_3' => 'image',
						'image_4' => \Elementor\Utils::get_placeholder_image_src(),
						'version_4' => 'image',
					],
				],
			'title_field' => '<span>{{ title }}</span>',
		  )
		);
		
		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings	= $this->get_settings_for_display();
		$images	    = $settings['images'];		
		$style	    = $settings['style'];
	?>

<section class="diagonal-media--container <?php echo esc_attr($settings['style']); ?>">		 
	<div class="slides--di">
		<?php $i = 0; if (is_array($images) || is_object($images)) { foreach($images as $key => $image): ?>
		<div class="slide--diagonal<?php if ( $i == 0 ) { echo " slide--diagonal--current"; } ?>">
			<div class="slide--diagonal__img-wrap">
				<?php if ( 'video' == $image['version_1'] ) { ?>
				<div class="slide--diagonal__img slide--diagonal__img--1">
					<video class="__video video--1" xmlns="https://www.w3.org/1999/xhtml" width="auto" height="auto" autoplay muted loop>
						<source src="<?php echo esc_url($image['image_1']['url']); ?>" type="video/mp4">
					</video>
				</div>
				<?php } else { ?>
				<img class="slide--diagonal__img slide--diagonal__img--1" src="<?php echo esc_url($image['image_1']['url']); ?>" alt=""/>
				<?php } ?>
				<?php if ( 'video' == $image['version_2'] ) { ?>
				<div class="slide--diagonal__img slide--diagonal__img--2">	
					<video class="__video video--1" xmlns="https://www.w3.org/1999/xhtml" width="auto" height="auto" autoplay muted loop>
						<source src="<?php echo esc_url($image['image_2']['url']); ?>" type="video/mp4">
					</video>
				</div>
				<?php } else { ?>
				<img class="slide--diagonal__img slide--diagonal__img--2" src="<?php echo esc_url($image['image_2']['url']); ?>" alt=""/>
				<?php } ?>
				<div class="break"></div>
				<?php if ( 'video' == $image['version_3'] ) { ?>
				<div class="slide--diagonal__img slide--diagonal__img--3">
					<video class="__video video--3" xmlns="https://www.w3.org/1999/xhtml" width="auto" height="auto" autoplay muted loop>
						<source src="<?php echo esc_url($image['image_3']['url']); ?>" type="video/mp4">
					</video>
				</div>
				<?php } else { ?>
				<img class="slide--diagonal__img slide--diagonal__img--3" src="<?php echo esc_url($image['image_3']['url']); ?>" alt=""/>
				<?php } ?>
				<?php if ( 'video' == $image['version_4'] ) { ?>
				<div class="slide--diagonal__img slide--diagonal__img--4">	
					<video class="__video video--4" xmlns="https://www.w3.org/1999/xhtml" width="auto" height="auto" autoplay muted loop>
						<source src="<?php echo esc_url($image['image_4']['url']); ?>" type="video/mp4">
					</video>
				</div>
				<?php } else { ?>
				<img class="slide--diagonal__img slide--diagonal__img--4" src="<?php echo esc_url($image['image_4']['url']); ?>" alt=""/>
				<?php } ?>
			</div>
			<h2 class="slide--diagonal__title">
				<span class="slide--diagonal__title-inner" data-splitting><?php echo esc_html($image['title']); ?></span>
				<span class="slide--diagonal__title-inner slide--diagonal__title-inner--middle" data-splitting><?php echo esc_html($image['title-middle']); ?></span>
				<span class="slide--diagonal__title-inner" data-splitting><?php echo esc_html($image['title-last']); ?></span>
			</h2>
		</div>
		<?php $i++; endforeach; } ?>
		<button class="slides--di__nav slides--di__nav--prev">
			<svg><path d="M82 10H9" stroke="" stroke-width="2"/><path d="M10.474 0C7.988 4 4.118 7.422 0 10c4.156 2.539 7.865 6 10.474 10h2.485c-2.608-4-5.744-7.379-9.618-10C7.215 7.34 10.474 4 13 0h-2.526zm6 0C13.987 4 10.116 7.422 6 10c4.156 2.539 7.865 6 10.474 10h2.485c-2.606-4-5.745-7.379-9.617-10C13.214 7.34 16.474 4 19 0h-2.526z" fill=""/></svg>
		</button>
		<button class="slides--di__nav slides--di__nav--next">
			<svg><path d="M0 10h73" stroke="" stroke-width="2"/><path d="M71.526 0C74.012 4 77.882 7.422 82 10c-4.156 2.539-7.865 6-10.474 10h-2.485c2.608-4 5.744-7.379 9.618-10C74.785 7.34 71.526 4 69 0h2.526zm-6 0C68.013 4 71.884 7.422 76 10c-4.156 2.539-7.865 6-10.474 10H63.04c2.606-4 5.745-7.379 9.617-10C68.786 7.34 65.526 4 63 0h2.526z" fill=""/></svg>
		</button>
	</div>
</section>

	<?php
	
	}

	/**
	* Render the widget output in the editor.
	*
	* Written as a Backbone JavaScript template and used to generate the live preview.
	*
	* @since 1.0.0
	*/

	protected function content_template() {}
}