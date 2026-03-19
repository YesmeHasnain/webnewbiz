<?php
namespace PranaYogaSpace\ThemeConfig;

use PranaYogaSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme Config.
 *
 * Main class for theme config.
 */
class Theme_Config {

	/**
	 * Major versions.
	 */
	const MAJOR_VERSIONS = array();

	/**
	 * Default Colors.
	 */
	const PRIMARY_COLOR_DEFAULT = '#FFAF7E';
	const SECONDARY_COLOR_DEFAULT = '#343130';
	const TEXT_COLOR_DEFAULT = '#74716C';
	const ACCENT_COLOR_DEFAULT = '#FF7D6A';
	const TERTIARY_COLOR_DEFAULT = '#FF907E';
	const BACKGROUND_COLOR_DEFAULT = '#FBEEDE';
	const ALTERNATE_COLOR_DEFAULT = '#FFFFFF';
	const BORDER_COLOR_DEFAULT = '#DED2C4';

	/**
	 * Default Typography.
	 */
	const PRIMARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const PRIMARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';

	const SECONDARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const SECONDARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';

	const TEXT_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const TEXT_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TEXT_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TEXT_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.8', 'unit' => 'em' );
	const TEXT_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const TEXT_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '18', 'unit' => 'px' );
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '600';
	const ACCENT_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const ACCENT_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const ACCENT_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.6', 'unit' => 'em' );
	const ACCENT_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const ACCENT_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '17', 'unit' => 'px' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const TERTIARY_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TERTIARY_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TERTIARY_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.6', 'unit' => 'em' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const META_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const META_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '13', 'unit' => 'px' );
	const META_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const META_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const META_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const META_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const META_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.7', 'unit' => 'em' );
	const META_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const META_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '13', 'unit' => 'px' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'uppercase';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.7', 'unit' => 'em' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const SMALL_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '16', 'unit' => 'px' );
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const SMALL_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const SMALL_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const SMALL_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.75', 'unit' => 'em' );
	const SMALL_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const SMALL_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H1_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const H1_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '42', 'unit' => 'px' );
	const H1_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H1_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H1_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H1_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H1_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.3', 'unit' => 'em' );
	const H1_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '-2', 'unit' => 'px' );
	const H1_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H2_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const H2_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '34', 'unit' => 'px' );
	const H2_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H2_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H2_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H2_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H2_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.35', 'unit' => 'em' );
	const H2_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '-1', 'unit' => 'px' );
	const H2_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H3_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const H3_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '28', 'unit' => 'px' );
	const H3_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H3_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H3_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H3_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H3_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.4', 'unit' => 'em' );
	const H3_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H3_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H4_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const H4_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '20', 'unit' => 'px' );
	const H4_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';
	const H4_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H4_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H4_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H4_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.45', 'unit' => 'em' );
	const H4_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H4_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H5_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const H5_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '18', 'unit' => 'px' );
	const H5_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';
	const H5_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H5_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H5_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H5_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.5', 'unit' => 'em' );
	const H5_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H5_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H6_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const H6_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const H6_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';
	const H6_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H6_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H6_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H6_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.6', 'unit' => 'em' );
	const H6_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '2', 'unit' => 'px' );
	const H6_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '16', 'unit' => 'px' );
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const BUTTON_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const BUTTON_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const BUTTON_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.4', 'unit' => 'em' );
	const BUTTON_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const BUTTON_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Manrope';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '20', 'unit' => 'px' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.7', 'unit' => 'em' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '1', 'unit' => 'px' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	/**
	 * Theme_Config constructor.
	 */
	public function __construct() {
		$this->define_constants();

		add_action( 'cmsmasters_first_setup', array( $this, 'first_setup_actions' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_default_assets' ), 8 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_default_assets' ), 8 );
	}

	/**
	 * Define constants.
	 */
	public function define_constants() {
		/**
		 * Product key.
		 */
		define( 'CMSMASTERS_THEME_PRODUCT_KEY', '7072616e612d796f6761' );

		/**
		 * Import type.
		 *
		 * demos - import content and all data from demo; apply all data from demo;
		 * kit - import content and all data from demo; apply only kit from demo;
		 * only_kit - import content and all data from main demo, and kit from current demo; apply only kit from demo;
		 */
		define( 'CMSMASTERS_THEME_IMPORT_TYPE', 'demos' );

		/**
		 * Marketplace.
		 *
		 * themeforest - theme for themeforest
		 * envato-elements - theme for envato-elements
		 * templatemonster - theme for templatemonster
		 */
		define( 'CMSMASTERS_THEME_MARKETPLACE', 'envato-elements' );
	}

	/**
	 * Actions on first setup.
	 */
	public function first_setup_actions() {
		$cpt_support = get_option( 'elementor_cpt_support', array( 'post', 'page', 'e-landing-page' ) );

		if ( is_array( $cpt_support ) ) {
			if ( ! in_array( 'cmsms_teacher', $cpt_support ) ) {
				$cpt_support[] = 'cmsms_teacher';
			}

			if ( ! in_array( 'cmsms_classes', $cpt_support ) ) {
				$cpt_support[] = 'cmsms_classes';
			}
		}

		update_option( 'elementor_cpt_support', $cpt_support );
	}

	/**
	 * Enqueue default assets.
	 */
	public function enqueue_default_assets() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			wp_enqueue_style(
				'prana-yoga-default-fonts',
				'https://fonts.googleapis.com/css?family=Manrope%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic',
				array(),
				'1.0.0',
				'screen'
			);
		}

		if ( '' === Utils::get_active_kit() || ! did_action( 'elementor/loaded' ) ) {
			$default_styles = '.wp-block-widget-area h2.wp-block-heading,
			.widget h2 {
				font-family: var(--cmsmasters-h4-font-family);
				font-weight: var(--cmsmasters-h4-font-weight);
				font-style: var(--cmsmasters-h4-font-style);
				text-transform: var(--cmsmasters-h4-text-transform);
				text-decoration: var(--cmsmasters-h4-text-decoration);
				font-size: var(--cmsmasters-h4-font-size);
				line-height: var(--cmsmasters-h4-line-height);
				letter-spacing: var(--cmsmasters-h4-letter-spacing);
				word-spacing: var(--cmsmasters-h4-word-spacing);
			}';

			wp_add_inline_style( 'prana-yoga-default-fonts', $default_styles );
		}
	}

}
