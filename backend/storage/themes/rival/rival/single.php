<?php
   $rival_redux_demo = get_option('redux_demo');
   get_header(); 
?>
<?php 
    while (have_posts()): the_post();
?>
<?php $img_blog = get_post_meta(get_the_ID(),'_cmb_img_blog', true); ?>
<section id="blogSingle" class="blogSingle singleOffset ofsBottom">
   <div class="container clearfix">
      <div class="eleven columns">
         <div class="postSingle">
            <div class="postContent">
               <div class="postTitle">
                  <h1><?php the_title();?> <span class="postDate"><?php echo esc_html__( '/ ', 'rival' ); ?> <?php the_time( 'd M' );?></span></h1>
                  <div class="postMeta">
                     <span class="metaAuthor">
                        <?php if(isset($rival_redux_demo['post_blog_single'])){?>
                        <?php echo esc_attr($rival_redux_demo['post_blog_single']);?>
                        <?php }else{?>
                        <?php echo esc_html__( 'Posted by ', 'rival' ); } ?>
                        <?php the_author_posts_link(); ?></span>
                     <?php if(get_the_category_list() != '') { ?>
                        <span class="metaCategory"><?php echo esc_html__( '/ ', 'rival' ); ?> <?php echo get_the_category_list();?> </span>
                     <?php } ?>
                     /<span class="metaComment"> <?php comments_number( esc_html__('0 Comments', 'rival'), esc_html__('1 Comment', 'rival'), esc_html__('% Comments', 'rival') ); ?></span>
                  </div>
               </div>
               <?php if (wp_get_attachment_url($img_blog) !='')  { ?>
               <div class="postMedia large">
                  <img src="<?php echo wp_get_attachment_url($img_blog);?>" alt="<?php the_title_attribute(); ?>">
               </div>
               <?php } ?>
               <?php the_content(); ?>      
               <div class="tagsSingle clearfix">
                  <h4><i class="icon-tag-1"></i>
                     <?php if(isset($rival_redux_demo['tag_blog_single'])){?>
                     <?php echo esc_attr($rival_redux_demo['tag_blog_single']);?>
                     <?php }else{?>
                     <?php echo esc_html__( 'Tags :', 'rival' ); } ?>
                  </h4>
                  <ul class="tagsListSingle">
                     <?php echo get_the_tag_list();?> 
                  </ul>
               </div>
            </div>
         </div>
         <?php comments_template();?>
      </div>
      <div class="five columns sidebar">
         <?php get_sidebar(); ?>
      </div>
   </div>
</section>
<?php endwhile; ?>
<?php
    get_footer();
?>