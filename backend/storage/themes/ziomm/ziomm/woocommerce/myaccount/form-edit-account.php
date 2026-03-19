<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="vlt-form-group">
		<input type="text" class="vlt-form-control style-2 woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" placeholder="<?php esc_html_e( 'First name', 'ziomm' ); ?>" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</div>

	<div class="vlt-form-group">
		<input type="text" class="vlt-form-control style-2 woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" placeholder="<?php esc_html_e( 'Last name*', 'ziomm' ); ?>" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</div>

	<div class="vlt-form-group">
		<input type="text" class="vlt-form-control style-2 woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" placeholder="<?php esc_html_e( 'Display name*', 'ziomm' ); ?>" value="<?php echo esc_attr( $user->display_name ); ?>" />
	</div>

	<div class="vlt-form-group">
		<input type="email" class="vlt-form-control style-2 woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" placeholder="<?php esc_html_e( 'Email address*', 'ziomm' ); ?>" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</div>

	<h3><?php esc_html_e( 'Password change', 'ziomm' ); ?></h3>

	<div class="vlt-form-group">
		<input type="password" class="vlt-form-control style-2 woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" placeholder="<?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'ziomm' ); ?>" autocomplete="off" />
	</div>

	<div class="vlt-form-group">
		<input type="password" class="vlt-form-control style-2 woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" placeholder="<?php esc_html_e( 'New password (leave blank to leave unchanged)', 'ziomm' ); ?>" autocomplete="off" />
	</div>

	<div class="vlt-form-group">
		<input type="password" class="vlt-form-control style-2 woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" placeholder="<?php esc_html_e( 'Confirm new password', 'ziomm' ); ?>" autocomplete="off" />
	</div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<div>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="vlt-btn vlt-btn--primary vlt-btn--effect woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'ziomm' ); ?>"><span><?php esc_html_e( 'Save changes', 'ziomm' ); ?></span></button>
		<input type="hidden" name="action" value="save_account_details" />
	</div>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
