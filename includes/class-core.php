<?php

namespace StoicWP;

if (!defined("ABSPATH")) {
    exit();
}

final class Core
{
    protected static ?self $instance = null;
    protected object $manifest;
    protected string $version;

    private function __construct()
    {
        // Fetch manifest
        $this->manifest = json_decode(file_get_contents(STOICWP_THEME_PATH . 'dist/mix-manifest.json') ?: "{}");

        // Fetch theme version
        $theme_version = wp_get_theme()->get('Version');
        $this->version = is_string($theme_version) ? $theme_version : false;
    }

    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setup(): void
    {
        add_action('after_setup_theme', [$this, "i18n"]);
        add_action('after_setup_theme', [$this, "setup_theme"]);
        add_action('wp_enqueue_scripts', [$this, "scripts"]);
        add_action('wp_enqueue_scripts', [$this, "styles"]);
        add_action('wp_head', [$this, "preload_fonts"]);
    }

    /**
     * Enable support for translations
     */
    public function i18n(): void
    {
        load_theme_textdomain('stoic-wp', STOICWP_THEME_PATH . '/languages');
    }

    /**
     * Set up the theme.
     *
     * @return void
     */
    public function setup_theme(): void
    {
        // Add support for block styles
        add_theme_support('wp-block-styles');

        // Enqueue editor styles
        add_editor_style('/dist' . $this->manifest->{'/stoic-wp.css'});
    }

    /**
     * Enqueue front-end scripts
     *
     * @return void
     */
    public function scripts(): void
    {
        wp_enqueue_script('stoicwp-frontend', STOICWP_THEME_DIST_URL . $this->manifest->{'/stoic-wp.js'}, [], $this->version, true);
    }

    /**
     * Enqueue front-end styles
     *
     * @return void
     */
    public function styles(): void
    {
        wp_register_style(
            'stoicwp-frontend',
            STOICWP_THEME_DIST_URL . $this->manifest->{'/stoic-wp.css'},
            [],
            $this->version
        );

        // Inline critical CSS
        $critical_css = file_get_contents(STOICWP_THEME_DIST_PATH . '/critical.css');
        wp_add_inline_style('stoicwp-frontend', $critical_css);

        // Enqueue theme stylesheet
        wp_enqueue_style('stoicwp-frontend');
    }

    /**
     * Preload fonts
     *
     * @return void
     */
    public function preload_fonts(): void
    {
        $preloaded_fonts = apply_filters('stoicwp_preloaded_fonts', [
            STOICWP_THEME_DIST_URL . $this->manifest->{'/fonts/Inter-Regular.woff2'},
            STOICWP_THEME_DIST_URL . $this->manifest->{'/fonts/Inter-Bold.woff2'},
        ]);

        foreach ($preloaded_fonts as $font) {
            echo "<link rel=\"preload\" href=\"{$font}\" as=\"font\" type=\"font/woff2\" crossorigin>";
        }
    }
}