<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dragonhb' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '1234' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '5w;*{BScJDC[cvMI*I]hPR.+n6}j0~WE{V^y]g7WlE<u1TF2Phg}oejX[nI,Q*Xs' );
define( 'SECURE_AUTH_KEY',  'pKuwoMA9,r<mDIQ(XtK6m.E)ET +Z+kZQ0}svpc57<>>w<H=d+X*(`l+T!TBU*!A' );
define( 'LOGGED_IN_KEY',    '&S.-@g%Af!X}TrwN;y)j6#QJxdlbKi 8dzHmfY9m}V/.f,@r;*Sz{otp66*.,Hv*' );
define( 'NONCE_KEY',        '?OF!9w=LV<ap48plBZP0{v|>aF8i3A3b|##a>4OCpy.V78atn)*E_yc6[5/`N9Iq' );
define( 'AUTH_SALT',        '{ysUMT%ALgq_C+4SC$a^,_wIRy/6~8;:Wki<$qD^/Q!%HUeChQaA342z/,F3eH/m' );
define( 'SECURE_AUTH_SALT', 'N.s?kn+ru@h_]cxndm}&MC1)*z37mG;-{|ZeAiJ=a+>wC7;ti8.D6m)Ly3F/J[Xh' );
define( 'LOGGED_IN_SALT',   'v lB3~VhBv!+ PzTO!Ul>*qx B=KT_;,{;T<H{gsBc*C/%F37;4)yj1[+h:H*D&-' );
define( 'NONCE_SALT',       '{soOkOvzn&uDI$HI%@~Qgq1C2(UD`p<vsx^??,^Z==|-oWeM+f&_D;ih~%2b0w.`' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
