<?php
/** 
 * Adding options page under Appearance menu 
 */
function gauthier_one_options_theme_menu() {  
add_theme_page( 'gauthier Theme', 'Gauthier Help & Guide', 'edit_theme_options', 'gauthier_after_instalation', 'gauthier_one_options_display');  
} 
add_action( 'admin_menu', 'gauthier_one_options_theme_menu' ); 
/** 
 * Adding customizer link under Appearance menu
 */ 
/** 
 * Show gauthier Options 
 */ 
function gauthier_one_options_display() { 
?>     <!-- Create a header in the default WordPress 'wrap' container --> 
    <div class="wrap"style='background:#FFFFFF;border:1px solid #e1e1e1; padding:20px;min-width:750px; max-width:960px;'> 
<div style="clear:both;">
<h1><?php _e( '<b>Gauthier Help & Stard Guide</b>', 'gauthier' ); ?></h1> 
<br .../><br .../>
<?php _e( 'For detail guide and documentation, please visit the documentation online.', 'gauthier' ); ?>
<br .../><br .../>
<a href="https://wpbromo.com/doc/gauthier/index.html" target="_blank">
<img src="<?php echo esc_url( get_template_directory_uri() . '/images/doc-online.jpg' ); ?>">
</a>
<br .../><br .../>
<hr>


<h1>
<?php _e( '<b>QUICK STEPS TO BUILD YOUR WEB</b>', 'gauthier' ); ?>
</h1> 

<h2>
<?php _e( 'I. Make sure all the required plugins are installed and activated.', 'gauthier' ); ?>
</h2>
<ol>
<li><b>
<?php _e( 'Step 1 :', 'gauthier' ); ?>
</b> 
<?php _e( 'After installing and activated the theme, you will see the notification that appears at the top of page.', 'gauthier' ); ?>
</li>
<br>
<img src="<?php echo esc_url( get_template_directory_uri() . '/images/intallplugin.jpg' ); ?>">
<br .../><br .../>
<li><b>
<?php _e( 'Step 2 :', 'gauthier' ); ?>
</b> 
<?php _e( 'Click <b>Begin Install Plugins</b>.', 'gauthier' ); ?><br>
<?php _e( 'wait until the installation process is complete. 
</li>
<br>
<li>Activate the plugins after the installation process is complete.', 'gauthier' ); ?>
</li>
</ol>
<br .../>
<div style="clear:both;">
<h2>
<?php _e( 'II. Import Demo Data', 'gauthier' ); ?>
</h2>
<ol>
<li><b>
<?php _e( 'Step 1 :', 'gauthier' ); ?>
</b> 
<?php _e( 'Go to Dashboard> Appearance > Import Demo Data.', 'gauthier' ); ?>
</li>
<li><b>
<?php _e( 'Step 2 :', 'gauthier' ); ?>
</b> 
<?php _e( 'On One Click Demo Imports page, choose the layout and click Import button.', 'gauthier' ); ?>
</li>
<li><b>
<?php _e( 'Step 3 :', 'gauthier' ); ?>
</b>
<?php _e( ' Wait until the process is complete.', 'gauthier' ); ?>
</li>
</ol>
<br .../>
<h2>
<?php _e( 'III. Setup Menu', 'gauthier' ); ?>
</h2> 
<ol>
<li><b>
<?php _e( 'Step 1 :', 'gauthier' ); ?>
</b>
<?php _e( ' Go to Dashboard> Appearance > Menus.', 'gauthier' ); ?>
</li>
<li><b>
<?php _e( 'Step 2 :', 'gauthier' ); ?>
</b> 
<?php _e( 'On Menus page, setting up the menu placement.', 'gauthier' ); ?>
</li>
<li><b>
<?php _e( 'Step 3 :', 'gauthier' ); ?>
</b>  
<?php _e( 'Go to Dashboard> Mega Main Menu. Click Specific options tab. Scrolling down till you see Download backup file with current settings.', 'gauthier' ); ?>
</li>
<li><b>
<?php _e( 'Step 4 :', 'gauthier' ); ?>
</b>  
<?php _e( 'Search the file name Mega-main-menu.txt on your theme on folder name inc>installation. ', 'gauthier' ); ?>
</li>
</ol>
<br .../>
<h2>
<?php _e( 'IV. Theme Option and Customization', 'gauthier' ); ?>
</h2>
<?php _e( 'You can rearrange the appearance of the web layout through the <b>gauthier theme option.</b> In this option, you can make changes to the header, footer, sidebar, typography, and
other elements', 'gauthier' ); ?>
</div><!-- /.wrap --> 
<?php 
} // end gauthier_one_options_display 
