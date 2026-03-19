<?php 
global $startli_option;
$preloader_img = "";
if(!empty($startli_option['show_preloader']))
  {
    $loading = $startli_option['show_preloader'];
    
    if(!empty($startli_option['preloader_img'])){
        $preloader_img = $startli_option['preloader_img'];
    }

    if($loading == 1){
      if(empty($preloader_img['url'])):
      ?>
      <div id="startli-load">  
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
      </div>  
        
        <?php else: ?>
            <div id="startli-load">                
                <div class="loader-container">
                    <div class='loader-icon'><img src="<?php echo esc_url($preloader_img['url']);?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></div>
                </div>                            
            </div>
        <?php endif; ?>
  <?php }
}?>

<?php 
    if(!empty($startli_option['off_sticky'])):   
        $sticky = $startli_option['off_sticky'];         
        if($sticky == 1):
            $sticky_menu ='menu-sticky';        
        endif;
        else:
            $sticky_menu ='';
    endif;

    if( is_page() ){

        $post_meta_header = get_post_meta($post->ID, 'trans_header', true);  

        if($post_meta_header == 'Default Header'){       
            $header_style = 'default_header';             
        }
        else{
            $header_style = 'transparent_header';
        }
    }
    else{
        $header_style = 'transparent_header';
    }
 ?>   