<?php
Redux::setSection('transland_opt', array(
    'title'     => esc_html__('Social Links', 'transland'),
    'id'        => 'opt_social_links',
    'icon'      => 'dashicons dashicons-share',
    'fields'    => array(
        array(
            'id'    => 'facebook',
            'type'  => 'text',
            'title' => esc_html__('Facebook', 'transland'),
            'default'	 => '#'
        ),
        array(
            'id'    => 'twitter',
            'type'  => 'text',
            'title' => esc_html__('Twitter', 'transland'),
            'default'	  => '#'
        ),
        array(
            'id'    => 'skype',
            'type'  => 'text',
            'title' => esc_html__('Skype', 'transland'),
            'default'	  => '#'
        ),
        array(
            'id'    => 'linkedin',
            'type'  => 'text',
            'title' => esc_html__('LinkedIn', 'transland'),
            'default'	  => '#'
        ),
        array(
            'id'    => 'dribbble',
            'type'  => 'text',
            'title' => esc_html__('Dribbble', 'transland'),
        ),
        array(
            'id'    => 'youtube',
            'type'  => 'text',
            'title' => esc_html__('Youtube', 'transland'),
            'default' => '#'
        ),
        array(
            'id'    => 'instagram',
            'type'  => 'text',
            'title' => esc_html__('Instagram', 'transland'),
        ),
    ),
));