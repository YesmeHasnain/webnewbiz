<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( );
}
/**
 * @Packge    : Solak
 * @version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 * Template Name: Template Builder 
 */

//Header
get_header();

// Container or wrapper div
$solak_layout = solak_meta( 'custom_page_layout' );

if( $solak_layout == '1' ){ ?>
	<div class="solak-main-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
<?php }elseif( $solak_layout == '2' ){ ?>
    <div class="solak-main-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
<?php }else{ ?>
	<div class="solak-fluid">
<?php } ?>
	<div class="builder-page-wrapper">
	<?php 
	// Query
	if( have_posts() ){
		while( have_posts() ){
			the_post();
			the_content();
		}
        wp_reset_postdata();
	} ?>

	</div>
<?php if( $solak_layout == '1' ){ ?>
				</div>
			</div>
		</div>
	</div>
<?php }elseif( $solak_layout == '2' ){ ?>
				</div>
			</div>
		</div>
	</div>
<?php }else{ ?>
	</div>
<?php }

//footer
get_footer();