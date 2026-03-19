<?php
/**
 * Blog Post Main File.
 *
 * @package EARLS
 * @author  Template Path
 * @version 1.0
 */

get_header();
$options = earls_WSH()->option();

$data    = \EARLS\Includes\Classes\Common::instance()->data( 'single' )->get();
$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
if (is_active_sidebar( $sidebar )) {$layout = 'right';} else{$layout = 'full';}
$class = ( !$layout || $layout == 'full' ) ? 'col-lg-12 col-sm-12 col-md-12' : 'col-lg-7 col-md-12 col-sm-12';


if ( class_exists( '\Elementor\Plugin' ) && $data->get( 'tpl-type' ) == 'e') {
	
	while(have_posts()) {
	   the_post();
	   the_content();
    }

} else {
?>

<?php if ( $data->get( 'enable_banner' ) ) : ?>
	<?php do_action( 'earls_banner', $data );?>
<?php else:?>
<!-- page-title --> 
<section class="page-title centred" >
   <div class="bg-layer parallax-bg" data-parallax='{"y": 150}' style="background-image: url('<?php echo esc_url( $data->get( 'banner' ) ); ?>');">
   </div>
   <div class="auto-container">
        <div class="content-box">
            <div class="title">
                <h1><?php if( $data->get( 'title' ) ) echo wp_kses( $data->get( 'title' ), true ); else( the_title( ) ); ?></h1>
            </div>
        </div>
    </div>
</section>
<!-- page-title end -->
<?php endif;?>

<!-- sidebar-page-container -->
<section class="sidebar-page-container pt_120 pb_120">
    <div class="medium-container">
        <div class="row">
        	<?php
				if ( $data->get( 'layout' ) == 'left' ) {
					do_action( 'earls_sidebar', $data );
				}
			?>
            <div class="content-side <?php echo esc_attr( $class ); ?>">
            	
				<?php while ( have_posts() ) : the_post(); ?>				
                <div class="blog-details-content ">               	
                    <div class="thm-unit-test"> 
                    	
                        <div class="inner-box">
                            <?php if($options->get('single_post_author') || $options->get('single_post_date') || $options->get('single_post_comments')){ ?>
                            <div class="news-block-one">
                                <ul class="post-info clearfix p-0">
                                    <?php if($options->get('single_post_author')){ ?><li><a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID') )); ?>"><?php the_author(); ?></a> </li><?php } ?>
                                    <?php if($options->get('single_post_date')){ ?><li><a href="<?php echo get_month_link(get_the_date('Y'), get_the_date('m')); ?>"><i class="fal fa-calendar"></i> <?php echo get_the_date(); ?> </a> </li><?php } ?>
                    				<?php if($options->get('single_post_comments')){ ?><li><i class="far fa-comments-alt"></i> <?php comments_number( wp_kses(__('0 Comments' , 'earls'), true), wp_kses(__('01 Comment' , 'earls'), true), wp_kses(__('0% Comments' , 'earls'), true)); ?></li><?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                            
                            <div class="text">
								<?php the_content(); ?>
                            </div>
                            <div class="clearfix"></div>
                            <?php wp_link_pages(array('before'=>'<div class="paginate-links m-t30">'.esc_html__('Pages: ', 'earls'), 'after' => '</div>', 'link_before'=>'<span>', 'link_after'=>'</span>')); ?>
                                                            
                        </div>
                        
                        <?php if(has_tag() || function_exists('bunch_share_us_two')){ ?>
                        <div class="blog-details tags-widget">
                            <?php if(has_tag()){ ?>
                            <div class="left">
                                <div class="widget-title">
                                    <p><?php esc_html_e('Tags:', 'earls'); ?></p>
                                </div>
                                <div class="widget-content">
                                    <ul class="tags-list clearfix">
                                        <?php the_tags( '<li class="theme-btn one">', ', </li><li class="theme-btn one">', '</li>' ); ?>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if(function_exists('bunch_share_us_two')){ ?>
                            <?php echo wp_kses(bunch_share_us_two(get_the_id(),$post->post_name ), true);?>
                            <?php } ?>
                            
                        </div>
                        <?php } ?>
                        
                        <?php if( $options->get( 'single_post_author_box' )){ ?>
                        <div class="comments___section">
                            <div class="comment">
                                <?php if($avatar = get_avatar(get_the_author_meta('ID')) !== FALSE): ?>
                                <figure class="thumb-box"><?php echo get_avatar(get_the_author_meta('ID'), 100); ?></figure>
                                <?php endif; ?>
                                <div class="comment-inner">
                                    <div class="comment-info">
                                        <p class="body__one"><?php the_author(); ?></p>
                                    </div>
                                    <p><?php the_author_meta( 'description', get_the_author_meta('ID') ); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                          
                        <!--End post-details-->
                        <?php comments_template(); ?>
                        
                	</div>
					<!--End thm-unit-test-->
                </div>
                <!--End blog-content-->
				<?php endwhile; ?>
                
            </div>
        	<?php
				if ( $data->get( 'layout' ) == 'right' ) {
					do_action( 'earls_sidebar', $data );
				}
			?>
        </div>  
    </div>
</section>
<!--End blog area--> 

<?php
}
get_footer();
