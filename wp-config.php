<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'syperd91_ecopuls');

/** MySQL database username */
define('DB_USER', 'syperd91_ecopuls');

/** MySQL database password */
define('DB_PASSWORD', '8*cW0tC@');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '(x?60l-!i4{&kDl^V#3 C%~TKRr.sZs%_PB+qi,GV)t<,a*AUHS27-zDU=,4rGg9');
define('SECURE_AUTH_KEY',  '_cZa%OW6H-ESgS_^~MJfBKFD-]QB<R7oIG,2ny]lS(0C!c+|Lc<W1*^2>6WF}l}&');
define('LOGGED_IN_KEY',    'jJEP^F^+MDo/LUR3&{~()H>z}4x0B/4L@G_#!;}7PF,KIn*npKIk)gpb?PS3QY_X');
define('NONCE_KEY',        'I+LXL[E*?+JW,=][QPsxa-?H:s7Mgx7 }`dl}n^o%zb*@ 18z,|JMCr?0#]r!9;l');
define('AUTH_SALT',        '>,mb,+v;1lf-ngh{OVfHv3heOj} +raY_Wb)}4,@kr<iJ(PW0)2j/NC0:Bwsom,H');
define('SECURE_AUTH_SALT', 'z)Y_lytj3_g8<~TCPZN/Q|cLNWae;P!Z%sUgxaek{o]g.T*v}{s>DW+!m36LZqj,');
define('LOGGED_IN_SALT',   '`qF*LpmO1UCYd+CG&V}7L>wrIfE;2~pp[/8-8jk-;ti2 7$_MXc#5FTMB|2rJ.:=');
define('NONCE_SALT',       'rHKu6+T8S3o[D085h+<!iM;1mINEZ^__<MI5x`WjE2|yUN}Y=r8H]-w<WOKD:{s?');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'vzjdi_';


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
#define('WP_DEBUG', true);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_POST_REVISIONS', 6);
define('DISALLOW_FILE_EDIT', true);
define('NINJA_FORMS_JS_DEBUG', false);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Move wp-content **/
$host = 'ecopulsar.ru'; //define your host rules here
define( 'WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content' );
define( 'WP_CONTENT_URL', '//'.$host.'/wp-content' );
	
	
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
