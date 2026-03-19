<?php global $redux_gauthier;  
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="category1-topheader">
<div <?php body_class(); ?>>
        <div class="category1-topinside">
            <div class="category1-titlewrapper ">
				<h1 class="archive">
					<?php if (is_day()):
					printf(__("Daily Archives %s", "gauthier"),"<span>" . get_the_date() . "</span>");
					elseif (is_month()):
					printf(	__("Monthly Archives %s", "gauthier"),	"<span>" . get_the_date(_x("F Y", "monthly archives date format", "gauthier")) . "</span>");
					elseif (is_year()):
					printf(__("Yearly Archives %s", "gauthier"),"<span>" . get_the_date(_x("Y", "yearly archives date format", "gauthier")) . "</span>");
					else:
					_e("Archives", "gauthier");
					endif; ?>
				</h1>
            </div>

            <div class="bottom-gradientblack"></div>
        </div>
	</div>
</div>
<div class="single-wrapper">
	<div id="primary" <?php body_class("site-content"); ?>>
		<div id="content" role="main">
			<?php if (have_posts()): ?>
			<?php while (have_posts()):
			the_post(); ?>
			<div class="category1-wrapper">
				<div <?php post_class("clearfix"); ?>><?php get_template_part("page-templates/index-content", get_post_format()); ?></div>
			</div>
			<?php endwhile; ?>
			<?php else: ?>
			<?php get_template_part("page-templates/content2", "none"); ?>
			<?php endif; ?>
		</div>
		<?php if (function_exists("gauthier_numbered_pages")) { ?>
		<?php gauthier_numbered_pages(); ?>
		<?php } else { ?>
		<nav>
			<ul class="pager">
				<li class="previous"><?php next_posts_link(esc_attr__("&laquo; Older Entries", "gauthier")); ?></li>
				<li class="next"><?php previous_posts_link(esc_attr__("Newer Entries &raquo;", "gauthier")); ?></li>
			</ul>
		</nav>
		<?php } ?>
	</div>
	<div class="sidebar">
		<?php get_sidebar(); ?>
	</div>
</div>
<?php if (isset($redux_gauthier["footer_layout"])) {
    get_template_part($redux_gauthier["footer_layout"]);
} else {
    get_footer();
}
?>