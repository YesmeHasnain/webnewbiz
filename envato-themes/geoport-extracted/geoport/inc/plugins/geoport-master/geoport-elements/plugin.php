<?php
namespace Geoport;  //main namespace
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $void_widgets;
$void_widgets= array_map('basename', glob(dirname( __FILE__ ) . '/widgets/*.php'));



/* - Path define same as class name of the widget
====================================================================================================*/

use Geoport\Widgets\Section_Head;
use Geoport\Widgets\Slider_Three;
use Geoport\Widgets\Slider_Two;
use Geoport\Widgets\Banner_One;
use Geoport\Widgets\Image_Slider;
use Geoport\Widgets\Working_Steps;
use Geoport\Widgets\Video_Button;
use Geoport\Widgets\Single_Icon_Item;
use Geoport\Widgets\Geoport_Button;
use Geoport\Widgets\Single_Counter;
use Geoport\Widgets\Geoport_Counter;
use Geoport\Widgets\Photo_Gallery;
use Geoport\Widgets\Testimonial;
use Geoport\Widgets\Section_Blog;
use Geoport\Widgets\Contact_Tab;
use Geoport\Widgets\Form_Tab;
use Geoport\Widgets\Tab_Slider;
use Geoport\Widgets\Geoport_Accordion;
use Geoport\Widgets\Animated_Image;
use Geoport\Widgets\Section_Services;
use Geoport\Widgets\Ifinger_Icon_List;
use Geoport\Widgets\Our_Team;
use Geoport\Widgets\Progress_Bar;
use Geoport\Widgets\Our_History;
use Geoport\Widgets\Contact_Box;
use Geoport\Widgets\Location_Box;
use Geoport\Widgets\Section_Head_Two;
use Geoport\Widgets\Careers_List;

/* - End Of path define same as class name of the widget
====================================================================================================*/

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class Plugin {
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	
	public function __construct() {
		$this->add_actions();
		add_action('elementor/controls/register', array( $this, 'johanspond_icon_pack' ), 11 );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_enqueue_styles' ) );
	}


	/**
     * Enqueue editor styles
     *
     * @return void
     */

   public function editor_enqueue_styles() {
      wp_enqueue_style( 'geoport-elements-editor', GEOPORT_PLG_URL.'assets/css/geoport-admin-style.css',null, GEOPORT_VERSION );
      wp_enqueue_style( 'johanspond-icon-elementor', GEOPORT_PLG_URL.'assets/css/fontawesome-all.min.css',null, GEOPORT_VERSION );
   }


	/**
     * Extend Icon pack core controls.
     *
     * @param  object $controls_manager Controls manager instance.
     * @return void
     */

   public function johanspond_icon_pack( $controls_manager ) {
      	require_once GEOPORT_PLG_DIR. '/geoport-elements/controls/johanspond-icon.php';
         $controls = array(
         	$controls_manager::ICON => 'Johanspond_Icon_Controler',
         );
         foreach ( $controls as $control_id => $class_name ) {
         	$controls_manager->unregister( $control_id );
         	$controls_manager->register_control( $control_id, new $class_name() );
         }
   }

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {
		add_action( 'elementor/widgets/register', [ $this, 'on_widgets_registered' ] );
		add_action( 'elementor/frontend/after_register_scripts', function() {
			wp_register_script( 'geoport-elementor-editor', plugins_url( '../assets/js/geoport-elementor-editor.js', __FILE__), [ 'jquery' ], true, true );
		} );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function includes() {
		global $void_widgets;              //include the widgets here
		foreach($void_widgets as $key => $value){
   			require __DIR__ . '/widgets/'.$value;
		}
	}


	/* - Register all elements widget
	====================================================================================================*/

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {   
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Section_Head() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Slider_Three() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Slider_Two() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Banner_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Image_Slider() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Working_Steps() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Video_Button() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Single_Icon_Item() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Geoport_Button() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Single_Counter() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Geoport_Counter() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Photo_Gallery() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Testimonial() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Section_Blog() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Contact_Tab() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Form_Tab() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Tab_Slider() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Geoport_Accordion() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Animated_Image() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Section_Services() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Ifinger_Icon_List() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Our_Team() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Progress_Bar() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Our_History() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Contact_Box() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Location_Box() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Section_Head_Two() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Careers_List() );
	}

}
new Plugin();