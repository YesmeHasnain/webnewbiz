<?php 
$rival_redux_demo = get_option('redux_demo');
get_header(); ?>
<?php 
while (have_posts()): the_post();
?>
<?php $category_projects = get_post_meta(get_the_ID(),'_cmb_category_projects', true); ?>
<?php $img_projects1 = get_post_meta(get_the_ID(),'_cmb_img_projects1', true); ?>
<?php $img_projects2 = get_post_meta(get_the_ID(),'_cmb_img_projects2', true); ?>
<?php $video_projects = get_post_meta(get_the_ID(),'_cmb_video_projects', true); ?>
<?php $heading_projects = get_post_meta(get_the_ID(),'_cmb_heading_projects', true); ?>
<?php $desc_projects = get_post_meta(get_the_ID(),'_cmb_desc_projects', true); ?>
<section id="projectDetail" class="ofsBottom singleOffset">
    <div class="container clearfix singleProject">
        <h1 class="projTitle tCenter title"><?php the_title(); ?><span><?php echo esc_html($category_projects);?></span></h1>
        <div class="extra clearfix">
            <div class="eight columns projSocials">
                <ul>
                    <li><a href="
                        <?php if(isset($rival_redux_demo['link_fb'])){?>
                        <?php echo esc_attr($rival_redux_demo['link_fb']);?>
                        <?php }else{?>
                        <?php echo esc_html__( '', 'rival' );}?>
                        "><i class="
                            <?php if(isset($rival_redux_demo['icon_fb'])){?>
                            <?php echo esc_attr($rival_redux_demo['icon_fb']);?>
                            <?php }else{?>
                            <?php echo esc_html__( 'icon-facebook', 'rival' );}?>
                        "></i></a></li>
                    <li><a href="
                        <?php if(isset($rival_redux_demo['link_linkedin'])){?>
                        <?php echo esc_attr($rival_redux_demo['link_linkedin']);?>
                        <?php }else{?>
                        <?php echo esc_html__( '', 'rival' );}?>
                        "><i class="
                            <?php if(isset($rival_redux_demo['icon_linkedin'])){?>
                            <?php echo esc_attr($rival_redux_demo['icon_linkedin']);?>
                            <?php }else{?>
                            <?php echo esc_html__( 'icon-linkedin', 'rival' );}?>
                        "></i></a></li>
                    <li><a href="
                        <?php if(isset($rival_redux_demo['link_ig'])){?>
                        <?php echo esc_attr($rival_redux_demo['link_ig']);?>
                        <?php }else{?>
                        <?php echo esc_html__( '', 'rival' );}?>
                        "><i class="
                            <?php if(isset($rival_redux_demo['icon_ig'])){?>
                            <?php echo esc_attr($rival_redux_demo['icon_ig']);?>
                            <?php }else{?>
                            <?php echo esc_html__( 'icon-instagram', 'rival' );}?>
                        "></i></a></li>
                </ul>
            </div>
            <div class="eight columns projNav">
                <ul>
                    <li><?php previous_post_link('%link', '<i class="icon-left-open-big"></i>'); ?></li>
                    <li><a href="<?php the_permalink(); ?>"><i class="icon-layout"></i></a></li>
                    <li><?php next_post_link('%link', '<i class="icon-right-open-big"></i>'); ?></li>
                </ul>
            </div>
        </div>

        <?php $choose_select = get_post_meta(get_the_ID(),'_cmb_choose_select', true); ?>
        <?php if($choose_select=='style1') {?>
            <div class="projectSlider flexslider">
                <ul class="slides">
                    <li><img src="<?php echo wp_get_attachment_url($img_projects1);?>" alt="<?php the_title_attribute(); ?>"/></li>
                    <li><img src="<?php echo wp_get_attachment_url($img_projects2);?>" alt="<?php the_title_attribute(); ?>" /></li>
                </ul>
            </div>
        <?php } else {?>
            <div class="videoHolder" >
                <iframe width="940" height="600" src="<?php echo esc_attr($video_projects);?>" allowfullscreen></iframe>
            </div>
        <?php } ?>
        <div class="singleDetails clearfix">
            <div class="four columns projectInfo">
                <h1><?php echo esc_html($heading_projects);?></h1>
                <?php if ( is_active_sidebar( 'sidebar-projects' ) ) : ?>
                    <?php dynamic_sidebar( 'sidebar-projects' ); ?>
                 <?php endif; ?>
            </div>
            <div class="twelve columns">
                <h1><?php the_title(); ?></h1>
                <p><?php echo esc_html($desc_projects);?></p>
                <div class="btn">
                    <a href="<?php if(isset($rival_redux_demo['link_projects'])){?>
                        <?php echo esc_attr($rival_redux_demo['link_projects']);?>
                        <?php }else{?>
                        <?php echo esc_html__( '', 'rival' );}?>">
                        <?php if(isset($rival_redux_demo['projects_title'])){?>
                        <?php echo esc_attr($rival_redux_demo['projects_title']);?>
                        <?php }else{?>
                        <?php echo esc_html__( 'Launch Project', 'rival' );}?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php the_content(); ?>
<?php endwhile; ?>
<?php get_footer(); ?>