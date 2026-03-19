<?php

function vasia_before_footer(){
	$footer_before = rdt_get_option('footer_before_content', '');
	if($footer_before) {
		?>
		<div class="footer-before">
			<div class="container">
				<?php echo do_shortcode($footer_before); ?>
			</div>
		</div>
		<?php
	}
}
add_action('vasia_footer', 'vasia_before_footer', 5);

function vasia_main_footer(){
	if(!is_active_sidebar('sidebar-footer-column-1') && !is_active_sidebar('sidebar-footer-column-2') && !is_active_sidebar('sidebar-footer-column-3') && !is_active_sidebar('sidebar-footer-column-4')) return;

	$footer_layout = rdt_get_option('footer_layout','layout-4');
	$classes = array();
	$footer_text = rdt_get_option('footer_text', 'dark');
	$classes[] = 'text-'.$footer_text;

	$footer_config = vasia_get_footer_config($footer_layout);

	?>
	<div class="footer-main <?php echo esc_attr(implode(' ', $classes)); ?>">
		<div class="container">
			<div class="row">
				<?php foreach( $footer_config['cols'] as $key => $column ) : 
					$index = $key + 1;
					?>
					<div class="footer-column footer-column-<?php echo esc_attr( $index ); ?> <?php echo esc_attr( $column ); ?>">
						<?php dynamic_sidebar( 'footer-column-'. $index); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php	
}
add_action('vasia_footer', 'vasia_main_footer', 10);

function vasia_get_footer_config($layout){
	$configs = apply_filters( 'vasia_footer_configs_array', array(
			'layout-1' => array(
				'cols' => array(
					'col-12'
				),

			),
			'layout-2' => array(
				'cols' => array(
					'col-12 col-md-6',
					'col-12 col-md-6',
				),
			),
			'layout-3' => array(
				'cols' => array(
					'col-12 col-md-4',
					'col-12 col-md-4',
					'col-12 col-md-4',
				),
			),
			'layout-4' => array(
				'cols' => array(
					'col-12 col-md-6 col-lg-3',
					'col-12 col-md-6 col-lg-3',
					'col-12 col-md-6 col-lg-3',
					'col-12 col-md-6 col-lg-3',
				),
			),
			'layout-5' => array(
				'cols' => array(
					'col-12 col-md-6  col-lg-4',
					'col-12 col-md-6  col-lg-8-3',
					'col-12 col-md-6  col-lg-8-3',
					'col-12 col-md-6  col-lg-8-3',
				),
			),
			'layout-6' => array(
				'cols' => array(
					'col-12 col-md-6  col-lg-4',
					'col-12 col-md-6  col-lg-2',
					'col-12 col-md-6  col-lg-2',
					'col-12 col-md-6  col-lg-4',
				),
			),
			'layout-7' => array(
				'cols' => array(
					'col-12 col-md-6  col-lg-8-3',
					'col-12 col-md-6  col-lg-8-3',
					'col-12 col-md-6  col-lg-8-3',
					'col-12 col-md-6  col-lg-4',
				),
			),
		) );
	return $configs[$layout];
}

function vasia_bottom_footer(){
	$footer_bottom_active = rdt_get_option('footer_bottom_active', true );
	if( !$footer_bottom_active ) return;

	$footer_bottom_left = rdt_get_option('footer_bottom_left', 'copyright');
	$footer_bottom_center = rdt_get_option('footer_bottom_center' , 'none');
	$footer_bottom_right = rdt_get_option('footer_bottom_right', 'none');
	$footer_text_color = rdt_get_option('footer_bottom_text','light');

	?>
	<div class="footer-bottom text-<?php echo esc_attr($footer_text_color); ?>">
		<div class="container">
			<div class="row">
				<?php if ($footer_bottom_left !== 'none') { ?>
					<div class="col footer-bottom-left">
						<?php  vasia_footer_bottom_content($footer_bottom_left); ?>
					</div>
				<?php } ?>
				<?php if ($footer_bottom_center !== 'none') { ?>
					<div class="col footer-bottom-center text-center">
						<?php  vasia_footer_bottom_content($footer_bottom_center); ?>
					</div>
				<?php } ?>
				<?php if ($footer_bottom_right !== 'none') { ?>
					<div class="col footer-bottom-right text-right">
						<?php  vasia_footer_bottom_content($footer_bottom_right); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php	
}
add_action('vasia_footer', 'vasia_bottom_footer', 15);

function vasia_footer_bottom_content($param){
	$copyright = rdt_get_option('footer_bottom_copyright',esc_html__('Copyright © Plazathemes. All Rights Reserved','vasia'));
	$payment = rdt_get_option('footer_bottom_payment', '');
	if($param == 'copyright') {
		?>
			<p><?php echo esc_html($copyright); ?></p>
		<?php
	}else if ($param == 'payment') {
		?>
			<img src="<?php echo esc_url($payment); ?>" alt="<?php echo esc_attr_e('payments','vasia');?>" />
		<?php
	}else if ($param == 'footer-menu') {

		if ( has_nav_menu( 'footer' ) ) {

			wp_nav_menu(
				array(
					'container'  => '',
					'items_wrap' => '%3$s',
					'theme_location' => 'footer',
				)
			);

		}

	}else if ($param == 'social') {
		vasia_social_list();
	}else {
		return;
	}
}