<?php
if ( ! function_exists('rashy_custom_styles') ) {
	function rashy_custom_styles() {
		$main_color = rashy_get_config('main_color');
		if ( empty($main_color) ) {
			$main_color = '#f44a16';
		}

		if ( rashy_get_config('text_color') != "" ) {
			$text_color = rashy_get_config('text_color');
		} else {
			$text_color = '#8d8d8d';
		}

		if ( rashy_get_config('link_color') != "" ) {
			$link_color = rashy_get_config('link_color');
		} else {
			$link_color = '#2b2b2b';
		}

		if ( rashy_get_config('link_hover_color') != "" ) {
			$link_hover_color = rashy_get_config('link_hover_color');
		} else {
			$link_hover_color = '#f44a16';
		}

		if ( rashy_get_config('heading_color') != "" ) {
			$heading_color = rashy_get_config('heading_color');
		} else {
			$heading_color = '#2b2b2b';
		}

		
		$main_color_rgb = rashy_hex2rgb($main_color);

		// font
        $main_font = rashy_get_config('main_font');
		$main_font_family = !empty($main_font['font-family']) ? $main_font['font-family'] : 'Inter Tight';

		$main_font_arr = explode(',', $main_font_family);
		if ( count($main_font_arr) == 1 ) {
			$main_font_family = "'".$main_font_family."'";
		}

		$heading_font = rashy_get_config('heading_font');
		$heading_font_family = !empty($heading_font['font-family']) ? $heading_font['font-family'] : 'Zen Dots';

		$heading_font_arr = explode(',', $heading_font_family);
		if ( count($heading_font_arr) == 1 ) {
			$heading_font_family = "'".$heading_font_family."'";
		}

		ob_start();
		?>
		:root {
		  --rashy-theme-color: <?php echo esc_attr($main_color); ?>;
		  --rashy-text-color: <?php echo trim($text_color); ?>;
		  --rashy-link-color: <?php echo trim($link_color); ?>;
		  --rashy-link-hover-color: <?php echo trim($link_hover_color); ?>;
		  --rashy-heading-color: <?php echo trim($heading_color); ?>;

		  --rashy-main-font: <?php echo trim($main_font_family); ?>;
		  --rashy-heading-font: <?php echo trim($heading_font_family); ?>;
		}
		<?php

		$content = ob_get_clean();
		
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();

		foreach ($lines as $line) {
			if (!empty(trim($line))) {
				$new_lines[] = trim($line);
			}
		}

		return implode("\n", $new_lines);
	}
}
