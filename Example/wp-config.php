<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'live_E6w5g' );

/** Database username */
define( 'DB_USER', 'live_user_E6w5g' );

/** Database password */
define( 'DB_PASSWORD', 'l2njq4DvJTY9YbbYnJIdJesYM3vrhxsTyR' );

/** Database hostname */
define( 'DB_HOST', 'mysql.10web.site' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          ')b$>*By0q2x@CAxel9Jfg{El=1Y^F~B1NSBQP[w0-$eI|{q}S0[@q&`5F6t*>%;]' );
define( 'SECURE_AUTH_KEY',   'HL7r3h=eWRK7?%y(>Ai{s{q6%5M9Zp_PdvZ}h&cirDcAO%F/{`yu<Z{d6A9(m5NP' );
define( 'LOGGED_IN_KEY',     '$cOEN3vBG.OQTL-NcJ Tx~Z@PVkU@GgW]yoJBL8M3jCVU_RK 3$WXy<>8,nvb,G<' );
define( 'NONCE_KEY',         'i<X6`FgYY`GBcPREO3(~dH#1YxRSrX?8J_;N@Vd$NjL1ePu-:SU_)Z<b!g#Ps20~' );
define( 'AUTH_SALT',         '|o!//># f2T RvF[8D*>qxK>PS#d89!))Kj8]Ti{AzpUdw|uVC@zzQi++l|I3$W?' );
define( 'SECURE_AUTH_SALT',  '7o9&Q,T/2#GRXPfq7;;|`]X,Eyk<up!BMShx; @y`T5Z!I8QR=?5MC%)z</:>ed:' );
define( 'LOGGED_IN_SALT',    '.6Cx&=c9%1t9f)Bh`:=WIk${_f2]0xquc*9g$3B1Wm)l,6owe1V]ISa;r7gg?vFu' );
define( 'NONCE_SALT',        'fD@hxVO(JGmBZ3HQ3B XNMM]2J?);,d3(p#C62 4uYM=I?AbyrEs/t5]FNCNg248' );
define( 'WP_CACHE_KEY_SALT', '[t=rg2;#J#!zn7n7|aMNg@c1xQ!P&]`0/RFXp{te/ee*d]!&[bc-DGx){j6@pO .' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_REDIS_HOST', '10.44.10.10' );
define( 'WP_REDIS_PREFIX', 'TENWEBLXC-998518-object-cache-' );
define( 'WP_REDIS_PASSWORD', ["redis_user_998518","6meyeY11z7evuJhY1iL5BUL4VFdMSsH7tP"] );
define( 'WP_REDIS_MAXTTL', '360' );
define( 'WP_REDIS_IGNORED_GROUPS', ["comment","counts","plugins","wc_session_id"] );
define( 'WP_REDIS_GLOBAL_GROUPS', ["users","userlogins","useremail","userslugs","usermeta","user_meta","site-transient","site-options","site-lookup","site-details","blog-lookup","blog-details","blog-id-cache","rss","global-posts","global-cache-test"] );
define( 'WP_REDIS_TIMEOUT', '5' );
define( 'WP_REDIS_READ_TIMEOUT', '5' );
define( 'TENWEB_OBJECT_CACHE', '1' );
define( 'TENWEB_CACHE', '1' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
