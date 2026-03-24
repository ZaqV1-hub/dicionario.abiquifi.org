<?php
define( 'WP_CACHE', false ); // Added by AccelerateWP

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
define( 'DB_NAME', 'abiquifi_usbl1' );

/** Database username */
define( 'DB_USER', 'abiquifi_usbl1' );

/** Database password */
define( 'DB_PASSWORD', 'M^XU7kGV56^GM2SMiu.86.~5' );

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
define('AUTH_KEY',         '7TiZWzhYjlZZ6HLrfpgOFPRp0ynpDwidSLr7iNrQ3fQwtodU5pnGkj3xHBX2nwV5');
define('SECURE_AUTH_KEY',  'iy3gXcFbEK7D2Sw9bdgnJ0VirSknAzgBdinIkeQOutsD7L5ipjKqr0uGcZoUTWYv');
define('LOGGED_IN_KEY',    'VdxFeJ09odbI021y1laI3TbKtdJUINCz6owLGfQe8eNxctT17lFXh8B86iU00RjX');
define('NONCE_KEY',        '07zIvHVTptfuIB5budumxHRzDSMLJOno9bcpWYQkmXvd5bpFuXPgk7eyqXfFy3l8');
define('AUTH_SALT',        'vw0DsHRcaTK3ofDfnEy3Nxn5SBu3p88hwtJntl6TDOGQV9SLuZY4uUpJQOdJabml');
define('SECURE_AUTH_SALT', 'MY4iCipzdEpIfMN2sitXNebSIelBZXDchgnFktTRPD54SqIK416TzY7ru0P79s9L');
define('LOGGED_IN_SALT',   'J7ogtUkDQcivAmshEQae6HwbPCcO4ht7jfvNYIryZLVjYP2rN3pwSNxZsMr2znaZ');
define('NONCE_SALT',       'U1KrMq5Aw2BdShYIPXLnFcGS3FzKcDklIBXxkWuOzjYYTbFBLYpKuXVTvPU09h3M');

/**
 * Other customizations.
 */
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'oyqm_';

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

define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
