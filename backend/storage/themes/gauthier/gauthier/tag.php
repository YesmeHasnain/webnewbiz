<?php global $redux_gauthier;  
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>

<div class="<?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
<?php if ( isset($redux_gauthier['jp_tag1']) && is_tag($redux_gauthier['jp_tag1'])) { 
		get_template_part($redux_gauthier['jp_tagtemplate1']); 
	} elseif (isset($redux_gauthier['jp_tag2']) && is_tag($redux_gauthier['jp_tag2'])) {
		get_template_part($redux_gauthier['jp_tagtemplate2']); 		
	} elseif (isset($redux_gauthier['jp_tag3']) && is_tag($redux_gauthier['jp_tag3'])) {
		get_template_part($redux_gauthier['jp_tagtemplate3']); 
	} elseif (isset($redux_gauthier['jp_tag4']) && is_tag($redux_gauthier['jp_tag4'])) {
		get_template_part($redux_gauthier['jp_tagtemplate4']); 			
	} elseif (isset($redux_gauthier['jp_tag5']) && is_tag($redux_gauthier['jp_tag5'])) {
		get_template_part($redux_gauthier['jp_tagtemplate5']); 			
	} elseif (isset($redux_gauthier['jp_tag6']) && is_tag($redux_gauthier['jp_tag6'])) {
		get_template_part($redux_gauthier['jp_tagtemplate6']); 			
	} else { 
	  get_template_part( 'page-templates/tag_default' );
	} ?>
	</div>	
<?php 
if( isset($redux_gauthier['footer_layout']) ){
  get_template_part($redux_gauthier['footer_layout']);
} else{
    get_footer();
}
?>