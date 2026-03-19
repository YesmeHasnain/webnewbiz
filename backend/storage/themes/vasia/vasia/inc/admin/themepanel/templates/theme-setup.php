<?php
	$array_imports = array(
		'home1' => array(
			'page_name' => 'Home 01',
			'demo_url' => 'http://wp.plazathemes.com/vasia/'
		),
		'home2' => array(
			'page_name' => 'Home 02', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/home-page-02/'
		),
		'home3' => array(
			'page_name' => 'Home 03',
			'demo_url' => 'http://wp.plazathemes.com/vasia/home-page-03/'
		),
		'home4' => array(

			'page_name' => 'Home 04', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/home-page-04/'
		),
		'home5' => array(

			'page_name' => 'Home 05', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/shoes/'
		),
		'home6' => array(

			'page_name' => 'Home 06', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/shoes/home-page-06/'
		),
		'home7' => array(

			'page_name' => 'Home 07', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/shoes/home-page-07/'
		),
		'home8' => array(

			'page_name' => 'Home 08', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/shoes/home-page-08/'
		),
		'home9' => array(

			'page_name' => 'Home 09', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/bag/'
		),
		'home10' => array(

			'page_name' => 'Home 10', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/bag/home-page-10/'
		),
		'home11' => array(

			'page_name' => 'Home 11', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/bag/home-page-11/'
		),
		'home12' => array(

			'page_name' => 'Home 12', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/bag/home-page-12/'
		),
		'home13' => array(

			'page_name' => 'Home 13', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/glasses/'
		),
		'home14' => array(

			'page_name' => 'Home 14', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/glasses/home-page-14'
		),
		'home15' => array(

			'page_name' => 'Home 15', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/glasses/home-page-15'
		),
		'home16' => array(

			'page_name' => 'Home 16', 
			'demo_url' => 'http://wp.plazathemes.com/vasia/glasses/home-page-16'
		),
		
	);
?>
<section class="rdt-themesetup-panel">
	<div class="rdt-themesetup-wrapper">
		<div class="rdt-themesetup-header">
			<img src="<?php echo VASIA_THEME_URI; ?>/inc/admin/themepanel/images/vasia.png" alt="<?php esc_attr_e( 'vasia', 'vasia' ); ?>" />
			<p><?php esc_html_e( 'Welcome to import demo page.	 Here you can select the demo which you want to use', 'vasia' ); ?></p>
			<p><?php esc_html_e( 'Click import to start import demo', 'vasia' ); ?></p>
		</div>
		<div class="rdt-themesetup-content rdt-demo-list">
			<?php
				foreach($array_imports as $key => $val) : ?>
					<div class="rdt-item">
						<div class="rdt-item__image">
							<img src="<?php echo VASIA_THEME_URI; ?>/inc/admin/themepanel/images/<?php echo esc_attr($key); ?>.jpg" alt="<?php echo esc_attr($val['page_name']); ?>" />
							<h3><?php echo esc_attr($val['page_name']); ?></h3>
						</div>
						<div class="rdt-item__buttons">
							<a href="<?php echo esc_url($val['demo_url']); ?>" class="rdt-btn" target="_blank"><?php esc_html_e( 'Preview', 'vasia' ); ?></a>
							<button class="button-primary button button-large rdt-start-import" data-demo="<?php echo esc_attr($key); ?>" data-demo-name="<?php echo esc_attr($val['page_name']); ?>"><?php esc_html_e( 'Import', 'vasia' ); ?></button>
						</div>
					</div>
				<?php endforeach;
			?>
		</div>
	</div>
	<div class="rdt-popup-import"></div>
	<div class="rdt-popup-overlay"></div>
</section>