<?php global $redux_gauthier;  
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="<?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
<?php if ( isset($redux_gauthier['jp_category1']) && is_category($redux_gauthier['jp_category1'])) { 
		get_template_part($redux_gauthier['jp_cattemplate1']); 
	} elseif (isset($redux_gauthier['jp_category2']) && is_category($redux_gauthier['jp_category2'])) {
		get_template_part($redux_gauthier['jp_cattemplate2']); 		
	} elseif (isset($redux_gauthier['jp_category3']) && is_category($redux_gauthier['jp_category3'])) {
		get_template_part($redux_gauthier['jp_cattemplate3']); 
	} elseif (isset($redux_gauthier['jp_category4']) && is_category($redux_gauthier['jp_category4'])) {
		get_template_part($redux_gauthier['jp_cattemplate4']); 			
	} elseif (isset($redux_gauthier['jp_category5']) && is_category($redux_gauthier['jp_category5'])) {
		get_template_part($redux_gauthier['jp_cattemplate5']); 	
	} elseif (isset($redux_gauthier['jp_category6']) && is_category($redux_gauthier['jp_category6'])) {
		get_template_part($redux_gauthier['jp_cattemplate6']); 			
	} else { 
	  get_template_part( 'page-templates/category_default' );
	} ?>
	</div>
<?php 
if( isset($redux_gauthier['footer_layout']) ){
  get_template_part($redux_gauthier['footer_layout']);
} else{
    get_footer();
}
?>