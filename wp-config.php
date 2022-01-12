<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'trabajosjovenes' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'mWoYciyYyS1KqH' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'whcu5iz1g10ygbl8irxnr1romjsbdtyt7wmkkzii6wupqoluc6kq17y4yx1i0zsj' );
define( 'SECURE_AUTH_KEY',  'pzs7hpfopmtro7luw24s6bipkcgkbee20rfdxexqe5k2hyfowdbtbf3bmhk7s3tj' );
define( 'LOGGED_IN_KEY',    'edkkllzjorfzceiijfeogx3lvmx4nmrdkffizklc4hp3vdo0jxcfecubzcnipes8' );
define( 'NONCE_KEY',        'c6bhvsif9bkk8t9htsiipqa6pfukswwo2evfax4uqjliw7r50muu9wc0ripahdai' );
define( 'AUTH_SALT',        'ugoo3ugortjcyk5cga5saql3k9zs6rt340lpnri1bnruxd9hlqgsjqrh0zhwk10y' );
define( 'SECURE_AUTH_SALT', 'hhzogprqbkl95ih4ddzhnolz5y4vku6yuyxhrgvbwrqmunqdahmlbs3lwn9z8ayb' );
define( 'LOGGED_IN_SALT',   'xjiux4buxbpvrgouz0wfbmktad7ru8homnmvilszvvezpsw8yxi67atqal144iqb' );
define( 'NONCE_SALT',       'ybwzhug6mq9zpgy4rotwb22zpieeetabpkduib8cyfqfxepzlwcmsyd2uy2rwtrs' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp1i_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define('FS_METHOD','direct');
