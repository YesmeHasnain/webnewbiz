<?php
/*-------------------------------------------------------------------------------------------------*/
/*  sidebar register
/*-------------------------------------------------------------------------------------------------*/
/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 *
 *
**/

/*------------------------------------------------------------------------------------------*/
/*  Custom page sidebar register
/*------------------------------------------------------------------------------------------*/
function geoport_master_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Footer 2 Widgets 1', 'geoport'),
        'id'            => 'footer2-widgets1',
        'description'   => esc_html__('Widgets in this area will be shown on footer.', 'geoport'),
        'before_widget' => '<div id="%1$s" class="%2$s"><div class="footer-widget">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="footer-widget-title"><h4>',
        'after_title'   => '</h4></div>',
    ));
    register_sidebar(array(
        'name'          => esc_html__('Footer 2 Widgets 2', 'geoport'),
        'id'            => 'footer2-widgets2',
        'description'   => esc_html__('Widgets in this area will be shown on footer.', 'geoport'),
        'before_widget' => '<div id="%1$s" class="%2$s"><div class="footer-widget">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="footer-widget-title"><h4>',
        'after_title'   => '</h4></div>',
    ));
    register_sidebar(array(
        'name'          => esc_html__('Footer 2 Widgets 3', 'geoport'),
        'id'            => 'footer2-widgets3',
        'description'   => esc_html__('Widgets in this area will be shown on footer.', 'geoport'),
        'before_widget' => '<div id="%1$s" class="%2$s"><div class="footer-widget">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="footer-widget-title"><h4>',
        'after_title'   => '</h4></div>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Service Details widgets', 'geoport' ),
        'id'            => 'service-widgets',
        'description'   => esc_html__('Widgets in this area will be shown on service page sidebar.', 'geoport'),
        'before_widget' => '<div id="%1$s" class="%2$s service-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="service-widget-title"><h4 class="title">',
        'after_title'   => '</h4></div>',
    ));
}
add_action( 'widgets_init', 'geoport_master_widgets_init', 99 );                                        

/*===============================================================================================*/
/* - Sidebar Widget
/*===============================================================================================*/ 

/*------------------------------------------------------------------------------------------*/
/* - About Me Widget
/*------------------------------------------------------------------------------------------*/
if( ! class_exists( 'geoport_about_me_Widget' ) ) {

  //Footer About Widget
  class geoport_about_me_Widget extends WP_Widget {

    /* = Widget Register
    /*=====================================================*/
    function __construct() {

      $widget_ops     = array(
        'classname'   => 'geoport_about_me_widget',
        'description' => __( 'About Me Widget.', 'geoport' )
      );
      parent::__construct( 'address_widget', __( 'A::1 About Me Widget', 'geoport' ), $widget_ops );
    }

    /* = Front-end Setting
    /*=====================================================*/
    function widget( $args, $instance ) {

      extract( $args );
      echo $before_widget; ?>

      <?php 

      if (!empty($instance['title'])) {
        $title = $instance['title'];
      } else {
        $title = '';
      } 

      if (!empty($instance['about_me_desc'])) {
        $about_me_desc = $instance['about_me_desc'];
      } else {
        $about_me_desc = '';
      } 

      if (!empty($instance['about_name'])) {
        $about_name = $instance['about_name'];
      } else {
        $about_name = '';
      } 

      if (!empty($instance['social_list'])) {
        $social_list = $instance['social_list'];
      } else {
        $social_list = '';
      }

      if ( !empty($instance['about_me_widget_logo'])) {
        $image_id = $instance['about_me_widget_logo'];
        $attachment = wp_get_attachment_image_src( $image_id, 'full' );
        $image =($attachment) ? $attachment[0] : $image_id;
      } else {
        $image = '';
      }

      ?>

      <div class="widget-title mb-35">
        <span></span>
        <h4><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h4>
      </div>
      <div class="blog-about text-center">
        <div class="blog-avatar-img mb-25">
          <img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'Author logo', 'geoport' ); ?>">
        </div>
        <div class="b-about-content mb-20">
          <h4><?php echo $about_name; ?></h4>
          <p><?php echo $about_me_desc; ?></p>
        </div>
        <?php if (!empty( $social_list)) { ?>
          <div class="b-about-social">
            <?php echo $social_list; ?>
          </div>
        <?php } ?>
      </div>

      <?php echo $after_widget;
    }

    /* = Widget Update Setting
    /*=====================================================*/
    function update( $new_instance, $old_instance ) {

      $instance                         = $old_instance;
      $instance['title']                = $new_instance['title'];
      $instance['about_me_widget_logo'] = $new_instance['about_me_widget_logo'];
      $instance['about_name']           = $new_instance['about_name'];
      $instance['about_me_desc']        = $new_instance['about_me_desc'];
      $instance['social_list']          = $new_instance['social_list'];

      return $instance;
    }

    /* = Widget Form Setting
    /*=====================================================*/
    function form( $instance ) {

      /* - Defaults Value Seiitng Fields
      /*------------------------------------------------- */
      $instance   = wp_parse_args( $instance, array(

        'title'                => 'About Me',
        'about_me_widget_logo' => GEOPORT_PLG_URL. 'assets/imgs/me.png',
        'about_name'           => 'Rosalina D. Willaimson',
        'about_me_desc'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.',
        'social_list' => '
        <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
        <a href="#" class="instagram"><i class="fab fa-pinterest"></i></a>
        <a href="#" class="pinterest"><i class="fab fa-behance"></i></a>
        <a href="#" class="pinterest"><i class="fab fa-youtube"></i></a>',
      ));

      /* - Widget Title Seiitng
      /*------------------------------------------------- */
      $text_value = esc_attr( $instance['title'] );
      $text_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => __( 'Title', 'geoport' ),
      );
      echo geoport_add_element( $text_field, $text_value );

      /* - About Me Widget Logo Upload Field
      /* ------------------------------------------------- */
      $about_me_widget_logo_value = esc_attr( $instance['about_me_widget_logo'] );
      $about_me_widget_logo_field = array(
        'id'    => $this->get_field_name('about_me_widget_logo'),
        'name'  => $this->get_field_name('about_me_widget_logo'),
        'type'  => 'image',
        'title' => __( 'Logo Image', 'geoport' ),
        'info'  => __( 'About Widghet Logo Upload Here', 'geoport' ),
      );
      echo geoport_add_element( $about_me_widget_logo_field, $about_me_widget_logo_value );

      /* - Widget Name Seiitng
      /*------------------------------------------------- */
      $about_me_name_value = esc_attr( $instance['about_name'] );
      $about_me_name_field = array(
        'id'    => $this->get_field_name('about_name'),
        'name'  => $this->get_field_name('about_name'),
        'type'  => 'text',
        'title' => __( 'Name', 'geoport' ),
      );
      echo geoport_add_element( $about_me_name_field, $about_me_name_value );


      /* - About widget Button Link
      /* ------------------------------------------------- */
      $about_me_desc_value = esc_attr( $instance['about_me_desc'] );
      $about_me_desc_field = array(
        'id'    => $this->get_field_name('about_me_desc'),
        'name'  => $this->get_field_name('about_me_desc'),
        'type'  => 'textarea',
        'title' => __( 'Description', 'geoport' ),
      );
      echo geoport_add_element( $about_me_desc_field, $about_me_desc_value );

      /* - About widget Button Link
      /* ------------------------------------------------- */
      $social_list_value = esc_attr( $instance['social_list'] );
      $social_list_field = array(
        'id'    => $this->get_field_name('social_list'),
        'name'  => $this->get_field_name('social_list'),
        'type'  => 'textarea',
        'title' => __( 'Social List', 'geoport' ),
      );
      echo geoport_add_element( $social_list_field, $social_list_value );
    }

  }
  // End Of About Me Widget
}

if ( ! function_exists( 'geoport_about_me_widget_init' ) ) {
  function geoport_about_me_widget_init() {
    register_widget( 'geoport_about_me_Widget' );
  }
  add_action( 'widgets_init', 'geoport_about_me_widget_init', 1 );
}


/*------------------------------------------------------------------------------------------*/
/* - Recent Post Widget Widget
/*------------------------------------------------------------------------------------------*/
if( ! class_exists( 'geoport_recent_post_Widget' ) ) {
  class geoport_recent_post_Widget extends WP_Widget {

    /* = Widget Register
    /*=====================================================*/
    function __construct() {
      $widget_ops     = array(
        'classname'   => 'geoport_rp_widget',
        'description' =>  __( 'Recent Post Widget.', 'geoport' )
      );
      parent::__construct( 'recent_post_widget', __( 'A::2 Recent Post', 'geoport' ), $widget_ops );
    }

    /* = Front-end Setting
    /*=====================================================*/
    function widget( $args, $instance ) {

      extract( $args );

      echo $before_widget;

      $title = $instance['title'];
      $post_type = $instance['post_type'];
      $posts_order = $instance['post_order'];
      $posts_per_page = $instance['post_per_page'];
      $post_condition = $instance['post_condition'];

      ?> 

      <div class="widget-title mb-35">
        <span></span>
        <h4><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h4>
      </div>

      <div class="sidebar-rc-post">
        <?php
        if ( $post_condition == 'popular' ) {
          $the_query = new WP_Query( array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
          ) );
        } elseif ( $post_condition == 'recent' ) {
          $the_query = new WP_Query( array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'order' => $posts_order,
          ) );
        } else {
          $the_query = new WP_Query( array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'order' => $posts_order,
          ) );
        }
        while ( $the_query->have_posts() ) : $the_query->the_post();
          ?>

          <div class="rc-post">
            <?php if(has_post_thumbnail()) { ?>
              <div class="rc-post-thumb">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'geoport-thumb-140-140' ); ?></a>
              </div>
            <?php } ?>
            <div class="rc-post-content">
              <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <div class="rc-post-date">
                <span><i class="fal fa-calendar-alt"></i><?php echo esc_html( get_the_date() ); ?></span>
              </div>
            </div>
          </div>

        <?php endwhile; wp_reset_query(); ?>
      </div>
    
      <?php echo $after_widget;
    }

     /* = Widget Update Setting
    /*=====================================================*/
    function update( $new_instance, $old_instance ) {

      $instance            = $old_instance;
      $instance['title']   = $new_instance['title'];
      $instance['post_type']    = $new_instance['post_type'];
      $instance['post_order']    = $new_instance['post_order'];
      $instance['post_per_page']    = $new_instance['post_per_page'];
      $instance['post_condition']    = $new_instance['post_condition'];

      return $instance;
    }

    /* = Widget Form Setting
    /*=====================================================*/
    function form( $instance ) {

      /* - Field Default Value
      /* ------------------------------------------------- */
      $instance       = wp_parse_args( $instance, array(
        'title'       => 'Recent Post',
        'post_per_page'  => '3',
        'post_type'      => 'post',
        'post_order'     => 'ASC',
        'post_condition' => 'recent',
      ));

      /* - Title Field
      /* ------------------------------------------------- */
      $text_value = esc_attr( $instance['title'] );
      $text_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => __( 'Title', 'geoport' ),
      );
      echo geoport_add_element( $text_field, $text_value );

      /* - Post type
      /* ------------------------------------------------- */
      $post_type_value = esc_attr( $instance['post_type'] );
      $post_type_field = array(
        'id'    => $this->get_field_name('post_type'),
        'name'  => $this->get_field_name('post_type'),
        'type'           => 'select',
        'title'          => __( 'Select Post Type', 'geoport' ),
        'options'        => array(
          'post'         => 'Default Post',
          'events'       => 'Event Post',
        ),
        'default_option' => __( 'Select post type', 'geoport' ),
      );
      echo geoport_add_element( $post_type_field, $post_type_value );

      /* - Post Order
      /* ------------------------------------------------- */
      $post_order_value = esc_attr( $instance['post_order'] );
      $post_order_field = array(
        'id'    => $this->get_field_name('post_order'),
        'name'  => $this->get_field_name('post_order'),
        'type'   => 'select',
        'title'  => __( 'Select Post Order', 'geoport' ),
        'options' => array(
          'ASC'  => 'Ascending Order',
          'DESC' => 'Descending Post',
        ),
        'default_option' => __( 'Select post order', 'geoport' ),
      );
      echo geoport_add_element( $post_order_field, $post_order_value );

      /* - Post Per Page
      /* ------------------------------------------------- */
      $post_per_page_value = esc_attr( $instance['post_per_page'] );
      $post_per_page_field = array(
        'id'    => $this->get_field_name('post_per_page'),
        'name'  => $this->get_field_name('post_per_page'),
        'type'  => 'text',
        'title' => __( 'Post Per Page', 'geoport' ),
        'info'  => __( 'How post display here', 'geoport' ),
      );
      echo geoport_add_element( $post_per_page_field, $post_per_page_value );

      /* - Popular or recent ?
      /* ------------------------------------------------- */
      $post_condition_value = esc_attr( $instance['post_condition'] );
      $post_condition_field = array(
        'id'    => $this->get_field_name('post_condition'),
        'name'  => $this->get_field_name('post_condition'),
        'type'   => 'select',
        'title'  => __( 'Recent or Popular?', 'geoport' ),
        'options' => array(
          'recent'  => 'Recent',
          'popular' => 'Popular',
        ),
        'default_option' => __( 'Select post order', 'geoport' ),
      );
      echo geoport_add_element( $post_condition_field, $post_condition_value );
    }

  }
}

if ( ! function_exists( 'geoport_recent_post_widget_init' ) ) {
  function geoport_recent_post_widget_init() {
    register_widget( 'geoport_recent_post_Widget' );
  }
  add_action( 'widgets_init', 'geoport_recent_post_widget_init', 2 );
}


/*===============================================================================================*/
/* - Footer Widget
/*===============================================================================================*/ 

/*------------------------------------------------------------------------------------------*/
/* - Contact Info Widget
/*------------------------------------------------------------------------------------------*/
if( ! class_exists( 'geoport_about_us_Widget' ) ) {

  //Footer Newsletter Widget
  class geoport_about_us_Widget extends WP_Widget {

    /* = Widget Register
    /*=====================================================*/
    function __construct() {
      $widget_ops     = array(
        'classname'   => 'geoport_about_us_widget',
        'description' => 'About Widget.'
      );
      parent::__construct( 'about_us_widget', 'A::3 About us Widget', $widget_ops );
    }

    /* = Front-end Setting
    /*=====================================================*/
    function widget( $args, $instance ) {

      extract( $args );
      echo $before_widget; ?>

      <?php 
      $title = $instance['title'];
      $desc = $instance['desc'];
      $social_list  = $instance['social_list'];

      if ( !empty($instance['about_logo'])) {
        $image_id = $instance['about_logo'];
        $attachment = wp_get_attachment_image_src( $image_id, 'full' );
        $image =($attachment) ? $attachment[0] : $image_id;
      } else {
        $image = '';
      }

      ?>
      
      <div class="about-us-widget">
        <?php if (!empty( $image )) { ?>
          <div class="footer-logo">
            <a href="<?php echo esc_url(home_url('/')) ?>"><img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'footer logo', 'geoport' ); ?>"></a>
          </div>
        <?php } else { ?>
          <div class="footer-widget-title">
            <h4><?php echo $title; ?></h4>
          </div>
        <?php } if ( !empty( $desc ) ) { ?>
          <div class="footer-text">
            <p><?php echo esc_html( $desc ); ?></p>
          </div>
        <?php } ?>
        <div class="footer-social">
          <?php echo $social_list; ?>
        </div>
      </div>

      <?php echo $after_widget;
    }

    /* = Widget Update Setting
    /*=====================================================*/
    function update( $new_instance, $old_instance ) {
      $instance          = $old_instance;

      $instance['title'] = $new_instance['title'];
      $instance['about_logo'] = $new_instance['about_logo'];
      $instance['desc']  = $new_instance['desc'];
      $instance['social_list']  = $new_instance['social_list'];

      return $instance;
    }

    /* = Widget Form Setting
    /*=====================================================*/
    function form( $instance ) {

      /* - Defaults Value Setting Fields
      /*------------------------------------------------- */
      $instance   = wp_parse_args( $instance, array(

        'title'       => 'About us',
        'about_logo'  => GEOPORT_PLG_URL. 'assets/imgs/logo1.png',
        'desc'        => 'Transport infrastructure consists of the fixed install anions',
        'social_list' => '
        <ul>
        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
        </ul>',

      ));

      /* - Widget Title Setting
      /*------------------------------------------------- */
      $text_value = esc_attr( $instance['title'] );
      $text_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => 'Title',
      );
      echo geoport_add_element( $text_field, $text_value );

      /* - About Me Widget Logo Upload Field
      /* ------------------------------------------------- */
      $about_logo_value = esc_attr( $instance['about_logo'] );
      $about_logo_field = array(
        'id'    => $this->get_field_name('about_logo'),
        'name'  => $this->get_field_name('about_logo'),
        'type'  => 'image',
        'title' => __( 'Logo Image', 'geoport' ),
        'info'  => __( 'About Widghet Logo Upload Here', 'geoport' ),
      );
      echo geoport_add_element( $about_logo_field, $about_logo_value );

      /* - Widget Description Settings
      /*------------------------------------------------- */
      $text_value = esc_attr( $instance['desc'] );
      $text_field = array(
        'id'    => $this->get_field_name('desc'),
        'name'  => $this->get_field_name('desc'),
        'type'  => 'textarea',
        'title' => 'Description',
      );
      echo geoport_add_element( $text_field, $text_value );

      /* - Social Media Link Text
      /* ------------------------------------------------- */
      $social_list_value = esc_attr( $instance['social_list'] );
      $social_list_field = array(
        'id'    => $this->get_field_name('social_list'),
        'name'  => $this->get_field_name('social_list'),
        'type'  => 'textarea',
        'title' => 'Social Media List',
      );
      echo geoport_add_element( $social_list_field, $social_list_value );
    }

  }
}

if ( ! function_exists( 'geoport_about_us_widget_init' ) ) {
  function geoport_about_us_widget_init() {
    register_widget( 'geoport_about_us_Widget' );
  }
  add_action( 'widgets_init', 'geoport_about_us_widget_init', 3 );
}


/*------------------------------------------------------------------------------------------*/
/* - Working Hour Widget
/*------------------------------------------------------------------------------------------*/
if( ! class_exists( 'geoport_working_hour_Widget' ) ) {

  //Footer Newsletter Widget
  class geoport_working_hour_Widget extends WP_Widget {

    /* = Widget Register
    /*=====================================================*/
    function __construct() {
      $widget_ops     = array(
        'classname'   => 'geoport_working_hour_widget',
        'description' => 'Working Time Widget.'
      );
      parent::__construct( 'working_hour_widget', 'A::4 Working Time', $widget_ops );
    }

    /* = Front-end Setting
    /*=====================================================*/
    function widget( $args, $instance ) {

      extract( $args );
      echo $before_widget;

      $title = $instance['title'];
      $working_time = $instance['working_time'];
      $working_content  = $instance['working_content'];

      ?>
  
      <div class="footer-work-hour">
        <?php if (!empty( $title )) { ?>
        <div class="footer-widget-title">
          <h4><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h4>
        </div>
        <?php } ?>
        <div class="footer-work-hour">
          <?php echo $working_time; ?>
        </div>
        <div class="footer-work-hour-content">
          <?php echo $working_content; ?>
        </div>
      </div>

      <?php echo $after_widget;
    }

    /* = Widget Update Setting
    /*=====================================================*/
    function update( $new_instance, $old_instance ) {
      $instance          = $old_instance;

      $instance['title'] = $new_instance['title'];
      $instance['working_time'] = $new_instance['working_time'];
      $instance['working_content'] = $new_instance['working_content'];

      return $instance;
    }

    /* = Widget Form Setting
    /*=====================================================*/
    function form( $instance ) {

      /* - Fields Defaults Value
      /* ------------------------------------------------- */
      $instance   = wp_parse_args( $instance, array(

        'title'       => 'Working Hours',
        'working_time' => '
        <ul>
          <li>Monday - Friday: 7:00 - 17:00</li>
          <li>Saturday: 7:00 - 12:00</li>
          <li>Sunday and holidays: 8:00 - 10:00</li>
        </ul>',
        'working_content' => '
        <p><span>For more then 30 years,</span> Transport has been a reliable partner in the field of logistics and cargo forwarding.</p><a href="#"><i class="far fa-angle-right"></i>Discover More</a>',

      ));

      /* - Widget Title Setting
      /* ------------------------------------------------- */
      $text_value = esc_attr( $instance['title'] );
      $text_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => 'Title',
      );
      echo geoport_add_element( $text_field, $text_value );

      /* - Working Time
      /* ------------------------------------------------- */
      $working_time_value = esc_attr( $instance['working_time'] );
      $working_time_field = array(
        'id'    => $this->get_field_name('working_time'),
        'name'  => $this->get_field_name('working_time'),
        'type'  => 'textarea',
        'title' => 'Working Time',
      );
      echo geoport_add_element( $working_time_field, $working_time_value );

      /* - Working Content
      /* ------------------------------------------------- */
      $working_content_value = esc_attr( $instance['working_content'] );
      $working_content_field = array(
        'id'    => $this->get_field_name('working_content'),
        'name'  => $this->get_field_name('working_content'),
        'type'  => 'textarea',
        'title' => 'Working Content',
      );
      echo geoport_add_element( $working_content_field, $working_content_value );

    }
  }
  // End Of Footer About Widget
}

if ( ! function_exists( 'geoport_working_hour_widget_init' ) ) {
  function geoport_working_hour_widget_init() {
    register_widget( 'geoport_working_hour_Widget' );
  }
  add_action( 'widgets_init', 'geoport_working_hour_widget_init', 4 );
}


/*===============================================================================================*/
/* - Service Widget
/*===============================================================================================*/ 

/*------------------------------------------------------------------------------------------*/
/* - Service Categories Widget
/*------------------------------------------------------------------------------------------*/
if( ! class_exists( 'geoport_service_cats_Widget' ) ) {
  class geoport_service_cats_Widget extends WP_Widget {

    /* = Widget Register
    /*=====================================================*/
    function __construct() {
      $widget_ops     = array(
        'classname'   => 'geoport_service_cats_widget',
        'description' =>  __( 'Service Categories.', 'geoport' )
      );
      parent::__construct( 'service_cats_post_widget', __( 'A::5 Service Cats', 'geoport' ), $widget_ops );
    }

    /* = Front-end Setting
    /*=====================================================*/
    function widget( $args, $instance ) {

      extract( $args );

      echo $before_widget;

      $title = $instance['title'];
      $sub_title = $instance['sub_title'];

      $filters = get_terms( array(
        'taxonomy' => 'service_category',
        'hide_empty'  => false, 
        'orderby'  => 'include',
      ) );

      ?> 
      <div class="services-cat">
        <div class="service-widget-title">
          <span><?php echo esc_html( $sub_title ); ?></span>
          <h2><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
          
        </div>
        <?php if (!empty($filters)) { ?>
          <ul>
            <?php 
              if ( $filters && ! is_wp_error( $filters ) ) {
                foreach ($filters as $filter) {
                  $meta_icon = get_term_meta( $filter->term_id, '_service_taxonomy_options', true );

                  if (is_array($meta_icon) && isset($meta_icon['service_cat_icon'])) {
                      $icon_class = $meta_icon['service_cat_icon'];
                  } else {
                      // Handle the case where the expected array structure is not present
                      $icon_class = ''; // Or a default value
                  }

                  echo '<li><a href="'.get_term_link($filter->slug, 'service_category').'"><i class="'.$icon_class.'"></i> '.$filter->name.' <i class="far fa-arrow-right"></i></a></li>';
                }
              }
            ?>
          </ul>
        <?php } ?>
      </div>
    
      <?php echo $after_widget;
    }

     /* = Widget Update Setting
    /*=====================================================*/
    function update( $new_instance, $old_instance ) {

      $instance            = $old_instance;
      $instance['title']   = $new_instance['title'];
      $instance['sub_title']    = $new_instance['sub_title'];

      return $instance;
    }

    /* = Widget Form Setting
    /*=====================================================*/
    function form( $instance ) {

      /* - Field Default Value
      /* ------------------------------------------------- */
      $instance       = wp_parse_args( $instance, array(
        'title'       => __( 'Transport Services', 'geoport' ),
        'sub_title'   => __( 'Services', 'geoport' ),
      ));

      /* - Title Field
      /* ------------------------------------------------- */
      $title_value = esc_attr( $instance['title'] );
      $title_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => __( 'Title', 'geoport' ),
      );
      echo geoport_add_element( $title_field, $title_value );

      /* - Sub Title Field
      /* ------------------------------------------------- */
      $sub_title_value = esc_attr( $instance['sub_title'] );
      $sub_title_field = array(
        'id'    => $this->get_field_name('sub_title'),
        'name'  => $this->get_field_name('sub_title'),
        'type'  => 'text',
        'title' => __( 'Sub Title', 'geoport' ),
      );
      echo geoport_add_element( $sub_title_field, $sub_title_value );
    }

  }
}

if ( ! function_exists( 'geoport_service_cats_widget_init' ) ) {
  function geoport_service_cats_widget_init() {
    register_widget( 'geoport_service_cats_Widget' );
  }
  add_action( 'widgets_init', 'geoport_service_cats_widget_init', 2 );
}


/*------------------------------------------------------------------------------------------*/
/* - Download Browser Widget
/*------------------------------------------------------------------------------------------*/
if( ! class_exists( 'geoport_download_Widget' ) ) {

  //Footer Newsletter Widget
  class geoport_download_Widget extends WP_Widget {

    /* = Widget Register
    /*=====================================================*/
    function __construct() {
      $widget_ops     = array(
        'classname'   => 'geoport_download_widget',
        'description' => 'Download Widget.'
      );
      parent::__construct( 'download_widget', 'A::6 Download Widget', $widget_ops );
    }

    /* = Front-end Setting
    /*=====================================================*/
    function widget( $args, $instance ) {

      extract( $args );
      echo $before_widget; ?>

      <?php 
        $text_l1 = $instance['text_l1'];
        $text_l2 = $instance['text_l2'];
        $download_btns  = $instance['download_btns'];

        if ( !empty($instance['sd_bg_img'])) {
          $image_id = $instance['sd_bg_img'];
          $attachment = wp_get_attachment_image_src( $image_id, 'full' );
          $image =($attachment) ? $attachment[0] : $image_id;
        } else {
          $image = '';
        }
      ?>

      <div class="services-pdf" data-background="<?php echo esc_url( $image ); ?>">
        <div class="service-widget-title">
          <span><?php echo esc_html( $text_l1 ); ?></span>
          <h2><?php echo esc_html( $text_l2 ); ?></h2>
        </div>
        <?php echo $download_btns; ?>
      </div>

      <?php echo $after_widget;
    }

    /* = Widget Update Setting
    /*=====================================================*/
    function update( $new_instance, $old_instance ) {
      $instance          = $old_instance;
      $instance['sd_bg_img'] = $new_instance['sd_bg_img'];
      $instance['text_l1'] = $new_instance['text_l1'];
      $instance['text_l2']  = $new_instance['text_l2'];
      $instance['download_btns']  = $new_instance['download_btns'];

      return $instance;
    }

    /* = Widget Form Setting
    /*=====================================================*/
    function form( $instance ) {

      /* - Defaults Value Setting Fields
      /*------------------------------------------------- */
      $instance   = wp_parse_args( $instance, array(

        'sd_bg_img'  => GEOPORT_PLG_URL. 'assets/imgs/services-download_bg.jpg',
        'text_l1'       => 'PDF Files',
        'text_l2'       => 'Download Brochures',
        'download_btns' => '
        <ul>
        <li><a href="#"><i class="fal fa-file-pdf"></i>2020 Boucher <i class="far fa-arrow-right"></i></a></li>
        <li><a href="#"><i class="fal fa-file-pdf"></i>Presentation Boucher <i class="far fa-arrow-right"></i></a></li>
        </ul>',

      ));

      /* - Download Widget Logo Upload Field
      /* ------------------------------------------------- */
      $sd_bg_img_value = esc_attr( $instance['sd_bg_img'] );
      $sd_bg_img_field = array(
        'id'    => $this->get_field_name('sd_bg_img'),
        'name'  => $this->get_field_name('sd_bg_img'),
        'type'  => 'image',
        'title' => __( 'Download Background Image', 'geoport' ),
        'info'  => __( 'Download BG Image Upload Here', 'geoport' ),
      );
      echo geoport_add_element( $sd_bg_img_field, $sd_bg_img_value );


      /* - Widget text line 1 Settings
      /*------------------------------------------------- */
      $text_l1_value = esc_attr( $instance['text_l1'] );
      $text_l1_field = array(
        'id'    => $this->get_field_name('text_l1'),
        'name'  => $this->get_field_name('text_l1'),
        'type'  => 'text',
        'title' => 'Text Line 1',
      );
      echo geoport_add_element( $text_l1_field, $text_l1_value );

      /* - Widget Title Setting
      /*------------------------------------------------- */
      $text_l1_value = esc_attr( $instance['text_l2'] );
      $text_l1_field = array(
        'id'    => $this->get_field_name('text_l2'),
        'name'  => $this->get_field_name('text_l2'),
        'type'  => 'text',
        'title' => 'Text Line 1',
      );
      echo geoport_add_element( $text_l1_field, $text_l1_value );


      /* - Download Button Text
      /* ------------------------------------------------- */
      $download_btns_value = esc_attr( $instance['download_btns'] );
      $download_btns_field = array(
        'id'    => $this->get_field_name('download_btns'),
        'name'  => $this->get_field_name('download_btns'),
        'type'  => 'textarea',
        'title' => 'Dowload button list',
      );
      echo geoport_add_element( $download_btns_field, $download_btns_value );

    }

  }
}

if ( ! function_exists( 'geoport_download_widget_init' ) ) {
  function geoport_download_widget_init() {
    register_widget( 'geoport_download_Widget' );
  }
  add_action( 'widgets_init', 'geoport_download_widget_init', 3 );
}