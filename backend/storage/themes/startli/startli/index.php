<?php
get_header(); ?>

<div id="reactheme-blog" class="reactheme-blog blog-page">
   <?php
    //checking blog layout form option  
    $col         ='';
    $blog_layout =''; 
    $column      =''; 
    $blog_grid   ='';

    if(!empty($startli_option['blog-layout']) || !is_active_sidebar( 'sidebar-1' )){

        $blog_layout = !empty($startli_option['blog-layout']) ? $startli_option['blog-layout'] : '';
        $blog_grid   =  !empty($startli_option['blog-grid']) ? $startli_option['blog-grid'] : '';
        $blog_grid   = !empty($blog_grid) ? $blog_grid : '12';
        if($blog_layout == 'full' || !is_active_sidebar( 'sidebar-1' ))
        {
            $layout ='full-layout';
            $col    = '-full';
            $column = 'sidebar-none';  
        } 
          
        elseif($blog_layout == '2left'){
            $layout = 'full-layout-left';  
        }
        elseif($blog_layout == '2right'){
            $layout = 'full-layout-right'; 
        } 
        else{
            $col = '';
            $blog_layout = ''; 
        }
    }
    else{
        $col         ='';
        $blog_layout =''; 
        $layout      ='';
        $blog_grid   ='12';
    }
?>
    
    <div class="row padding-<?php echo esc_attr( $layout) ?>">       
        <div class="contents-sticky col-md-12 col-lg-8<?php echo esc_attr($col); ?> <?php echo esc_attr($layout); ?>">                   
            <div class="row">            
                <?php
                    if ( have_posts() ) :           
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();

                        $post_id   = get_the_id();
                        $author_id = $post->post_author;
                        $no_thumb  = "";

                        if ( !has_post_thumbnail() ) {
                          $no_thumb = "no-thumbs";
                        }?>

                    <div class="col-sm-<?php echo esc_attr($blog_grid);?> col-xs-12">
                        <article <?php post_class();?>>
                            <div class="blog-item <?php echo esc_attr($no_thumb); ?>">
                                <?php if ( has_post_thumbnail() ) {?>
                                    <div class="blog-img">
                                       <a href="<?php the_permalink();?>">
                                            <?php
                                                the_post_thumbnail();
                                            ?>
                                        </a>                                
                                    
                                    </div><!-- .blog-img -->
                                <?php }       
                                ?>
                                <div class="full-blog-content">
                                    <div class="user-info">
                                        <!-- single info -->
                                        <?php if (!empty($startli_option['blog-author-post'])) {
                                        if ($startli_option['blog-author-post'] == 'show') : ?>
                                            <div class="single-info">
                                                <span>
                                                    <i class="rt rt-circle-user-regular"></i>
                                                    <?php
                                                    echo esc_html__('by', 'startli');
                                                    $last_name = get_user_meta($author_id, 'last_name', true);
                                                    $first_name = get_user_meta($author_id, 'first_name', true);
                                                    if (!empty($first_name) && !empty($last_name)) {
                                                        echo esc_html($first_name) . ' ' . esc_html($last_name);
                                                    } else {
                                                        echo get_the_author();
                                                    } ?>  </span>
                                                    </div>
                                                <?php endif; ?>
                                              
                                        <?php } else { ?>
                                            <div class="single-info">
                                                <span>
                                                    <i class="rt rt-circle-user-regular"></i>
                                                    <?php
                                                    echo esc_html__('by', 'startli');
                                                    $last_name = get_user_meta($author_id, 'last_name', true);
                                                    $first_name = get_user_meta($author_id, 'first_name', true);
                                                    if (!empty($first_name) && !empty($last_name)) {
                                                        echo esc_html($first_name) . ' ' . esc_html($last_name);
                                                    } else {
                                                        echo get_the_author();
                                                    } ?>
                                                </span>
                                            </div>
                                        <?php }; ?>
                                               
                                          
                                        <!-- single infoe end -->
                                        <!-- single info -->
                                        <?php if(!empty($startli_option['blog-date'])) :?>
                                            <div class="single-info">
                                                <i class="rt rt-clock-regular"></i>
                                                <span><?php echo get_the_date();?></span>
                                            </div>
                                        <?php endif; ?>
                                        <!-- single infoe end -->
                                        <!-- single info -->
                                        <div class="single-info cat">
                                            
                                            <span> <?php if(!empty($startli_option['blog-category'])){
                                                if($startli_option['blog-category'] == 'show'){ ?>
                                                <i class="rt rt-tags"></i> <?php
                                                    if(get_the_category()):
                                                        the_category(', ');                                                 
                                                endif;
                                                    }
                                            }else{ ?>
                                                <i class="rt rt-tags"></i> <?php
                                                if(get_the_category()): ?>
                                                                                                
                                                    <?php
                                                        //tag add
                                                        $seperator = ', '; // blank instead of comma
                                                        $after = '';
                                                        $before = '';
                                                    
                                                        ?>
                                                        
                                                        <?php
                                                        the_category(',  '); 
                                                    
                                                    ?> 
                                                
                                                <?php
                                            endif;
                                            } ?>
                                            </span>
                                        </div>
                                        <!-- single info end -->
                                    </div>
                                    <div class="title-wrap">                                                                                                              
                                        <h3 class="blog-title">
                                            <a href="<?php the_permalink();?>">
                                                <?php the_title();?>
                                            </a>
                                        </h3>                                        
                                  </div>

                                    <div class="blog-desc">   
                                        <?php echo startli_custom_excerpt(30);?>                                     
                                    </div>                                     
                                    <?php                                     
                                          
                                        if(!empty($startli_option['blog_readmore'])):?>
                                            <div class="blog-button">
                                                <a href="<?php the_permalink();?>">
                                                    <?php echo esc_html($startli_option['blog_readmore']); ?>
                                                </a>
                                            </div>
                                    <?php endif; ?>
                            </div>
                          </div>
                        </article>
                    </div>
                
                <?php  
                  endwhile;                        
                ?>
            </div>
            <div class="pagination-area">
                <?php
                    the_posts_pagination();
                ?>
            </div>
            <?php
            else :
            get_template_part( 'template-parts/content', 'none' );
            endif; ?> 
        </div>
        <?php if( $layout != 'full-layout' ):     
            get_sidebar();    
        endif;
        ?>
    </div>      
</div>
<?php
get_footer();