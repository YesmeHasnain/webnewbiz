<?php 
/* - For Custom Icon Loading
==========================================================*/

function custom_get_flat_icons() {
  return [
    /* - Flat Icons*/
    'geoport-flaticon fab fa-buromobelexperte'=>'fab fa-buromobelexperte',
    'geoport-flaticon flaticon-digital-marketing'=>'flaticon-digital-marketing',
    'geoport-flaticon flaticon-group'=>'flaticon-group',
    'geoport-flaticon flaticon-analytics'=>'flaticon-analytics',
    'geoport-flaticon flaticon-website'=>'flaticon-website',
    'geoport-flaticon flaticon-research'=>'Flaticon Transport',
    'geoport-flaticon flaticon-market'=>'Flaticon Safebox',
    'geoport-flaticon flaticon-software'=>'flaticon-software',
    'geoport-flaticon flaticon-analysis'=>'flaticon-analysis',
    'geoport-flaticon flaticon-pie-chart'=>'flaticon-pie-chart',
    'geoport-flaticon flaticon-value'=>'flaticon-value',
    'geoport-flaticon flaticon-digital-marketing-1'=>'flaticon-digital-marketing-1',
    'geoport-flaticon flaticon-award'=>'flaticon-award',
    'geoport-flaticon flaticon-support'=>'flaticon-support',
    'geoport-flaticon flaticon-contract'=>'flaticon-contract',
    'geoport-flaticon flaticon-analytics-1'=>'flaticon-analytics-1',
    'geoport-flaticon flaticon-award-1'=>'flaticon-award-1',
    'geoport-flaticon flaticon-tea'=>'flaticon-tea',
    'geoport-flaticon flaticon-mouse'=>'flaticon-mouse',
  ];
}

function custom_get_flat_icons_includes() {
  return [
    /* - Flat Icons*/
    'geoport-flaticon fab fa-buromobelexperte',
    'geoport-flaticon flaticon-digital-marketing',
    'geoport-flaticon flaticon-group',
    'geoport-flaticon flaticon-analytics',
    'geoport-flaticon flaticon-website',
    'geoport-flaticon flaticon-research',
    'geoport-flaticon flaticon-market',
    'geoport-flaticon flaticon-software',
    'geoport-flaticon flaticon-analysis',
    'geoport-flaticon flaticon-pie-chart',
    'geoport-flaticon flaticon-value',
    'geoport-flaticon flaticon-digital-marketing-1',
    'geoport-flaticon flaticon-award',
    'geoport-flaticon flaticon-support',
    'geoport-flaticon flaticon-contract',
    'geoport-flaticon flaticon-analytics-1',
    'geoport-flaticon flaticon-award-1',
    'geoport-flaticon flaticon-tea',
    'geoport-flaticon flaticon-mouse',
  ];
}

function font_weight() {
  return [
    '100' => __( '100', 'geoport' ),
    '200' => __( '200', 'geoport' ),
    '300' => __( '300', 'geoport' ),
    '400' => __( '400', 'geoport' ),
    '500' => __( '500', 'geoport' ),
    '600' => __( '600', 'geoport' ),
    '700' => __( '700', 'geoport' ),
    '800' => __( '800', 'geoport' ),
    '900' => __( '900', 'geoport' ),
  ];
  
}

function ifinger_post_orderby_options(){
    $orderby = array(
        'ID' => 'Post Id',
        'author' => 'Post Author',
        'title' => 'Title',
        'date' => 'Date',
        'modified' => 'Last Modified Date',
        'parent' => 'Parent Id',
        'rand' => 'Random',
        'comment_count' => 'Comment Count',
        'menu_order' => 'Menu Order',
    );
    return $orderby;
}

/**  Shortcodes | Image compatibility.
--------------------------------------------------------------------------------------------------- */
function ifinger_grid_columns() {
  return [
    '6'=>'Columns 2',
    '4'=>'Columns 3',
    '3'=>'Columns 4',
  ];
}

/**  Shortcodes | Image compatibility.
--------------------------------------------------------------------------------------------------- */
function ifinger_anim_delay() {
  return [
    '0.2s'=>'0.2s',
    '0.4s'=>'0.4s',
    '0.6s'=>'0.6s',
    '0.8s'=>'0.8s',
    '1s'=>'1s',
    '1.2s'=>'1.2s',
    '1.4s'=>'1.4s',
    '1.6s'=>'1.6s',
    '1.8s'=>'1.8s',
    '2s'=>'2s',
    '2.2s'=>'2.2s',
    '2.4s'=>'2.4s',
    '2.6s'=>'2.6s',
    '2.8s'=>'2.8s',
    '3s'=>'3s',
    '3.2s'=>'3.2s',
    '3.4s'=>'3.4s',
    '3.6s'=>'3.6s',
    '3.8s'=>'3.8s'
  ];
}

/* - For category
==========================================================*/
function geoport_get_category_post(){

  $categories = get_categories( array(
    'orderby' => 'name',
    'parent'  => 0
  ) );
  
  $catlist=[];

  foreach ( $categories as $category ) {
    (int)$catlist[$category->term_id] = $category->name;
  }
  return $catlist;
}

/* - Default Post List
==========================================================*/
if ( ! function_exists( 'default_posts_options' ) ) :

  function default_posts_options(){

  $args = array('post_type' => 'post', 'posts_per_page' => -1);

    // $servicelist=[];
    $servicelist = array( esc_html__('-- Select Post --', 'geoport') => 'Select Post Link' );
    
    if( $services = get_posts($args)){
      foreach ( $services as $service ) {
        (int)$servicelist[$service->ID] = $service->post_title;
      }
    } else{
        (int)$servicelist['0'] = esc_html__('None Found, Do post', 'geoport');
    }
  return $servicelist;
  }

endif;

/* - Services Post List
==========================================================*/
if ( ! function_exists( 'service_posts_options' ) ) :

  function service_posts_options(){
    $args = array('post_type' => 'service', 'posts_per_page' => -1);

      // $servicelist=[];

      $servicelist = array( esc_html__('-- Select Post --', 'geoport') => 'Select Service Link' );
      
      if( $services = get_posts($args)){
        foreach ( $services as $service ) {
          (int)$servicelist[$service->ID] = $service->post_title;
        }
      } else{
          (int)$servicelist['0'] = esc_html__('None Found, Do service post', 'geoport');
      }
    return $servicelist;
  }
endif;

/* - Services Post List
==========================================================*/
if ( ! function_exists( 'team_posts_options' ) ) :

  function team_posts_options(){
    $args = array('post_type' => 'team', 'posts_per_page' => -1);

      // $teamlist=[];

      $teamlist = array( esc_html__('-- Select Post --', 'geoport') => 'Select Team Link' );
      
      if( $teams = get_posts($args)){
        foreach ( $teams as $team ) {
          (int)$teamlist[$team->ID] = $team->post_title;
        }
      } else{
          (int)$teamlist['0'] = esc_html__('None Found, Do team post', 'geoport');
      }
    return $teamlist;
  }
endif;

/* - For custom category slug & category name
==========================================================*/

if ( ! function_exists( 'geoport_causes_categories_slug_no_comma' ) ) :
  /**
   * Getting Custome taxanomy for project - category- single project for filtering
   */
  function geoport_causes_categories_slug_no_comma() {
    $categories = get_the_terms( get_the_ID(), 'cause_category' );
      if(!empty($categories)){
        foreach( $categories as $category ) {
            echo $category->slug.' ';
        }
      }
  }
endif;

if ( ! function_exists( 'geoport_cases_categories' ) ) :
  /**
   * Getting Custome taxanomy for project - category- single project
   */
  function geoport_cases_categories() {
      $terms = get_terms( 'cause_category' );
      if(!empty($terms)){
      $category_links = array();
      foreach ($terms as $key => $value) {
        $category_links[$value->term_id] = $value->name;  
      }
      return $category_links;
    }
  }
endif;

/* - For custom category slug & category name
==========================================================*/

if ( ! function_exists( 'geoport_faq_categories_slug_no_comma' ) ) :
  /**
   * Getting Custome taxanomy for gallery - category- single gallery for filtering
   */
  function geoport_faq_categories_slug_no_comma() {
      $categories = get_the_terms( get_the_ID(), 'faq_tax' );
      if(!empty($categories)){
      foreach( $categories as $category ) {
          echo $category->slug.' ';
      }
    }
  }
endif;

/* - For custom category slug & category name
==========================================================*/
if ( ! function_exists( 'geoport_events_categories' ) ) :
  /**
   * Getting Custome taxanomy for project - category- single project
   */
  function geoport_events_categories() {
    $terms = get_terms( 'event_category' );
    if(!empty($terms)){
      $category_links = array();

      foreach ($terms as $key => $value) {
        $category_links[$value->term_id] = $value->name;  
      }
      return $category_links;
    }
  }
endif;

/* - Elementor Default add
==========================================================*/

function geoport_add_cpt_support() {
    
    //if exists, assign to $cpt_support var
  $cpt_support = get_option( 'elementor_cpt_support' );
  
  //check if option DOESN'T exist in db
  if( ! $cpt_support ) {
      $cpt_support = [ 'page', 'post', 'service','team' ]; //create array of our default supported post types
      update_option( 'elementor_cpt_support', $cpt_support ); //write it to the database
  }
  
  //if it DOES exist, but service is NOT defined
  else if( ! in_array( 'service', $cpt_support ) ) {
      $cpt_support[] = 'service'; //append to array
      update_option( 'elementor_cpt_support', $cpt_support ); //update database
  }

   //if it DOES exist, but service is NOT defined
  else if( ! in_array( 'team', $cpt_support ) ) {
      $cpt_support[] = 'team'; //append to array
      update_option( 'elementor_cpt_support', $cpt_support ); //update database
  }
  
  //otherwise do nothing, service already exists in elementor_cpt_support option
}
add_action( 'after_switch_theme', 'geoport_add_cpt_support' );



/**
 * Get a translatable string with allowed html tags.
 *
 * @param string $level Allowed levels are basic and intermediate
 * @return string
 */
function tp_get_allowed_html_desc($level = 'basic'){
    if (!in_array($level, ['basic', 'intermediate', 'advance'])) {
        $level = 'basic';
    }

    $tags_str = '<' . implode('>,<', array_keys(tp_get_allowed_html_tags($level))) . '>';
    return sprintf(__('This input field has support for the following HTML tags: %1$s', 'tpcore'), '<code>' . esc_html($tags_str) . '</code>');
}


/**
 * Get a list of all the allowed html tags.
 *
 * @param string $level Allowed levels are basic and intermediate
 * @return array
 */
function tp_get_allowed_html_tags($level = 'basic'){
    $allowed_html = [
        'b' => [],
        'i' => [
            'class' => [],
        ],
        'u' => [],
        'em' => [],
        'br' => [],
        'abbr' => [
            'title' => [],
        ],
        'span' => [
            'class' => [],
        ],
        'strong' => [],
    ];

    if ($level === 'intermediate') {
        $allowed_html['a'] = [
            'href' => [],
            'title' => [],
            'class' => [],
            'id' => [],
            'target' => [],
        ];
    }

    if ($level === 'advance') {
        $allowed_html['ul'] = [
            'class' => [],
            'id' => [],
        ];
        $allowed_html['ol'] = [
            'class' => [],
            'id' => [],
        ];
        $allowed_html['li'] = [
            'class' => [],
            'id' => [],
        ];
        $allowed_html['a'] = [
            'href' => [],
            'title' => [],
            'class' => [],
            'id' => [],
            'target' => [],
        ];

    }

    return $allowed_html;
}