<?php
if ( ! defined( 'ABSPATH' ) ) exit;



class Rashy_Widget_Woo_Price_Filter_Slider extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'rashy_price_filter_slider',
            esc_html__( 'Goal Price Filter Slider', 'rashy' ),
            array( 'description' => esc_html__( 'A WooCommerce price filter slider widget.', 'rashy' ) )
        );

        // Enqueue WooCommerce slider script when this widget is active
        add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_slider' ) );
    }

    public function maybe_enqueue_slider() {
        if ( is_active_widget( false, false, $this->id_base, true ) && function_exists( 'wc_enqueue_js' ) ) {
            wp_enqueue_script( 'wc-price-slider' );
            wp_enqueue_style( 'jquery-ui-slider' );
        }
    }

	public function widget( $args, $instance ) {
		if ( ! class_exists( 'WC_Widget_Price_Filter' ) ) {
			return;
		}

		echo isset( $args['before_widget'] ) ? $args['before_widget'] : '';

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Filter by price', 'rashy' );
		echo isset( $args['before_title'] ) ? $args['before_title'] : '';
		echo esc_html( apply_filters( 'widget_title', $title, $instance, $this->id_base ) );
		echo isset( $args['after_title'] ) ? $args['after_title'] : '';

		$transient_key = 'rashy_price_filter_widget_' . md5( json_encode( $_GET ) );
		$filter_html   = get_transient( $transient_key );

		if ( false === $filter_html ) {
			ob_start();
			the_widget( 'WC_Widget_Price_Filter', array( 'title' => '' ) );
			$filter_html = ob_get_clean();

			
			$filter_html = preg_replace( '/<h2[^>]*>.*?<\/h2>/', '', $filter_html );

			set_transient( $transient_key, $filter_html, MINUTE_IN_SECONDS * 3 );
		}


		$allowed_tags = array(
		    'form'    => array(
		        'method' => true, 'action' => true, 'class' => true,
		    ),
		    'div'     => array(
		        'class' => true, 'id' => true, 'style' => true, 'data-role' => true,
		        'data-*' => true, 'aria-*' => true,
		    ),
		    'span'    => array(
		        'class' => true,
		    ),
		    'input'   => array(
		        'type' => true, 'name' => true, 'value' => true, 'min' => true, 'max' => true, 'step' => true,
		        'class' => true, 'id' => true, 'data-*' => true, 'aria-*' => true,
		    ),
		    'button'  => array(
		        'type' => true, 'class' => true,
		    ),
		    'label'   => array(
		        'for' => true, 'class' => true,
		    ),
		    'script' => array(
		        'type' => true,
		    )
		);


		echo wp_kses( $filter_html, $allowed_tags );

		echo isset( $args['after_widget'] ) ? $args['after_widget'] : '';
	}




    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : esc_html__( 'Filter by price', 'rashy' );
        ?>
        <p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
		        <?php esc_html_e( 'Title:', 'rashy' ); ?>
		    </label>
		    <input class="widefat" 
		           id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
		           name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
		           type="text"
		           value="<?php echo esc_attr( $title ); ?>" />
		</p>

        <?php
    }

    public function update( $new_instance, $old_instance ) {
        return array(
            'title' => sanitize_text_field( $new_instance['title'] ),
        );
    }
}

// Register widget
if ( function_exists( 'goal_framework_reg_widget' ) ) {
    goal_framework_reg_widget( 'Rashy_Widget_Woo_Price_Filter_Slider' );
}
