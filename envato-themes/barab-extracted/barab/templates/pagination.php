<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */
 
    // Block direct access
    if( !defined( 'ABSPATH' ) ){
        exit();
    }

    if( !empty( barab_pagination() ) ) :
?>
<!-- Post Pagination -->
<div class="th-pagination"> 
    <ul>
        <?php
            $prev   = '<i class="far fa-arrow-left"></i>';
            $next   = '<i class="far fa-arrow-right"></i>';
            // previous
            if( get_previous_posts_link() ){
                echo '<li>';
                previous_posts_link( $prev );
                echo '</li>';
            }

            echo barab_pagination();

            // next
            if( get_next_posts_link() ){
                echo '<li>';
                next_posts_link( $next );
                echo '</li>';
            }
        ?>
    </ul>
</div>
<!-- End of Post Pagination -->
<?php endif;