<?php 

$blog_single_cat = saasten_get_option('blog_single_cat');
$blog_single_author= saasten_get_option('blog_single_author', false);
$blog_single_navigation = saasten_get_option('blog_nav');
$blog_single_related = saasten_get_option('blog_related', false);
$blog_single_taglist = saasten_get_option('blog_tags');
$blog_single_views = saasten_get_option('blog_views');

?>



	<!-- BreadCrumb -->
    <div class="breadcrumb-area saastain-bg__cover" style="background-image: url('<?php echo SAASTEN_IMG ."/breadcrumb.png"; ?>')">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12">
            <div class="page-banner-content text-center">
              <h3
                class="page-banner-heading saastain-gsap-anim3"
                data-aos="fade-in"
              >
                <?php esc_html_e('Blog Single', 'saasten'); ?>
              </h3>
              <!-- Breadcrumb Start-->
              <nav aria-label="breadcrumb">
                <ol
                  class="breadcrumb justify-content-center"
                  data-aos="fade-in"
                >
                  <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'saasten'); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    <?php esc_html_e('Blog Single', 'saasten'); ?>
                  </li>
                </ol>
              </nav>
              <!-- Breadcrumb End-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End BreadCrumb -->


<div id="main-content" class="bloglayout__One main-container blog-single post-layout-style2 single-one-bwrap"  role="main">
	<div class="container">
		<div class="row single-blog-content">

		<div class="<?php if(is_active_sidebar('sidebar-1')) { echo "col-lg-8"; } else { echo "col-lg-12";}?> col-md-12">
		<?php while (have_posts()):
		the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(["post-content", "post-single"]); ?>>

							
				<div class="theme-blog-details">
				
				<?php if ( has_post_thumbnail() && !post_password_required() ) : ?>
				<div class="post-featured-image">
				<?php if(get_post_format()=='video'): ?>
					<?php get_template_part( 'template-parts/single/post', 'video' ); ?>  
					<?php else: ?>
					<img class="img-fluid" src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php the_title_attribute(); ?>">
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Blog Meta -->
				<div class="blog-single-meta">
					<li>
					  <span><img src="<?php echo SAASTEN_IMG ."/author-icon.svg"; ?>" /></span>
					  <span> by <?php echo get_the_author_link(); ?></span>
					</li>
					<li>
					  <span><img src="<?php echo SAASTEN_IMG ."/calendar-icon.svg"; ?>" /></span>
					  <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
					</li>
				</div>

				<h1 class="post-title single_blog_inner__Title">
				<?php the_title(); ?>
				</h1>


				
				<div class="post-body clearfix single-blog-header single-blog-inner blog-single-block blog-details-content">
					<!-- Article content -->
					<div class="entry-content clearfix">
						
						<?php
						if ( is_search() ) {
							the_excerpt();
						} else {
							the_content();
							saasten_link_pages();
						}
						?>
						
					<?php if(has_tag() && $blog_single_taglist == true ) : ?>
					<div class="post-footer clearfix theme-tag-list-wrapp">
						<?php saasten_single_post_tags(); ?>
					</div>
					 
					<?php endif; ?>	

					</div>
				</div>
				
				</div>
							
			</article>
					   
				<?php if($blog_single_author == true) :?>
					<?php saasten_theme_author_box(); ?>
				<?php endif; ?>
			   
				<?php if($blog_single_navigation == true) :?>
					<?php saasten_theme_post_navigation(); ?>
				<?php endif; ?>

				<?php comments_template(); ?>
				<?php endwhile; ?>
			</div>
					
			<?php get_sidebar(); ?>

		</div>
		
		<?php if($blog_single_related == true) :?>
		<div class="theme_related_posts_Wrapper">
			
			<div class="row">
				<div class="col-md-12">
					<?php get_template_part('template-parts/single/related', 'posts'); ?>
				</div>
			</div>
			
		</div>
		<?php endif; ?>
	</div> 
	
</div>






