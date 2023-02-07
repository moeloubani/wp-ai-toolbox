<?php

namespace WPAIToolbox;

class Settings {
    use Singleton;
    public string $openai_api_key;

    public function start() {
        //Add settings page
        add_action('admin_menu', [$this, 'add_settings_page']);

        //Register settings
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_menu_page(
            __( 'WP AI Toolbox Settings', 'ai-summary' ),
            'WP AI Toolbox',
            'manage_options',
            'wp-ai-toolbox-settings',
            [$this, 'settings_page_callback'],
            'dashicons-superhero',
            99
        );
    }

    public function settings_page_callback() {
        $this->openai_api_key = get_option('wpaitb_api_key');
        ob_start();
        include(WPAITB_PATH . 'templates/admin/settings.php');
        echo ob_get_clean();
    }

    public function register_settings() {
        register_setting( 'wpaitb_settings', 'wpaitb_api_key', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest' => true,
        ] );

        register_setting( 'wpaitb_settings', 'wpaitb_default_audience', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest' => true,
        ] );

        register_setting( 'wpaitb_settings', 'wpaitb_summary_length', [
            'type' => 'number',
            'sanitize_callback' => [ $this, 'sanitize_number_field' ],
            'show_in_rest' => true,
        ] );

        add_settings_section(
            'wpaitb_main_settings', // ID
            'API Settings', // Title
            '',
            'wp-ai-toolbox-settings' // Page
        );

        add_settings_field(
            'api_key', // ID
            'API Key', // Title
            [ $this, 'api_key_callback' ], // Callback
            'wp-ai-toolbox-settings',
            'wpaitb_main_settings'
        );

        add_settings_field(
            'default_audience', // ID
            'Default Audience', // Title
            [ $this, 'default_audience_callback' ], // Callback
            'wp-ai-toolbox-settings',
            'wpaitb_main_settings'
        );

        add_settings_field(
            'summary_length', // ID
            'Summary Length', // Title
            [ $this, 'summary_length_callback' ], // Callback
            'wp-ai-toolbox-settings',
            'wpaitb_main_settings'
        );

    }

    public function api_key_callback() {
        $api_key = get_option('wpaitb_api_key');
        printf(
            '<input type="text" id="wpaitb_api_key" name="wpaitb_api_key" value="%s" />',
            isset( $api_key ) ? esc_attr( $api_key) : ''
        );
    }

    public function default_audience_callback() {
        $audience = get_option('wpaitb_default_audience');
        printf(
            '<input type="text" id="wpaitb_default_audience" name="wpaitb_default_audience" value="%s" />',
            !empty( $audience ) ? esc_attr( $audience) : 'young adults'
        );
    }

    public function summary_length_callback() {
        $length = get_option('wpaitb_summary_length');
        printf(
            '<input type="text" id="wpaitb_summary_length" name="wpaitb_summary_length" value="%s" />',
            !empty( $length ) ? esc_attr( $length) : '200'
        );
    }

    public function sanitize_number_field($input) {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

}