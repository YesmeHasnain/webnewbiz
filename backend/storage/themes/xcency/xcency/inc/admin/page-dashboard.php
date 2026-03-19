<?php if (!defined('ABSPATH')) {
	die;
} // Cannot access directly. ?>

<div class="wrap xcency-wrap">

    <div class="xcency-admin-page-header">

        <div class="xcency-admin-page-header-text">
            <h1><?php esc_html_e('Welcome to Xcency!', 'xcency'); ?></h1>
            <p><?php esc_html_e('Xcency is a digital agency  WordPress theme.', 'xcency'); ?></p>
        </div>

        <div class="xcency-admin-page-header-logo">
            <img src="<?php echo get_theme_file_uri('inc/admin/assets/images/admin-logo.png'); ?>"/>
            <strong>V-<?php echo wp_get_theme()->get('Version'); ?></strong>
        </div>
    </div>

    <div class="xcency-admin-boxes">

        <div class="xcency-admin-box">
            <div class="xcency-admin-box-header">
                <h2><?php esc_html_e('Theme Documentation', 'xcency'); ?></h2>
            </div>

            <div class="xcency-admin-box-inside">
                <p><?php esc_html_e('You can find everything about theme settings. See our online documentation.', 'xcency'); ?></p>
                <a href="https://xcency.quintexbd.com/xcency-docs/" target="_blank"
                   class="button"><?php esc_html_e('Go to Documentation', 'xcency'); ?></a>
            </div>

        </div>

        <div class="xcency-admin-box">

            <div class="xcency-admin-box-header">
                <h2><?php esc_html_e('Theme Support', 'xcency'); ?></h2>
            </div>

            <div class="xcency-admin-box-inside">
                <p><?php esc_html_e('Do you need help? Feel to free ask any question.', 'xcency'); ?></p>
                <a href="https://themeforest.net/user/quintexit" target="_blank"
                   class="button"><?php esc_html_e('Profile Contact Form', 'xcency'); ?></a>
            </div>
        </div>

    </div>

</div>
