<?php

namespace WPAIToolbox;

class Setup {
    use Singleton;

    public function start() {

        //Start settings
        Settings::instance()->start();

        //Load API endpoints
        Endpoints::instance()->start();

        //Register blocks
        add_action('init', [$this, 'register_blocks']);

        //Register post meta
        add_action('init', [$this, 'register_summary_post_meta']);

    }

    public function scripts() {

    }

    public function styles() {

    }

    public function register_blocks() {
        register_block_type(WPAITB_PATH.'inc/blocks/ai-summary/build');
    }

    function register_summary_post_meta() {

    }
}