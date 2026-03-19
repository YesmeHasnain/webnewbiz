<?php
   $rival_redux_demo = get_option('redux_demo');
   get_header(); 
?>
<?php 
    while (have_posts()): the_post();
?>
<?php $author_title = get_post_meta(get_the_ID(),'_cmb_author_title', true); ?>
<?php $author_img = get_post_meta(get_the_ID(),'_cmb_author_img', true); ?>
<?php $author_name = get_post_meta(get_the_ID(),'_cmb_author_name', true); ?>
<?php $author_job = get_post_meta(get_the_ID(),'_cmb_author_job', true); ?>
<?php $author_desc = get_post_meta(get_the_ID(),'_cmb_author_desc', true); ?>
<div class="breadcrumb__area breadcrumb__height grey-bg p-relative">
 <div class="container">
    <div class="row">
       <div class="col-xxl-12">
          <div class="breadcrumb__content text-center z-index">
             <div class="breadcrumb__list mb-10">
                <span><a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php if(isset($rival_redux_demo['home'])){?>
                    <?php echo esc_attr($rival_redux_demo['home']);?>
                    <?php }else{?>
                    <?php echo esc_html__( 'Home', 'rival' ); } ?>
                </a></span>
                <span class="dvdr">\</span>
                <span>
                    <?php if(isset($rival_redux_demo['subtitle_blog_single'])){?>
                    <?php echo esc_attr($rival_redux_demo['subtitle_blog_single']);?>
                    <?php }else{?>
                    <?php echo esc_html__( 'Single Blog', 'rival' ); } ?>
                </span>
             </div>
             <div class="breadcrumb__section-title-box mb-20">
                <h3 class="breadcrumb__title"><?php the_title();?></h3>
             </div>
          </div>
       </div>
    </div>
 </div>
</div>
<div class="postbox__area pt-120 pb-70">
 <div class="container">
    <div class="row">
       <div class="col-xxl-8 col-xl-8 col-lg-8 mb-50" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <div class="postbox__wrapper">
             <article class="postbox__item format-image transition-3">
                <?php if (wp_get_attachment_url(get_post_thumbnail_id()) !='')  { ?>
                <div class="postbox__thumb p-relative mb-25">
                   <img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id());?>" alt="<?php the_title_attribute(); ?>">
                </div>
                <?php } ?>
                <div class="postbox__content mb-55">
                  <?php if ( is_sticky() )
                                  echo '<span class="sticky post">' . esc_html__( 'Sticky', 'rival' ) . '</span>';
                              ?>
                   <div class="postbox__meta-box pb-5 d-flex justify-content-between align-items-center">
                      <div class="postbox__meta mb-20">
                         <span><i class="fas fa-calendar-alt"></i> <?php the_time( 'd M Y' );?></span>
                         <span><i class="far fa-comments"></i> <?php comments_number( esc_html__('0 Comments', 'rival'), esc_html__('1 Comment', 'rival'), esc_html__('% Comments', 'rival') ); ?></span>
                         <?php if(isset($rival_redux_demo['time_blog_single'])){?>
                         <span><i class="fal fa-clock"></i>
                            <?php if(isset($rival_redux_demo['time_blog_single'])){?>
                            <?php echo esc_attr($rival_redux_demo['time_blog_single']);?>
                            <?php }else{?>
                            <?php echo esc_html__( '3 min Read', 'rival' ); } ?>
                         </span>
                         <?php } ?>
                      </div>
                      <?php if(isset($rival_redux_demo['number_blog_single'])){?>
                      <div class="postbox__meta d-none d-sm-block">
                         <span><i class="fa-sharp fa-light fa-heart"></i>
                            <?php if(isset($rival_redux_demo['number_blog_single'])){?>
                            <?php echo esc_attr($rival_redux_demo['number_blog_single']);?>
                            <?php }else{?>
                            <?php echo esc_html__( '8', 'rival' ); } ?>
                         </span>
                      </div>
                      <?php } ?>
                   </div>
                   <?php the_content(); ?>
                   <?php wp_link_pages( array(
                    'before'      => '<div class="pagination">' . esc_html__( 'Pages:', 'rival' ),
                    'after'       => '</div>',
                    'link_before' => '',
                    'link_after'  => '',
                   ) ); ?>
                   <?php if(isset($rival_redux_demo['share_blog_single']) && $rival_redux_demo['author_title']!='') {?>
                   <div class="postbox-meta-wrapper">
                      <div class="postbox-share mb-50">
                         <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                               <div class="tagcloud tagcloud-sm mb-30">
                                  <?php echo get_the_tag_list();?> 
                               </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                               <div class="postbox-social text-md-end mb-20">
                                  <span>
                                   <?php if(isset($rival_redux_demo['share_blog_single'])){?>
                                   <?php echo esc_attr($rival_redux_demo['share_blog_single']);?>
                                   <?php }else{?>
                                   <?php echo esc_html__( 'Share:', 'rival' ); } ?>
                                  </span>
                                  <a href="<?php if(isset($rival_redux_demo['link_tw'])){?>
                                   <?php echo esc_attr($rival_redux_demo['link_tw']);?>
                                   <?php }else{?>
                                   <?php echo esc_html__( '', 'rival' ); } ?>"><i class="fa-brands fa-twitter"></i></a>
                                  <a class="social-fb" href="<?php if(isset($rival_redux_demo['link_fb'])){?>
                                   <?php echo esc_attr($rival_redux_demo['link_fb']);?>
                                   <?php }else{?>
                                   <?php echo esc_html__( '', 'rival' ); } ?>"><i class="fa-brands fa-facebook-f"></i></a>
                                  <a class="social-pin" href="<?php if(isset($rival_redux_demo['link_pinterest'])){?>
                                   <?php echo esc_attr($rival_redux_demo['link_pinterest']);?>
                                   <?php }else{?>
                                   <?php echo esc_html__( '', 'rival' ); } ?>"><i class="fa-brands fa-pinterest-p"></i></a>
                                  <a class="social-link" href="<?php if(isset($rival_redux_demo['link_linkedin'])){?>
                                   <?php echo esc_attr($rival_redux_demo['link_linkedin']);?>
                                   <?php }else{?>
                                   <?php echo esc_html__( '', 'rival' ); } ?>"><i class="fa-brands fa-linkedin-in"></i></a>
                               </div>
                            </div>
                         </div>
                      </div>
                      <div class="postbox-details-author-wrap mb-20">
                         <h5 class="postbox-details-author-main-title mb-35"><?php echo esc_html($author_title);?></h5>
                         <div class="row">
                            <div class="col-xl-9">
                               <div class="postbox-details-author d-sm-flex mb-50 mr-45">
                                  <div class="postbox-details-author-thumb">
                                     <a href="">
                                        <img src="<?php echo wp_get_attachment_url($author_img);?>" alt="<?php the_title_attribute(); ?>">
                                     </a>
                                  </div>
                                  <div class="postbox-details-author-content mt-10 mb-30">
                                     <h5 class="postbox-details-author-title"><a href=""><?php echo esc_html($author_name);?></a></h5>
                                     <span><?php echo esc_html($author_job);?></span>
                                     <p><?php echo esc_html($author_desc);?></p>
                                  </div>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                   <?php } ?>
                  <?php           
                     if ( comments_open() || get_comments_number() ) {
                       comments_template();
                     }
                  ?>
             </article>
          </div>
       </div>
       <?php if ( is_active_sidebar( 'sidebar-1' ) ){?>
       <div class="col-xxl-4 col-xl-4 col-lg-4">
          <div class="sidebar__wrapper">
             <?php get_sidebar(); ?>
          </div>
       </div>
       <?php } ?>
    </div>
 </div>
</div>
<?php if(isset($rival_redux_demo['tp_title']) && $rival_redux_demo['tp_subtitle2']!='') {?>
<div class="tp-cta-area tp-cta-border black-bg-2 pt-10">
 <div class="container">
    <div class="tp-cta-content p-relative text-center fix">
       <div class="tp-scroll-hr">
          <div class="tp-scroll-wrap">
             <h3 class="tp-cta-title">
               <?php if(isset($rival_redux_demo['tp_title'])){?>
               <?php echo esc_attr($rival_redux_demo['tp_title']);?>
               <?php }else{?>
               <?php echo esc_html__( 'Schedule A Free Consultation for Window and Door Replacement', 'rival' ); } ?>
             </h3>
          </div>
       </div>
       <div class="get-schedule">
          <a class="wow zoomIn" data-wow-duration=".8s" data-wow-delay=".5s" href="<?php if(isset($rival_redux_demo['link_tp_subtitle'])){?>
               <?php echo esc_attr($rival_redux_demo['link_tp_subtitle']);?>
               <?php }else{?>
               <?php echo esc_html__( '', 'rival' ); } ?>"><?php if(isset($rival_redux_demo['tp_subtitle1'])){?>
               <?php echo esc_attr($rival_redux_demo['tp_subtitle1']);?>
               <?php }else{?>
               <?php echo esc_html__( 'Get', 'rival' ); } ?><br><?php if(isset($rival_redux_demo['tp_subtitle2'])){?>
               <?php echo esc_attr($rival_redux_demo['tp_subtitle2']);?>
               <?php }else{?>
               <?php echo esc_html__( 'Schedule Now', 'rival' ); } ?>
             </a>
       </div>
    </div>
 </div>
</div>
<?php } ?>
<?php endwhile; ?>
<?php
    get_footer();
?>