<?php
/**
 * The template for displaying all team single posts.
 *
 * @package geoport
 */

get_header(); 

do_action( 'geoport_breadcrum' );

?>

<div class="team-inner-page-area">
	<div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

            $geoport_team_info = get_post_meta( get_the_ID(), '_geoport_team', true );
            if (!empty($geoport_team_info['team_designation'])) {
              $team_designation = $geoport_team_info['team_designation'];
            } else {
              $team_designation = '';
            } if (!empty($geoport_team_info['team_member_info'])) {
              $team_member_info = $geoport_team_info['team_member_info'];
            } else {
              $team_member_info = '';
            } if (!empty($geoport_team_info['team_social'])) {
              $team_social = $geoport_team_info['team_social'];
            } else {
              $team_social = '';
            }

            if(has_post_thumbnail()){
                $cols = '6';
            } else {
                $cols = '8 mx-auto';
            }
        ?>

		<div class="row align-items-center">
			<?php if(has_post_thumbnail()) : ?>
				<div class="col-lg-6">
					<div class="team-details-thumb">
						<?php the_post_thumbnail(); ?>
					</div>
				</div>
			<?php endif; ?>

            <div class="col-lg-<?php echo esc_attr( $cols ); ?>">
	            <div class="team-details-content">
	                <?php if (!empty($team_designation)) { ?>
	                    <span class="designation"><?php echo esc_html( $team_designation ); ?></span>
	                <?php } ?>
	                <h2><?php the_title(); ?></h2>
	                <div class="desc">
	                    <?php the_content(); ?>
	                </div>
	                <?php 
	                if (is_array($team_member_info)) { ?>
	                <div class="info-list">
	                    <ul>
	                        <?php foreach ($team_member_info as $key => $value) { 
	                            $title = $value['title'];
	                            $data_type = $value['text'];
	                            if(filter_var($data_type, FILTER_VALIDATE_EMAIL)){
	                                $href_value = 'email';
	                            } elseif( preg_match('/^[0-9\-\(\)\/\+\s]*$/', $data_type ) ){
	                                $href_value = 'phone';
	                            } elseif (filter_var($data_type, FILTER_VALIDATE_URL)) {
	                                $href_value = 'url';
	                            } else {
	                                $href_value = '';
	                            }
	                        ?>
	                        <li><i class="fa fa-check"></i>
	                            <span><?php echo esc_html($title); ?></span> 
	                            <?php if (!empty($href_value == 'email')) { ?>
	                            <a href="mailto:<?php echo sanitize_email($data_type); ?>"><?php echo esc_html( $data_type ); ?></a>
	                            <?php } elseif (!empty($href_value == 'phone')) { 
	                                $phone_no = str_replace(" ", "", $data_type ); 
	                            ?>
	                            <a href="tel:<?php echo esc_attr($phone_no); ?>"><?php echo esc_html( $data_type ); ?></a>
	                            <?php } elseif (!empty($href_value == 'url')) { ?>
	                            <a href="<?php echo esc_url($data_type); ?>"><?php echo esc_html( $data_type ); ?></a>
	                            <?php } else { ?>
	                            <?php echo esc_html( $data_type ); ?>
	                            <?php } ?>
	                        </li>
	                        <?php } ?>
	                    </ul>
	                </div>
	                <?php } if (is_array($team_social)) { ?>
	                <div class="team-details-social">
		                <ul>
		                    <?php foreach ($team_social as $key => $value) { ?>
		                    	<li><a href="<?php echo esc_url($value['link']); ?>"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a></li>
		                    <?php } ?>
		                </ul>
	                </div>
	                <?php } ?>
	            </div>
			</div>
		</div>
        <?php endwhile; 
        else :
        	get_template_part( 'template-parts/content', 'none' );
        endif; 
        ?>
	</div>
</div>

<?php get_footer(); ?>