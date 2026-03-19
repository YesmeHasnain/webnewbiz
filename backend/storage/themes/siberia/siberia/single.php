<?php
/**
 * @author: MadSparrow
 * @version: 1.0
 */

get_header();

$a_id = $post->post_author;
$prev = get_previous_post();
$next = get_next_post();
$related_cats_post = siberia_related_posts(); ?>

<div class="ms-single-post">

	<header class="ms-sp--header">
		<?php the_title( '<h1 class="ms-sp--title">', '</h1>' ); ?>
		<div class="post-meta-date meta-date-sp">
			<span class="post-author__name"><?php the_author_meta( 'display_name', $a_id ); ?></span>
			<span><?php echo get_the_date(); ?></span>
			<span class="post-category link"><?php echo the_category(',&nbsp;'); ?></span>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="ms-single-post--img">
			<figure class="media-wrapper media-wrapper--21:9">
				<?php the_post_thumbnail('siberia-featured-single-post', array( 'alt' => get_the_title())); ?>
			</figure>
		</div>
	<?php endif; ?>

	<article class="ms-sp--article">
		<div class="entry-content">
			<?php while ( have_posts() ) : the_post();
				the_content( sprintf( __( 'Continue reading %s', 'siberia' ), the_title( '<span class="screen-reader-text">', '</span>', false ) ) );
				siberia_link_pages();
			endwhile; ?>
		</div>
	</article>
	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer default-max-width">
			<?php edit_post_link(
				sprintf( '<span class="meta-icon"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="" d="M14.7272727,11.1763636 C14.7272727,10.7244943 15.0935852,10.3581818 15.5454545,10.3581818 C15.9973239,10.3581818 16.3636364,10.7244943 16.3636364,11.1763636 L16.3636364,15.5454545 C16.3636364,16.9010626 15.2646989,18 13.9090909,18 L2.45454545,18 C1.09893743,18 0,16.9010626 0,15.5454545 L0,4.09090909 C0,2.73530107 1.09893743,1.63636364 2.45454545,1.63636364 L6.82363636,1.63636364 C7.2755057,1.63636364 7.64181818,2.00267611 7.64181818,2.45454545 C7.64181818,2.9064148 7.2755057,3.27272727 6.82363636,3.27272727 L2.45454545,3.27272727 C2.00267611,3.27272727 1.63636364,3.63903975 1.63636364,4.09090909 L1.63636364,15.5454545 C1.63636364,15.9973239 2.00267611,16.3636364 2.45454545,16.3636364 L13.9090909,16.3636364 C14.3609602,16.3636364 14.7272727,15.9973239 14.7272727,15.5454545 L14.7272727,11.1763636 Z M6.54545455,9.33890201 L6.54545455,11.4545455 L8.66109799,11.4545455 L16.0247344,4.09090909 L13.9090909,1.97526564 L6.54545455,9.33890201 Z M14.4876328,0.239639906 L17.7603601,3.51236718 C18.07988,3.83188705 18.07988,4.34993113 17.7603601,4.669451 L9.57854191,12.8512692 C9.42510306,13.004708 9.21699531,13.0909091 9,13.0909091 L5.72727273,13.0909091 C5.27540339,13.0909091 4.90909091,12.7245966 4.90909091,12.2727273 L4.90909091,9 C4.90909091,8.78300469 4.99529196,8.57489694 5.14873082,8.42145809 L13.330549,0.239639906 C13.6500689,-0.0798799688 14.1681129,-0.0798799688 14.4876328,0.239639906 Z"></path></svg>' . esc_html__( 'Edit %s', 'siberia' ) . '</span>',
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				), '<span class="edit-link">', '</span>'
			); ?>
		</footer>
	<?php endif; ?>
	<div class="single-post__tags"><?php the_tags( '', '', '' ); ?></div>
	<?php if (!empty($prev) OR !empty($next)) : ?>
	<nav class="navigation post-navigation">
		<div class="nav-links">
			<div class="nav-previous">
				<?php if (!empty($prev)) : ?>
					<a href="<?php echo get_permalink($prev->ID); ?>" rel="prev">
						<div class="prev-post">
							<?php if (get_the_post_thumbnail($prev->ID)) : ?>
								<div class="ms-spp--i">
									<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title></title><g data-name="1" id="_1"><path d="M202.1,450a15,15,0,0,1-10.6-25.61L365.79,250.1,191.5,75.81A15,15,0,0,1,212.71,54.6l184.9,184.9a15,15,0,0,1,0,21.21l-184.9,184.9A15,15,0,0,1,202.1,450Z"></path></g></svg>
									<?php echo get_the_post_thumbnail($prev->ID, array(80,80), array( 'alt' => $prev->post_title )); ?>
								</div>
							<?php endif; ?>
							<div class="ms-spp">
								<span class="nav-label" aria-hidden="true"><?php esc_html_e('Previous Article', 'siberia'); ?></span>
								<h3 class="post-title"><?php echo esc_html($prev->post_title); ?></h3>
							</div>
						</div>
					</a>
				<?php endif ?>
			</div>
			<div class="nav-next">
				<?php if (!empty($next)) : ?>
					<a href="<?php echo get_permalink($next->ID); ?>" rel="next">
						<div class="next-post">
							<div class="ms-spn">
								<span class="nav-label" aria-hidden="true"><?php esc_html_e('Next Article', 'siberia'); ?></span>
								<h3 class="post-title"><?php echo esc_html($next->post_title); ?></h3>
							</div>
							<?php if (get_the_post_thumbnail($next->ID)) : ?>
								<div class="ms-spn--i">
									<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title></title><g data-name="1" id="_1"><path d="M202.1,450a15,15,0,0,1-10.6-25.61L365.79,250.1,191.5,75.81A15,15,0,0,1,212.71,54.6l184.9,184.9a15,15,0,0,1,0,21.21l-184.9,184.9A15,15,0,0,1,202.1,450Z"></path></g></svg>
									<?php echo get_the_post_thumbnail($next->ID, array(80,80), array( 'alt' => $next->post_title )); ?>
								</div>
							<?php endif; ?>
						</div>
					</a>
				<?php endif ?>
			</div>
		</div>
	</nav>
	<?php endif; ?>
</div>

<?php if($related_cats_post->have_posts()): ?>
	<section class="ms-related-posts">
		<div class="alignwide">
			<h2 class="ms-rp--title"><?php esc_html_e('Related Posts', 'siberia'); ?></h2>
			<div class="row">
				<?php while($related_cats_post->have_posts()): $related_cats_post->the_post(); ?>
					<article class="col-md-6 col-lg-6">
						<?php if( has_post_thumbnail() ):?>
							<div class="ms-rp--thumb">
								<figure class="card__img media-wrapper media-wrapper--16:9">
									<?php the_post_thumbnail('siberia-card-post-thumb', array( 'alt' => get_the_title())); ?>
								</figure>
							</div>
						<?php endif; ?>
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute (); ?>" class="ms-rp--cont">
								<div class="ms-rp--text">
									<span class="ms-rp__date"><?php echo get_the_date(); ?></span>
									<h3 class="ms-rp__title"><?php the_title(); ?></h3>
								</div>
							</a>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( comments_open() || get_comments_number() ) : ?>
	<?php comments_template(); ?>
<?php endif; ?>

<?php get_footer(); ?>