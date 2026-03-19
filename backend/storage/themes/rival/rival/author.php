<?php
     $rival_redux_demo = get_option('redux_demo');
     get_header(); 
?>
<section id="blogFull" class="blogFull singleOffset ofsBottom">
   <div class="container clearfix">
      <div class="sixteen columns">
            <h1 class="title tCenter">
               <?php printf( esc_html__( 'All posts by %s', 'rival' ), get_the_author() );?>
            </h1>
      </div>
      <div class="eleven columns">
         <?php
         $idd = 0;
            while($wp_query->have_posts()): $wp_query->the_post();
            $idd++;
            $rival_redux_demo = get_option('redux_demo');
         ?>
         <?php $img_blog = get_post_meta(get_the_ID(),'_cmb_img_blog', true); ?>
         <?php $link_video = get_post_meta(get_the_ID(),'_cmb_link_video', true); ?>
         <div class="postLarge">
            <div class="postContent">
               <div class="postTitle">
                  <h1><a href="<?php the_permalink() ?>"><?php the_title();?> <span class="postDate"><?php echo esc_html__( '/ ', 'rival' ); ?> <?php the_time( 'd M' );?></span></a></h1>
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
               <?php $gallery = get_post_gallery( get_the_ID(), false ); ?>
               <?php if (has_post_format('gallery') && isset($gallery['ids'])) {?>
                  <div class="postMedia postSliderLarge flexslider large">
                     <ul class="slides">
                     <?php
                     if(isset($gallery['ids'])){    
                        $gallery_ids = $gallery['ids'];
                        $img_ids = explode(",",$gallery_ids);
                        $i=0; $j=0;?>
                        <?php
                        foreach( $img_ids AS $img_id ){ 
                     $image_src = wp_get_attachment_image_src($img_id,'');
                     ?>
                        <li><a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($image_src[0]); ?>" alt="<?php the_title_attribute(); ?>"/></a></li>
                     <?php } } ?>
                     </ul>
                  </div>
               <?php } elseif(has_post_format('video')) {?>
                  <div class="postMedia large">
                     <iframe height="400" src="<?php print wp_specialchars_decode($link_video); ?>" allowfullscreen></iframe>
                  </div>
               <?php } elseif(has_post_format('image')) {?>
                  <div class="postMedia large">
                     <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo wp_get_attachment_url($img_blog);?>" alt="<?php the_title_attribute(); ?>">
                     </a>
                  </div>
               <?php } else{?>
               <?php } ?>
               <p><?php if(isset($rival_redux_demo['blog_excerpt'])){?>
                  <?php echo esc_attr(rival_excerpt($rival_redux_demo['blog_excerpt'])); ?>
                  <?php }else{?>
                  <?php echo esc_attr(rival_excerpt(40)); } ?></p>
               <div class="btn more">  
                  <a href="<?php the_permalink() ?>">
                     <?php if(isset($rival_redux_demo['read_more'])){?>
                     <?php echo esc_attr($rival_redux_demo['read_more']);?>
                     <?php }else{?>
                     <?php echo esc_html__( 'Read More', 'rival' ); } ?>
                  </a>
               </div>
            </div>
         </div>
         <?php endwhile; ?>
         <div class="pagination">
            <?php 
               $pagination = array(
               'base'      => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
               'format'    => '',
               'prev_text' => wp_specialchars_decode('<i class="icon-left-open-mini"></i>',ENT_QUOTES),
               'next_text' => wp_specialchars_decode('<i class="icon-right-open-mini"></i>',ENT_QUOTES),
               'type'      => 'list',
               'end_size'    => 3,
               'mid_size'    => 3
               );
               if(paginate_links( $pagination ) != ''){
                   $return =  paginate_links( $pagination );
                   echo str_replace( "<ul class='page-numbers'>", '<ul>', $return );
               }
           ?>
         </div>
      </div>
      <div class="five columns sidebar">
         <?php get_sidebar(); ?>
      </div>
   </div>
</section>
<?php
    get_footer();
?>