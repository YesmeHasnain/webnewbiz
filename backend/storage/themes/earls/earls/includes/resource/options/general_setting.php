<?php
$styles = [];
foreach(range(1, 28) as $val) {
    $styles[$val] = sprintf(esc_html__('Style %s', 'earls'), $val);
}

return  array(
    'title'      => esc_html__( 'General Setting', 'earls' ),
    'id'         => 'general_setting',
    'desc'       => '',
    'icon'       => 'el el-wrench',
    'fields'     => array(
        array(
            'id' => 'body_border_line',
            'type' => 'switch',
            'title' => esc_html__('Enable Home Page Border Line', 'earls'),
            'default' => false,
        ),
		array(
            'id' => 'theme_preloader',
            'type' => 'switch',
            'title' => esc_html__('Enable Preloader', 'earls'),
            'default' => false,
        ),
		array(
			'id'      => 'preloader_btn_text',
			'type'    => 'text',
			'title'   => __( 'Preloader Close Button Text', 'earls' ),
			'required' => array( 'theme_preloader', '=', true ),
		),
		array(
			'id'      => 'preloader_text',
			'type'    => 'textarea',
			'title'   => __( 'Preloader Text', 'earls' ),
			'required' => array( 'theme_preloader', '=', true ),
		),
    ),
);
