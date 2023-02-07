<?php

namespace WPAIToolbox;

class OpenAI {
    use Singleton;

    public function start() {
        add_action('wp_ajax_ai_summary', [$this, 'ai_summary']);
        add_action('wp_ajax_nopriv_ai_summary', [$this, 'ai_summary']);
    }

    protected function get_api_key() {
        return get_option('wpaitb_api_key');
    }

    public function get_client() {
        return \OpenAI::client($this->get_api_key());
    }

}

