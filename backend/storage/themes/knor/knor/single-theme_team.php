<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package knor
 */

get_header();

?>


    <div class="theme-breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner">
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'knor'); ?></a></li><span class="breadcrumb-divider">/</span>
                            <li><?php the_title(); ?></li>
                        </ul>
                    </div>

                    <h1 class="theme-breacrumb-title">
						<?php the_title(); ?>
					</h1>

                </div>
            </div>
        </div>
    </div>


    <div id="main-content" class="main-container theme-page-spacing theme-team-page-wrap" role="main">
		<div class="container">   
			<div class="row">
				<div class="col-lg-12">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="single-content">
							<div class="entry-content">
								
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<div class="post-body clearfix">
										<!-- Article content -->
										<div class="entry-content clearfix">
										<?php
											the_content();

											wp_link_pages( array(
												'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knor' ),
												'after'  => '</div>',
											) );
										?>	
										</div>
								    </div> 
								</article>

								<?php 
									// If comments are open or we have at least one comment, load up the comment template.
									if ( comments_open() || get_comments_number() ) :
										comments_template();
									endif;
								?>	
								
							</div>
						</div>
						
					<?php endwhile; ?>
				</div> 
			</div> 
		</div> 
	</div>


<?php get_footer(); ?>
