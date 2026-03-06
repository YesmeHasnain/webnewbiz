<?php 
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */
 
	// Block direct access
	if( ! defined( 'ABSPATH' ) ){
		exit();
	}

	/**
	* Hook for preloader
	*/
	add_action( 'barab_preloader_wrap', 'barab_preloader_wrap_cb', 10 );

	/**
	* Hook for offcanvas cart
	*/
	add_action( 'barab_main_wrapper_start', 'barab_main_wrapper_start_cb', 10 );

	/**
	* Hook for Header
	*/
	add_action( 'barab_header', 'barab_header_cb', 10 );

	/**
	* Hook for Breadcrumb
	*/
	add_action( 'barab_breadcrumb', 'barab_breadcrumb_cb', 10 );
	
	/**
	* Hook for Blog Start Wrapper
	*/
	add_action( 'barab_blog_start_wrap', 'barab_blog_start_wrap_cb', 10 );
	
	/**
	* Hook for Blog Column Start Wrapper
	*/
    add_action( 'barab_blog_col_start_wrap', 'barab_blog_col_start_wrap_cb', 10 );
	
	/**
	* Hook for Blog Column End Wrapper
	*/
    add_action( 'barab_blog_col_end_wrap', 'barab_blog_col_end_wrap_cb', 10 );
	
	/**
	* Hook for Blog Column End Wrapper
	*/
    add_action( 'barab_blog_end_wrap', 'barab_blog_end_wrap_cb', 10 );
	
	/**
	* Hook for Blog Pagination
	*/
    add_action( 'barab_blog_pagination', 'barab_blog_pagination_cb', 10 );
    
    /**
	* Hook for Blog Content
	*/
	add_action( 'barab_blog_content', 'barab_blog_content_cb', 10 );
    
    /**
	* Hook for Blog Sidebar
	*/
	add_action( 'barab_blog_sidebar', 'barab_blog_sidebar_cb', 10 );
    
    /**
	* Hook for Blog Details Sidebar
	*/
	add_action( 'barab_blog_details_sidebar', 'barab_blog_details_sidebar_cb', 10 );

	/**
	* Hook for Blog Details Wrapper Start
	*/
	add_action( 'barab_blog_details_wrapper_start', 'barab_blog_details_wrapper_start_cb', 10 );

	/**
	* Hook for Blog Details Post Meta
	*/
	add_action( 'barab_blog_post_meta', 'barab_blog_post_meta_cb', 10 );

	/**
	* Hook for Blog Details Post Share Options
	*/
	add_action( 'barab_blog_details_share_options', 'barab_blog_details_share_options_cb', 10 );

	/**
	* Hook for Blog Post Share Options
	*/
	add_action( 'barab_blog_post_share_options', 'barab_blog_post_share_options_cb', 10 );

	/**
	* Hook for Blog Details Post Author Bio
	*/
	add_action( 'barab_blog_details_author_bio', 'barab_blog_details_author_bio_cb', 10 );

	/**
	* Hook for Blog Details Tags and Categories
	*/
	add_action( 'barab_blog_details_tags_and_categories', 'barab_blog_details_tags_and_categories_cb', 10 );

	/**
	* Hook for Blog Details Related Post Navigation
	*/
	add_action( 'barab_blog_details_post_navigation', 'barab_blog_details_post_navigation_cb', 10 ); 

	/**
	* Hook for Blog Deatils Comments
	*/
	add_action( 'barab_blog_details_comments', 'barab_blog_details_comments_cb', 10 );

	/**
	* Hook for Blog Deatils Column Start
	*/
	add_action('barab_blog_details_col_start','barab_blog_details_col_start_cb');

	/**
	* Hook for Blog Deatils Column End
	*/
	add_action('barab_blog_details_col_end','barab_blog_details_col_end_cb');

	/**
	* Hook for Blog Deatils Wrapper End
	*/
	add_action('barab_blog_details_wrapper_end','barab_blog_details_wrapper_end_cb');
	
	/**
	* Hook for Blog Post Thumbnail
	*/
	add_action('barab_blog_post_thumb','barab_blog_post_thumb_cb');
    
	/**
	* Hook for Blog Post Content
	*/
	add_action('barab_blog_post_content','barab_blog_post_content_cb');
	
    
	/**
	* Hook for Blog Post Excerpt And Read More Button
	*/
	add_action('barab_blog_postexcerpt_read_content','barab_blog_postexcerpt_read_content_cb');
	
	/**
	* Hook for footer content
	*/
	add_action( 'barab_footer_content', 'barab_footer_content_cb', 10 );
	
	/**
	* Hook for main wrapper end
	*/
	add_action( 'barab_main_wrapper_end', 'barab_main_wrapper_end_cb', 10 );
	
	/**
	* Hook for Back to Top Button
	*/
	add_action( 'barab_back_to_top', 'barab_back_to_top_cb', 10 );

	/**
	* Hook for Page Start Wrapper
	*/
	add_action( 'barab_page_start_wrap', 'barab_page_start_wrap_cb', 10 );

	/**
	* Hook for Page End Wrapper
	*/
	add_action( 'barab_page_end_wrap', 'barab_page_end_wrap_cb', 10 );

	/**
	* Hook for Page Column Start Wrapper
	*/
	add_action( 'barab_page_col_start_wrap', 'barab_page_col_start_wrap_cb', 10 );

	/**
	* Hook for Page Column End Wrapper
	*/
	add_action( 'barab_page_col_end_wrap', 'barab_page_col_end_wrap_cb', 10 );

	/**
	* Hook for Page Column End Wrapper
	*/
	add_action( 'barab_page_sidebar', 'barab_page_sidebar_cb', 10 );

	/**
	* Hook for Page Content
	*/
	add_action( 'barab_page_content', 'barab_page_content_cb', 10 );