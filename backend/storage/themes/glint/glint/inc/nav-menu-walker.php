<?php

if (!function_exists('glint_return')) :
	function glint_return($arg)
	{
		return $arg;
	}
endif;

class Glint_Nav_Menu_Walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		global $wp_query;
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$class_names = $value = '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
		$class_names = ' class="' . esc_attr($class_names) . '"';

		$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';

		if (glint_detect_homepage() == true) {
			$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
		} else {
			if ($item->type_label == 'Custom Link') {
				$attributes .= !empty($item->url) ? ' href="'  .  esc_attr($item->url) . '"' : '';
			} else {
				$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . do_shortcode(apply_filters('the_title', $item->title, $item->ID)) . $args->link_after;
		$item_output .= '<span class="sub">' . do_shortcode($item->description) . '</span>';
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	public static function fallback($args)
	{
		if (current_user_can('manage_options')) {
			extract($args);
			$fb_output = null;
			if ($container) {
				$fb_output = '<' . $container;
				if ($container_id) {
					$fb_output .= ' id="' . $container_id . '"';
				}
				if ($container_class) {
					$fb_output .= ' class="menu-fallback ' . $container_class . '"';
				}
				$fb_output .= '>';
			}
			$fb_output .= '<ul';
			if ($menu_id) {
				$fb_output .= ' id="' . $menu_id . '"';
			}
			if ($menu_class) {
				$fb_output .= ' class="' . $menu_class . '"';
			}
			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url('nav-menus.php') . '">Add a menu</a></li>';
			$fb_output .= '</ul>';
			if ($container) {
				$fb_output .= '</' . $container . '>';
			}
			echo glint_return($fb_output);
		}
	}
}
