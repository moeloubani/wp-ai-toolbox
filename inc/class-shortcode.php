<?php

namespace WPAIToolbox;

class Shortcode {
    use Singleton;

    public function start() {
        add_shortcode('ai_summary', [$this, 'ai_summary_shortcode']);
    }

    public function ai_summary_shortcode() {
        $current_page = get_the_ID();

        $summary = get_post_meta($current_page, 'ai_summary', true);

        if(!$summary) {
            $summary = OpenAI::instance()->get_summary($current_page);
            update_post_meta($current_page, 'ai_summary', $summary);
        }
    }

}