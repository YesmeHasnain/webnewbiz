<?php if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="single2-wrapper">
	<div class="error-wrapper">
		<div id="content" role="main">
			<header class="entry-header">
				<h1 class="entry-title">
					<?php if (isset($redux_gauthier["jp_404text1"])) { ?>
					<?php echo esc_html($redux_gauthier["jp_404text1"]); ?>
					<?php } ?>
				</h1>
				<?php if (isset($redux_gauthier["jp_404text2"])) { ?>
				<?php echo esc_html($redux_gauthier["jp_404text2"]); ?>
				<?php } ?>
			</header>
			<div class="error-image">
				<?php if (isset($redux_gauthier["jp_404image"]["url"]) && !empty($redux_gauthier["jp_404image"]["url"]) ) { ?>
				<a href="<?php echo esc_url(home_url("/")); ?>"><img alt="<?php echo get_bloginfo("name"); ?>" src="<?php echo esc_url($redux_gauthier["jp_404image"]["url"]); ?>"></a>
				<?php } elseif ( isset($redux_gauthier["opt_header_text"]) &&!empty($redux_gauthier["opt_header_text"])  ) { ?>
				<h1><a href="<?php echo esc_url(home_url("/")); ?>"> <?php echo esc_html($redux_gauthier["opt_header_text"]); ?></a></h1>
				<?php } else { ?>
				<a href="<?php echo esc_url(home_url("/")); ?>"><img alt="<?php echo get_bloginfo("name"); ?>" src="<?php echo esc_url( get_template_directory_uri() . "/images/404.png"); ?>"></a>
				<?php } ?>
			</div>			
			<div class="error-cat">
				<div class="select-inside">
					<div class="d-flex align-items-center">
						<div class="col-md-6 cattitle">
							<?php wp_dropdown_categories( array('show_option_none' => esc_attr__( 'Select category', 'gauthier' )) ); ?>
							<script type="text/javascript">
								var dropdown = document.getElementById("cat");
								function onCatChange() {
									if (dropdown.options[dropdown.selectedIndex].value > 0) {
										location.href = "<?php echo esc_url(home_url("/") ); ?>/?cat=" + dropdown.options[dropdown.selectedIndex].value;
									}
								}
								dropdown.onchange = onCatChange;
							</script>
						</div>
						<div class="col-md-6">
							<select name="page-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
								<option value="">
									<?php esc_html_e("Select page", "gauthier"); ?>
								</option>
								<?php
									$pages = get_pages();
									foreach ($pages as $pagg) {
										$option =
										'<option value="' . esc_attr(get_page_link($pagg->ID)) . '">';
										$option .= $pagg->post_title;
										$option .= "</option>";
										echo esc_html(ent2ncr($option));
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<p><?php _e("Kindly search your topic below or browse the recent posts.", "gauthier" ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>
</div>
<?php if (isset($redux_gauthier["footer_layout"])) {
    get_template_part($redux_gauthier["footer_layout"]);
} else {
    get_footer();
}
?>