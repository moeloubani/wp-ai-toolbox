<?php

namespace WPAIToolbox;

class Endpoints {
    use Singleton;

    public function start() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'wp-ai-toolbox/v1';

        register_rest_route($namespace, '/summarize', [
            'methods' => 'POST',
            'callback' => [Summarize::instance(), 'generate'],
            'permission_callback' => [$this, 'permission_callback'],
        ]);
    }

    public function permission_callback() {
        return current_user_can('edit_posts');
    }
}