<?php
/**
 * Item badge template.
 *
 * @var $args
 * @var $opts
 * @var $allow_links
 *
 * @package @@plugin_name
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! ziomm_get_field( 'work_badge' ) ) {
	return;
}

?>

<div class="vlt-badge"><?php echo esc_html( ziomm_get_field( 'work_badge' ) ); ?></div>