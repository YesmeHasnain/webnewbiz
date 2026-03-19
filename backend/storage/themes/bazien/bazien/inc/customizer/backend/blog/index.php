<?php

// ============================================
// Panel
// ============================================

Kirki::add_panel( 'panel_blog', array(
    'title'         => esc_html__( 'Blog', 'bazien' ),
    'priority'      => 50,
) );


// ============================================
// Sections
// ============================================

Kirki::add_section( 'blog', array(
    'title'          => esc_html__( 'Blog Archives', 'bazien' ),
    'priority'       => 50,
    'capability'     => 'edit_theme_options',
    'panel'          => 'panel_blog'
) );

Kirki::add_section( 'blog_single', array(
    'title'          => esc_html__( 'Single Post', 'bazien' ),
    'priority'       => 50,
    'capability'     => 'edit_theme_options',
    'panel'          => 'panel_blog'
) );


// ============================================
// Controls
// ============================================

require_once('archives.php');
require_once('single.php');
