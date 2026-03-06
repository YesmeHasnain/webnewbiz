<?php
$opt = get_option('transland_opt');
$is_breadcrumb = !empty($opt['is_breadcrumb']) ? $opt['is_breadcrumb'] : '';
?>

<section class="page-banner-wrap text-center bg-cover">
    <div class="container">
        <div class="page-heading text-white">
            <div class="page-title">
                <h1><?php transland_banner_title(); ?></h1>
            </div>
        </div>
    </div>
</section>

<?php if ( $is_breadcrumb == true && !is_home() ) :?>
<div class="breadcrumb-wrapper">
    <div class="container">
        <?php if(function_exists('bcn_display')){
            bcn_display();
        }?>
    </div>
</div>
<?php endif; ?>
