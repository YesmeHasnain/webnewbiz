<?php
$sticky_header    = xcency_option( 'sticky_header', true );
?>
<div class="main-menu-area" <?php if($sticky_header == true ){echo 'data-uk-sticky="top: 250; animation: uk-animation-slide-top;"';} ?>>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2 col-sm-3 col-6 logo-column">
                <?php get_template_part( 'template-parts/header/header-logo' ); ?>
            </div>

            <div class="col-lg-10 col-sm-9 col-6">
                <div class="header-navigation-area">
                    <?php get_template_part( 'template-parts/header/header-menu' );?>

                    <div class="header-buttons-area text-end">
                        <div class="header-buttons-wrapper">
                            <ul class="xcency-list-style xcency-list-inline">
                                <li class="mobile-menu-trigger"><span></span><span></span><span></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>