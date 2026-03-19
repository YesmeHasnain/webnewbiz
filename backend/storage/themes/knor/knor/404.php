<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package knor
 */
 
get_header();

?>

	<section id="main-container" class="blog main-container error-page-container">
		<div class="container">
			<div class="row error-row">

			   <div class="col-lg-6">
				  <div class="error-page">
					 <div class="error-code">
						<h2><?php esc_html_e('404', 'knor'); ?></h2>
					 </div>
					 <div class="error-message">
						<h3><?php esc_html_e('Ooops!', 'knor'); ?></h3>
						<h3><?php esc_html_e('Page Not Found', 'knor'); ?></h3>
					 </div>

					 <div class="error-page-description">
					 	<p><?php esc_html_e('This page dosen’t exist or was removed! We suggest you back to home', 'knor'); ?></p>
					 </div>
					 <div class="error-bottom">
						<a href="<?php echo esc_url(home_url('/')); ?>" class="error-btn-custom"><?php esc_html_e('Back To HomePage', 'knor'); ?></a>
					 </div>
				  </div>
			   </div>

			   <div class="col-lg-6">
			   		<div class="error-thumb">
			   			<img src="<?php echo KNOR_IMG.'/error-thumb.png';?>" alt="<?php the_title_attribute(); ?>">
			   		</div>
			   </div>


			</div>
		</div>
	</section>

<?php get_footer(); ?>
