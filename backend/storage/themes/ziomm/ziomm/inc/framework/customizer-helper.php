<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

/**
* Theme path image
*/
$theme_path_images = ZIOMM_THEME_DIRECTORY . 'assets/img/';

/**
 * Wrapper for Kirki
 */
if ( ! class_exists( 'VLT_Options' ) ) {
	class VLT_Options {

		private static $default_options = array();

		public static function add_config( $args ) {
			if ( class_exists( 'Kirki' ) && isset( $args ) && is_array( $args ) ) {
				Kirki::add_config( 'ziomm_customize', $args );
			}
		}

		public static function add_panel( $name, $args ) {
			if ( class_exists( 'Kirki' ) && isset( $args ) && is_array( $args ) ) {
				Kirki::add_panel( $name, $args );
			}
		}

		public static function add_section( $name, $args ) {
			if ( class_exists( 'Kirki' ) && isset( $args ) && is_array( $args ) ) {
				Kirki::add_section( $name, $args );
			}
		}

		public static function add_field( $args ) {
			if ( isset( $args ) && is_array( $args ) ) {
				if ( class_exists( 'Kirki' ) ) {
					Kirki::add_field( 'ziomm_customize', $args );
				}
				if ( isset( $args['settings'] ) && isset( $args['default'] ) ) {
					self::$default_options[$args['settings']] = $args['default'];
				}
			}
		}

		public static function get_option( $name, $default = null ) {
			$value = get_theme_mod( $name, null );

			if ( $value === null ) {
				$value = isset( self::$default_options[$name] ) ? self::$default_options[$name] : null;
			}

			if ( $value === null ) {
				$value = $default;
			}

			return $value;
		}

	}
}

/**
 * Custom get_theme_mod
 */
if ( ! function_exists( 'ziomm_get_theme_mod' ) ) {
	function ziomm_get_theme_mod( $name = null, $use_acf = null, $postID = null, $acf_name = null ) {

		$value = null;

		if ( $name == null ) {
			return $value;
		}

		// try get value from meta box
		if ( $use_acf ) {
			$value = ziomm_get_field( $acf_name ? $acf_name : $name, $postID );
		}

		// get value from options
		if ( $value == null ) {
			if ( class_exists( 'VLT_Options' ) ) {
				$value = VLT_Options::get_option( $name );
			}
		}

		if ( is_archive() || is_search() || is_404() ) {
			if ( class_exists( 'VLT_Options' ) ) {
				$value = VLT_Options::get_option( $name );
			}
		}

		$value = apply_filters( 'ziomm/get_theme_mod', $value, $name );

		return $value;

	}
}

/**
 * Fix post ID preview
 * https://support.advancedcustomfields.com/forums/topic/preview-solution/page/3/
 */
if ( ! function_exists( 'ziomm_fix_post_id_on_preview' ) ) {
	function ziomm_fix_post_id_on_preview( $null, $postID ) {
		if ( is_preview() ) {
			return get_the_ID();
		}
		else {
			$acfPostID = isset( $postID->ID ) ? $postID->ID : $postID;

			if ( ! empty( $acfPostID ) ) {
				return $acfPostID;
			}
			else {
				return $null;
			}
		}
	}
}
add_filter( 'acf/pre_load_post_id', 'ziomm_fix_post_id_on_preview', 10, 2 );

/**
 * Get value from acf field
 */
if ( ! function_exists( 'ziomm_get_field' ) ) {
	function ziomm_get_field( $name = null, $postID = null ) {

		$value = null;

		// try get value from meta box
		if ( function_exists( 'get_field' ) ) {
			if ( $postID == null ) {
				$postID = get_the_ID();
			}
			$value = get_field( $name, $postID );
		}

		return $value;

	}
}

/**
 * Get social icons
 */
if ( ! function_exists( 'ziomm_get_social_icons' ) ) {
	function ziomm_get_social_icons() {
		$social_icons = array(
			'socicon-internet' => esc_html__( 'Internet', 'ziomm' ),
			'socicon-moddb' => esc_html__( 'Moddb', 'ziomm' ),
			'socicon-indiedb' => esc_html__( 'Indiedb', 'ziomm' ),
			'socicon-traxsource' => esc_html__( 'Traxsource', 'ziomm' ),
			'socicon-gamefor' => esc_html__( 'Gamefor', 'ziomm' ),
			'socicon-pixiv' => esc_html__( 'Pixiv', 'ziomm' ),
			'socicon-myanimelist' => esc_html__( 'Myanimelist', 'ziomm' ),
			'socicon-blackberry' => esc_html__( 'Blackberry', 'ziomm' ),
			'socicon-wickr' => esc_html__( 'Wickr', 'ziomm' ),
			'socicon-spip' => esc_html__( 'Spip', 'ziomm' ),
			'socicon-napster' => esc_html__( 'Napster', 'ziomm' ),
			'socicon-beatport' => esc_html__( 'Beatport', 'ziomm' ),
			'socicon-hackerone' => esc_html__( 'Hackerone', 'ziomm' ),
			'socicon-hackernews' => esc_html__( 'Hackernews', 'ziomm' ),
			'socicon-smashwords' => esc_html__( 'Smashwords', 'ziomm' ),
			'socicon-kobo' => esc_html__( 'Kobo', 'ziomm' ),
			'socicon-bookbub' => esc_html__( 'Bookbub', 'ziomm' ),
			'socicon-mailru' => esc_html__( 'Mailru', 'ziomm' ),
			'socicon-gitlab' => esc_html__( 'Gitlab', 'ziomm' ),
			'socicon-instructables' => esc_html__( 'Instructables', 'ziomm' ),
			'socicon-portfolio' => esc_html__( 'Portfolio', 'ziomm' ),
			'socicon-codered' => esc_html__( 'Codered', 'ziomm' ),
			'socicon-origin' => esc_html__( 'Origin', 'ziomm' ),
			'socicon-nextdoor' => esc_html__( 'Nextdoor', 'ziomm' ),
			'socicon-udemy' => esc_html__( 'Udemy', 'ziomm' ),
			'socicon-livemaster' => esc_html__( 'Livemaster', 'ziomm' ),
			'socicon-crunchbase' => esc_html__( 'Crunchbase', 'ziomm' ),
			'socicon-homefy' => esc_html__( 'Homefy', 'ziomm' ),
			'socicon-calendly' => esc_html__( 'Calendly', 'ziomm' ),
			'socicon-realtor' => esc_html__( 'Realtor', 'ziomm' ),
			'socicon-tidal' => esc_html__( 'Tidal', 'ziomm' ),
			'socicon-qobuz' => esc_html__( 'Qobuz', 'ziomm' ),
			'socicon-natgeo' => esc_html__( 'Natgeo', 'ziomm' ),
			'socicon-mastodon' => esc_html__( 'Mastodon', 'ziomm' ),
			'socicon-unsplash' => esc_html__( 'Unsplash', 'ziomm' ),
			'socicon-homeadvisor' => esc_html__( 'Homeadvisor', 'ziomm' ),
			'socicon-angieslist' => esc_html__( 'Angieslist', 'ziomm' ),
			'socicon-codepen' => esc_html__( 'Codepen', 'ziomm' ),
			'socicon-slack' => esc_html__( 'Slack', 'ziomm' ),
			'socicon-openaigym' => esc_html__( 'Openaigym', 'ziomm' ),
			'socicon-logmein' => esc_html__( 'Logmein', 'ziomm' ),
			'socicon-fiverr' => esc_html__( 'Fiverr', 'ziomm' ),
			'socicon-gotomeeting' => esc_html__( 'Gotomeeting', 'ziomm' ),
			'socicon-aliexpress' => esc_html__( 'Aliexpress', 'ziomm' ),
			'socicon-guru' => esc_html__( 'Guru', 'ziomm' ),
			'socicon-appstore' => esc_html__( 'Appstore', 'ziomm' ),
			'socicon-homes' => esc_html__( 'Homes', 'ziomm' ),
			'socicon-zoom' => esc_html__( 'Zoom', 'ziomm' ),
			'socicon-alibaba' => esc_html__( 'Alibaba', 'ziomm' ),
			'socicon-craigslist' => esc_html__( 'Craigslist', 'ziomm' ),
			'socicon-wix' => esc_html__( 'Wix', 'ziomm' ),
			'socicon-redfin' => esc_html__( 'Redfin', 'ziomm' ),
			'socicon-googlecalendar' => esc_html__( 'Googlecalendar', 'ziomm' ),
			'socicon-shopify' => esc_html__( 'Shopify', 'ziomm' ),
			'socicon-freelancer' => esc_html__( 'Freelancer', 'ziomm' ),
			'socicon-seedrs' => esc_html__( 'Seedrs', 'ziomm' ),
			'socicon-bing' => esc_html__( 'Bing', 'ziomm' ),
			'socicon-doodle' => esc_html__( 'Doodle', 'ziomm' ),
			'socicon-bonanza' => esc_html__( 'Bonanza', 'ziomm' ),
			'socicon-squarespace' => esc_html__( 'Squarespace', 'ziomm' ),
			'socicon-toptal' => esc_html__( 'Toptal', 'ziomm' ),
			'socicon-gust' => esc_html__( 'Gust', 'ziomm' ),
			'socicon-ask' => esc_html__( 'Ask', 'ziomm' ),
			'socicon-trulia' => esc_html__( 'Trulia', 'ziomm' ),
			'socicon-loomly' => esc_html__( 'Loomly', 'ziomm' ),
			'socicon-ghost' => esc_html__( 'Ghost', 'ziomm' ),
			'socicon-upwork' => esc_html__( 'Upwork', 'ziomm' ),
			'socicon-fundable' => esc_html__( 'Fundable', 'ziomm' ),
			'socicon-booking' => esc_html__( 'Booking', 'ziomm' ),
			'socicon-googlemaps' => esc_html__( 'Googlemaps', 'ziomm' ),
			'socicon-zillow' => esc_html__( 'Zillow', 'ziomm' ),
			'socicon-niconico' => esc_html__( 'Niconico', 'ziomm' ),
			'socicon-toneden' => esc_html__( 'Toneden', 'ziomm' ),
			'socicon-augment' => esc_html__( 'Augment', 'ziomm' ),
			'socicon-bitbucket' => esc_html__( 'Bitbucket', 'ziomm' ),
			'socicon-fyuse' => esc_html__( 'Fyuse', 'ziomm' ),
			'socicon-yt-gaming' => esc_html__( 'Yt-gaming', 'ziomm' ),
			'socicon-sketchfab' => esc_html__( 'Sketchfab', 'ziomm' ),
			'socicon-mobcrush' => esc_html__( 'Mobcrush', 'ziomm' ),
			'socicon-microsoft' => esc_html__( 'Microsoft', 'ziomm' ),
			'socicon-pandora' => esc_html__( 'Pandora', 'ziomm' ),
			'socicon-messenger' => esc_html__( 'Messenger', 'ziomm' ),
			'socicon-gamewisp' => esc_html__( 'Gamewisp', 'ziomm' ),
			'socicon-bloglovin' => esc_html__( 'Bloglovin', 'ziomm' ),
			'socicon-tunein' => esc_html__( 'Tunein', 'ziomm' ),
			'socicon-gamejolt' => esc_html__( 'Gamejolt', 'ziomm' ),
			'socicon-trello' => esc_html__( 'Trello', 'ziomm' ),
			'socicon-spreadshirt' => esc_html__( 'Spreadshirt', 'ziomm' ),
			'socicon-500px' => esc_html__( '500px', 'ziomm' ),
			'socicon-8tracks' => esc_html__( '8tracks', 'ziomm' ),
			'socicon-airbnb' => esc_html__( 'Airbnb', 'ziomm' ),
			'socicon-alliance' => esc_html__( 'Alliance', 'ziomm' ),
			'socicon-amazon' => esc_html__( 'Amazon', 'ziomm' ),
			'socicon-amplement' => esc_html__( 'Amplement', 'ziomm' ),
			'socicon-android' => esc_html__( 'Android', 'ziomm' ),
			'socicon-angellist' => esc_html__( 'Angellist', 'ziomm' ),
			'socicon-apple' => esc_html__( 'Apple', 'ziomm' ),
			'socicon-appnet' => esc_html__( 'Appnet', 'ziomm' ),
			'socicon-baidu' => esc_html__( 'Baidu', 'ziomm' ),
			'socicon-bandcamp' => esc_html__( 'Bandcamp', 'ziomm' ),
			'socicon-battlenet' => esc_html__( 'Battlenet', 'ziomm' ),
			'socicon-mixer' => esc_html__( 'Mixer', 'ziomm' ),
			'socicon-bebee' => esc_html__( 'Bebee', 'ziomm' ),
			'socicon-bebo' => esc_html__( 'Bebo', 'ziomm' ),
			'socicon-behance' => esc_html__( 'Bēhance', 'ziomm' ),
			'socicon-blizzard' => esc_html__( 'Blizzard', 'ziomm' ),
			'socicon-blogger' => esc_html__( 'Blogger', 'ziomm' ),
			'socicon-buffer' => esc_html__( 'Buffer', 'ziomm' ),
			'socicon-chrome' => esc_html__( 'Chrome', 'ziomm' ),
			'socicon-coderwall' => esc_html__( 'Coderwall', 'ziomm' ),
			'socicon-curse' => esc_html__( 'Curse', 'ziomm' ),
			'socicon-dailymotion' => esc_html__( 'Dailymotion', 'ziomm' ),
			'socicon-deezer' => esc_html__( 'Deezer', 'ziomm' ),
			'socicon-delicious' => esc_html__( 'Delicious', 'ziomm' ),
			'socicon-deviantart' => esc_html__( 'Deviantart', 'ziomm' ),
			'socicon-diablo' => esc_html__( 'Diablo', 'ziomm' ),
			'socicon-digg' => esc_html__( 'Digg', 'ziomm' ),
			'socicon-discord' => esc_html__( 'Discord', 'ziomm' ),
			'socicon-disqus' => esc_html__( 'Disqus', 'ziomm' ),
			'socicon-douban' => esc_html__( 'Douban', 'ziomm' ),
			'socicon-draugiem' => esc_html__( 'Draugiem', 'ziomm' ),
			'socicon-dribbble' => esc_html__( 'Dribbble', 'ziomm' ),
			'socicon-drupal' => esc_html__( 'Drupal', 'ziomm' ),
			'socicon-ebay' => esc_html__( 'Ebay', 'ziomm' ),
			'socicon-ello' => esc_html__( 'Ello', 'ziomm' ),
			'socicon-endomodo' => esc_html__( 'Endomodo', 'ziomm' ),
			'socicon-envato' => esc_html__( 'Envato', 'ziomm' ),
			'socicon-etsy' => esc_html__( 'Etsy', 'ziomm' ),
			'socicon-facebook' => esc_html__( 'Facebook', 'ziomm' ),
			'socicon-feedburner' => esc_html__( 'Feedburner', 'ziomm' ),
			'socicon-filmweb' => esc_html__( 'Filmweb', 'ziomm' ),
			'socicon-firefox' => esc_html__( 'Firefox', 'ziomm' ),
			'socicon-flattr' => esc_html__( 'Flattr', 'ziomm' ),
			'socicon-flickr' => esc_html__( 'Flickr', 'ziomm' ),
			'socicon-formulr' => esc_html__( 'Formulr', 'ziomm' ),
			'socicon-forrst' => esc_html__( 'Forrst', 'ziomm' ),
			'socicon-foursquare' => esc_html__( 'Foursquare', 'ziomm' ),
			'socicon-friendfeed' => esc_html__( 'Friendfeed', 'ziomm' ),
			'socicon-github' => esc_html__( 'Github', 'ziomm' ),
			'socicon-goodreads' => esc_html__( 'Goodreads', 'ziomm' ),
			'socicon-google' => esc_html__( 'Google', 'ziomm' ),
			'socicon-googlescholar' => esc_html__( 'Googlescholar', 'ziomm' ),
			'socicon-googlegroups' => esc_html__( 'Googlegroups', 'ziomm' ),
			'socicon-googlephotos' => esc_html__( 'Googlephotos', 'ziomm' ),
			'socicon-googleplus' => esc_html__( 'Googleplus', 'ziomm' ),
			'socicon-grooveshark' => esc_html__( 'Grooveshark', 'ziomm' ),
			'socicon-hackerrank' => esc_html__( 'Hackerrank', 'ziomm' ),
			'socicon-hearthstone' => esc_html__( 'Hearthstone', 'ziomm' ),
			'socicon-hellocoton' => esc_html__( 'Hellocoton', 'ziomm' ),
			'socicon-heroes' => esc_html__( 'Heroes', 'ziomm' ),
			'socicon-smashcast' => esc_html__( 'Smashcast', 'ziomm' ),
			'socicon-horde' => esc_html__( 'Horde', 'ziomm' ),
			'socicon-houzz' => esc_html__( 'Houzz', 'ziomm' ),
			'socicon-icq' => esc_html__( 'Icq', 'ziomm' ),
			'socicon-identica' => esc_html__( 'Identica', 'ziomm' ),
			'socicon-imdb' => esc_html__( 'Imdb', 'ziomm' ),
			'socicon-instagram' => esc_html__( 'Instagram', 'ziomm' ),
			'socicon-issuu' => esc_html__( 'Issuu', 'ziomm' ),
			'socicon-istock' => esc_html__( 'Istock', 'ziomm' ),
			'socicon-itunes' => esc_html__( 'Itunes', 'ziomm' ),
			'socicon-keybase' => esc_html__( 'Keybase', 'ziomm' ),
			'socicon-lanyrd' => esc_html__( 'Lanyrd', 'ziomm' ),
			'socicon-lastfm' => esc_html__( 'Lastfm', 'ziomm' ),
			'socicon-line' => esc_html__( 'Line', 'ziomm' ),
			'socicon-linkedin' => esc_html__( 'Linkedin', 'ziomm' ),
			'socicon-livejournal' => esc_html__( 'Livejournal', 'ziomm' ),
			'socicon-lyft' => esc_html__( 'Lyft', 'ziomm' ),
			'socicon-macos' => esc_html__( 'Macos', 'ziomm' ),
			'socicon-mail' => esc_html__( 'Mail', 'ziomm' ),
			'socicon-medium' => esc_html__( 'Medium', 'ziomm' ),
			'socicon-meetup' => esc_html__( 'Meetup', 'ziomm' ),
			'socicon-mixcloud' => esc_html__( 'Mixcloud', 'ziomm' ),
			'socicon-modelmayhem' => esc_html__( 'Modelmayhem', 'ziomm' ),
			'socicon-mumble' => esc_html__( 'Mumble', 'ziomm' ),
			'socicon-myspace' => esc_html__( 'Myspace', 'ziomm' ),
			'socicon-newsvine' => esc_html__( 'Newsvine', 'ziomm' ),
			'socicon-nintendo' => esc_html__( 'Nintendo', 'ziomm' ),
			'socicon-npm' => esc_html__( 'Npm', 'ziomm' ),
			'socicon-odnoklassniki' => esc_html__( 'Odnoklassniki', 'ziomm' ),
			'socicon-openid' => esc_html__( 'Openid', 'ziomm' ),
			'socicon-opera' => esc_html__( 'Opera', 'ziomm' ),
			'socicon-outlook' => esc_html__( 'Outlook', 'ziomm' ),
			'socicon-overwatch' => esc_html__( 'Overwatch', 'ziomm' ),
			'socicon-patreon' => esc_html__( 'Patreon', 'ziomm' ),
			'socicon-paypal' => esc_html__( 'Paypal', 'ziomm' ),
			'socicon-periscope' => esc_html__( 'Periscope', 'ziomm' ),
			'socicon-persona' => esc_html__( 'Persona', 'ziomm' ),
			'socicon-pinterest' => esc_html__( 'Pinterest', 'ziomm' ),
			'socicon-play' => esc_html__( 'Play', 'ziomm' ),
			'socicon-player' => esc_html__( 'Player', 'ziomm' ),
			'socicon-playstation' => esc_html__( 'Playstation', 'ziomm' ),
			'socicon-pocket' => esc_html__( 'Pocket', 'ziomm' ),
			'socicon-qq' => esc_html__( 'Qq', 'ziomm' ),
			'socicon-quora' => esc_html__( 'Quora', 'ziomm' ),
			'socicon-raidcall' => esc_html__( 'Raidcall', 'ziomm' ),
			'socicon-ravelry' => esc_html__( 'Ravelry', 'ziomm' ),
			'socicon-reddit' => esc_html__( 'Reddit', 'ziomm' ),
			'socicon-renren' => esc_html__( 'Renren', 'ziomm' ),
			'socicon-researchgate' => esc_html__( 'Researchgate', 'ziomm' ),
			'socicon-residentadvisor' => esc_html__( 'Residentadvisor', 'ziomm' ),
			'socicon-reverbnation' => esc_html__( 'Reverbnation', 'ziomm' ),
			'socicon-rss' => esc_html__( 'Rss', 'ziomm' ),
			'socicon-sharethis' => esc_html__( 'Sharethis', 'ziomm' ),
			'socicon-skype' => esc_html__( 'Skype', 'ziomm' ),
			'socicon-slideshare' => esc_html__( 'Slideshare', 'ziomm' ),
			'socicon-smugmug' => esc_html__( 'Smugmug', 'ziomm' ),
			'socicon-snapchat' => esc_html__( 'Snapchat', 'ziomm' ),
			'socicon-songkick' => esc_html__( 'Songkick', 'ziomm' ),
			'socicon-soundcloud' => esc_html__( 'Soundcloud', 'ziomm' ),
			'socicon-spotify' => esc_html__( 'Spotify', 'ziomm' ),
			'socicon-stackexchange' => esc_html__( 'Stackexchange', 'ziomm' ),
			'socicon-stackoverflow' => esc_html__( 'Stackoverflow', 'ziomm' ),
			'socicon-starcraft' => esc_html__( 'Starcraft', 'ziomm' ),
			'socicon-stayfriends' => esc_html__( 'Stayfriends', 'ziomm' ),
			'socicon-steam' => esc_html__( 'Steam', 'ziomm' ),
			'socicon-storehouse' => esc_html__( 'Storehouse', 'ziomm' ),
			'socicon-strava' => esc_html__( 'Strava', 'ziomm' ),
			'socicon-streamjar' => esc_html__( 'Streamjar', 'ziomm' ),
			'socicon-stumbleupon' => esc_html__( 'Stumbleupon', 'ziomm' ),
			'socicon-swarm' => esc_html__( 'Swarm', 'ziomm' ),
			'socicon-teamspeak' => esc_html__( 'Teamspeak', 'ziomm' ),
			'socicon-teamviewer' => esc_html__( 'Teamviewer', 'ziomm' ),
			'socicon-technorati' => esc_html__( 'Technorati', 'ziomm' ),
			'socicon-telegram' => esc_html__( 'Telegram', 'ziomm' ),
			'socicon-tripadvisor' => esc_html__( 'Tripadvisor', 'ziomm' ),
			'socicon-tripit' => esc_html__( 'Tripit', 'ziomm' ),
			'socicon-triplej' => esc_html__( 'Triplej', 'ziomm' ),
			'socicon-tumblr' => esc_html__( 'Tumblr', 'ziomm' ),
			'socicon-twitch' => esc_html__( 'Twitch', 'ziomm' ),
			'socicon-twitter' => esc_html__( 'Twitter', 'ziomm' ),
			'socicon-uber' => esc_html__( 'Uber', 'ziomm' ),
			'socicon-ventrilo' => esc_html__( 'Ventrilo', 'ziomm' ),
			'socicon-viadeo' => esc_html__( 'Viadeo', 'ziomm' ),
			'socicon-viber' => esc_html__( 'Viber', 'ziomm' ),
			'socicon-viewbug' => esc_html__( 'Viewbug', 'ziomm' ),
			'socicon-vimeo' => esc_html__( 'Vimeo', 'ziomm' ),
			'socicon-vine' => esc_html__( 'Vine', 'ziomm' ),
			'socicon-vkontakte' => esc_html__( 'Vkontakte', 'ziomm' ),
			'socicon-warcraft' => esc_html__( 'Warcraft', 'ziomm' ),
			'socicon-wechat' => esc_html__( 'Wechat', 'ziomm' ),
			'socicon-weibo' => esc_html__( 'Weibo', 'ziomm' ),
			'socicon-whatsapp' => esc_html__( 'Whatsapp', 'ziomm' ),
			'socicon-wikipedia' => esc_html__( 'Wikipedia', 'ziomm' ),
			'socicon-windows' => esc_html__( 'Windows', 'ziomm' ),
			'socicon-wordpress' => esc_html__( 'Wordpress', 'ziomm' ),
			'socicon-wykop' => esc_html__( 'Wykop', 'ziomm' ),
			'socicon-xbox' => esc_html__( 'Xbox', 'ziomm' ),
			'socicon-xing' => esc_html__( 'Xing', 'ziomm' ),
			'socicon-yahoo' => esc_html__( 'Yahoo', 'ziomm' ),
			'socicon-yammer' => esc_html__( 'Yammer', 'ziomm' ),
			'socicon-yandex' => esc_html__( 'Yandex', 'ziomm' ),
			'socicon-yelp' => esc_html__( 'Yelp', 'ziomm' ),
			'socicon-younow' => esc_html__( 'Younow', 'ziomm' ),
			'socicon-youtube' => esc_html__( 'Youtube', 'ziomm' ),
			'socicon-zapier' => esc_html__( 'Zapier', 'ziomm' ),
			'socicon-zerply' => esc_html__( 'Zerply', 'ziomm' ),
			'socicon-zomato' => esc_html__( 'Zomato', 'ziomm' ),
			'socicon-zynga' => esc_html__( 'Zynga', 'ziomm' )
		);
		return apply_filters( 'ziomm/get_social_icons', $social_icons );
	}
}

/**
 * Get Elementor templates
 */
if ( ! function_exists( 'ziomm_get_elementor_templates' ) ) {
	function ziomm_get_elementor_templates( $type = null ) {

		$args = [
			'post_type' => 'elementor_library',
			'posts_per_page' => -1,
		];

		if ( $type ) {

			$args[ 'tax_query' ] = [
				[
					'taxonomy' => 'elementor_library_type',
					'field' => 'slug',
					'terms' => $type,
				],
			];

		}

		$page_templates = get_posts( $args );

		$options[0] = esc_html__( 'Select a Template', 'ziomm' );

		if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ) {
			foreach ( $page_templates as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}
		} else {

			$options[0] = esc_html__( 'Create a Template First', 'ziomm' );

		}

		return $options;

	}
}

/**
 * Get hsl variables
 */
if ( ! function_exists( 'ziomm_get_hsl_variables' ) ) {
	function ziomm_get_hsl_variables( $name, $color ) {
		if ( class_exists( 'ariColor' ) ) {
			$color_obj = ariColor::newColor( $color );
			$new_color = "{$name}-h: {$color_obj->hue};";
			$new_color .= "{$name}-s: {$color_obj->saturation}%;";
			$new_color .= "{$name}-l: {$color_obj->lightness}%;";
			return $new_color;
		}
		return "{$name}: {$color};";
	}
}