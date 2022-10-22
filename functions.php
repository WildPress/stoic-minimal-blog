<?php
/**
 * Stoic WP functions and definitions
 *
 * @package StoicWP
 * @subpackage MinimalBlogTheme
 */

namespace StoicWP;

// Global constants
define( 'STOICWP_THEME_VERSION', '1.0.0' );
define( 'STOICWP_THEME_TEMPLATE_URL', get_template_directory_uri() . '/' );
define( 'STOICWP_THEME_PATH', get_template_directory() . '/' );
define( 'STOICWP_THEME_DIST_PATH', STOICWP_THEME_PATH . 'dist' );
define( 'STOICWP_THEME_DIST_URL', STOICWP_THEME_TEMPLATE_URL . 'dist' );
define( 'STOICWP_THEME_INC', STOICWP_THEME_PATH . 'includes/' );
define( 'STOICWP_THEME_BLOCK_DIR', STOICWP_THEME_INC . 'blocks/' );

require_once(STOICWP_THEME_INC . 'core.php');

Core\setup();