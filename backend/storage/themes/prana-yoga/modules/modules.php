<?php
namespace PranaYogaSpace\Modules;

use PranaYogaSpace\Modules\CSS_Vars;
use PranaYogaSpace\Modules\Gutenberg;
use PranaYogaSpace\Modules\Swiper;
use PranaYogaSpace\Modules\Page_Preloader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme modules.
 *
 * Main class for theme modules.
 */
class Modules {

	/**
	 * Theme modules constructor.
	 *
	 * Run modules for theme.
	 */
	public function __construct() {
		new CSS_Vars();

		new Swiper();

		new Gutenberg();

		new Page_Preloader();
	}

}
