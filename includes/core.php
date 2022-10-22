<?php

namespace StoicWP\Core;

/**
 * Set up the theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup() {
	add_action( 'after_setup_theme', __NAMESPACE__ . "\\i18n" );
	add_action( 'after_setup_theme', __NAMESPACE__ . "\\setup_theme" );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . "\\scripts" );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . "\\styles" );
	add_action( 'wp_head', __NAMESPACE__ . "\\preload_fonts" );
}

/**
 * Enable support for translations
 */
function i18n(): void {
	load_theme_textdomain( 'stoicwp', STOICWP_THEME_PATH . '/languages' );
}

/**
 * Set up the theme.
 *
 * @return void
 */
function setup_theme(): void {
	// Add support for block styles
	add_theme_support( 'wp-block-styles' );

	// Enqueue editor styles
	add_editor_style( '/dist/css/style.min.css' );
}

/**
 * Enqueue front-end scripts
 *
 * @return void
 */
function scripts(): void {
	$theme_version  = wp_get_theme()->get( 'Version' );
	$version_string = is_string( $theme_version ) ? $theme_version : false;

	$manifest = json_decode( file_get_contents( STOICWP_THEME_PATH . 'dist/mix-manifest.json' ) ?: "{}" );

	wp_enqueue_script( 'stoicwp-frontend', STOICWP_THEME_DIST_URL . $manifest->{'/stoicwp.js'}, [], $version_string, true );
}

/**
 * Enqueue front-end styles
 *
 * @return void
 */
function styles(): void {
	// Register theme stylesheet.
	$theme_version  = wp_get_theme()->get( 'Version' );
	$version_string = is_string( $theme_version ) ? $theme_version : false;

	$manifest = json_decode( file_get_contents( STOICWP_THEME_PATH . 'dist/mix-manifest.json' ) ?: "{}" );

	wp_register_style(
		'stoicwp-frontend',
		STOICWP_THEME_DIST_URL . $manifest->{'/stoicwp.css'},
		[],
		$version_string
	);

	// Inline critical CSS
	$critical_css = file_get_contents( STOICWP_THEME_DIST_PATH . '/critical.css' );
	wp_add_inline_style( 'stoicwp-frontend', $critical_css );

	// Enqueue theme stylesheet
	wp_enqueue_style( 'stoicwp-frontend' );
}

/**
 * Preload fonts
 *
 * @return void
 */
function preload_fonts(): void {
	$manifest = json_decode( file_get_contents( STOICWP_THEME_PATH . 'dist/mix-manifest.json' ) ?: "{}" );

	$preloaded_fonts = apply_filters( 'stoicwp_preloaded_fonts', [
		STOICWP_THEME_DIST_URL . $manifest->{'/fonts/Inter-Regular.woff2'},
		STOICWP_THEME_DIST_URL . $manifest->{'/fonts/Inter-Bold.woff2'},
	] );

	foreach ( $preloaded_fonts as $font ) {
		echo "<link rel=\"preload\" href=\"{$font}\" as=\"font\" type=\"font/woff2\" crossorigin>";
	}
}