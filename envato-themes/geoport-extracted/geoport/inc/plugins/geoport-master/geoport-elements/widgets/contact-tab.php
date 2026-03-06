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

class Contact_Tab extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'contact-tab';
	}

	public function get_title() {
		return 'Contact Tab';   // title to show on elementor
	}

	public function get_icon() {
		return 'eicon-tabs';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Contact Tab', 'geoport' ),   //section name for controler view
			]
		);
		$this->add_control(
			'post_number',
			[
				'label' => esc_html__( 'Number of Item', 'geoport' ),
				'description' => esc_html__( 'Give -1 for all contact', 'geoport' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
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

		$this->end_controls_section();

	}

	protected function render() {				//to show on the fontend 
		$settings = $this->get_settings();
		$post_number = $settings['post_number'];
		$post_order = $settings['post_order'];

		$grid_query= null;
	   	$args = array(
	      'post_type'      => 'contact_tab',
	      'post_status'    => 'publish',
	      'order' => $post_order,
	      'posts_per_page' => $post_number,
	   	);
	 
	$grid_query = new \WP_Query( $args );

	if ( $grid_query->have_posts() ) : 
?>
<!-- contact-area -->
<div class="container">
   <div class="row">
   	<div class="col-12">
         <div class="contact-tabs mb-40">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
	            <?php 
	            	$i = 0;
	            	while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 
	            	$i++;
		   			$ct_metadata = get_post_meta( get_the_ID(), '_ifinger_contact_tab', true );

	   				if (!empty($ct_metadata['title_icon'] )) {
					  $title_icon = $ct_metadata['title_icon'];
					} else {
					  $title_icon = '';
					} 

					$tab_title = get_the_title();
					$smallcase = strtolower($tab_title);
 					$tabid = str_replace(" ", "-", $smallcase);
 					$tab_id = str_replace(",", "", $tabid);

		   			if ( $i == 1) {
		   				$ac = 'active';
		   			} else {
		   				$ac = '';
		   			}
		   		?>
               <li class="nav-item">
                  <a class="nav-link <?php echo $ac; ?>" id="<?php echo $tab_id; ?>-tab" data-toggle="tab" href="#<?php echo $tab_id; ?>" role="tab" aria-controls="<?php echo $tab_id; ?>" aria-selected="true"><i class="<?php echo $title_icon; ?>"></i><?php the_title(); ?></a>
               </li>
               <?php endwhile; wp_reset_postdata(); ?>
            </ul>
         </div>
   	</div>
   </div>
</div>
<div class="container-fluid contact-map-container">
   <div class="row">
      <div class="col-12">
         <div class="tab-content" id="myTabContent">
         	<?php 
         		$i = 0;
         		while ( $grid_query->have_posts() ) : $grid_query->the_post(); global $post; 
         		$i++;

         		$ct_metadata = get_post_meta( get_the_ID(), '_ifinger_contact_tab', true );

	   			if (!empty($ct_metadata['info_title'] )) {
					  $info_title = $ct_metadata['info_title'];
					} else {
					  $info_title = '';
					}

					if (!empty($ct_metadata['contact_info_list'] )) {
					  $contact_info_list = $ct_metadata['contact_info_list'];
					} else {
					  $contact_info_list = '';
					}

					$tab_title = get_the_title();
					$smallcase = strtolower($tab_title);
 					$tabid = str_replace(" ", "-", $smallcase);
 					$tab_id = str_replace(",", "", $tabid);

				if ( $i == 1) {
	   				$sac = 'show active';
	   			} else {
	   				$sac = '';
	   			}

	   			if (!empty($ct_metadata['tab_map_embeded_code'] )) {
				  $tab_map_embeded_code = $ct_metadata['tab_map_embeded_code'];
				} else {
				  $tab_map_embeded_code = '';
				}
         	?>
            <div class="tab-pane fade <?php echo $sac; ?>" id="<?php echo $tab_id; ?>" role="tabpanel" aria-labelledby="<?php echo $tab_id; ?>-tab">
               <div class="contact-map-bg">

               		<?php echo $tab_map_embeded_code; ?>

               		<?php
                    if (!empty($ct_metadata['contact_info_list'] ) || !empty($info_title )) { ?>
                  	<div class="contact-map-content">
                     <h4><?php echo $info_title; ?></h4>
                     <ul>
                       	<?php
                       	if (!empty($ct_metadata['contact_info_list'] )) {
                   		foreach ($contact_info_list as $key => $value) { 
                   			$data_type = $value['info_text'];
			                if(filter_var($data_type, FILTER_VALIDATE_EMAIL)){
			                	$href_value = 'email';
			                } elseif( preg_match('/^[0-9\-\(\)\/\+\s]*$/', $data_type ) ){
			                	$href_value = 'phone';
			                } elseif (filter_var($data_type, FILTER_VALIDATE_URL)) {
			                	$href_value = 'url';
			                } else {
			                	$href_value = '';
			                }

			                if ( $href_value == 'email' ) {
                       	?>
                           <li><a href="mailto:<?php echo sanitize_email( $data_type ); ?>"><i class="<?php echo $value['info_icon']; ?>"></i><?php echo esc_html( $data_type ); ?></a></li>
                        <?php } elseif ( $href_value == 'phone' ) {
                        	$phone_no = str_replace(" ", "", $data_type);
                        ?>
                           <li><a href="tel:<?php echo esc_attr($phone_no); ?>"><i class="<?php echo $value['info_icon']; ?>"></i><?php echo esc_html( $data_type ); ?></a></li>
                        <?php } elseif ( $href_value == 'url' ) { ?>
                           <li><a href="<?php echo esc_url($data_type); ?>"><i class="<?php echo $value['info_icon']; ?>"></i><?php echo esc_html( $data_type ); ?></a></li>
                        <?php } else { ?>
                           <li><i class="<?php echo $value['info_icon']; ?>"></i><?php echo esc_html( $data_type ); ?></li>
                        <?php } 
                     	} ?>
                     	<?php } ?>
                     </ul>
                  </div>
                  <?php } ?>
               </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
         </div>
      </div>
   </div>
</div>
<!-- contact-area-end -->

<?php endif; //main if end

	}
}