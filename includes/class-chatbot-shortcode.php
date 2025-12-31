<?php
/**
 * Shortcode functionality
 */

class AI_Chatbot_Llama_Shortcode
{

    public function __construct()
    {
        add_shortcode('ai_chatbot', array($this, 'render_chatbot_shortcode'));
    }

    /**
     * Render chatbot shortcode
     * Usage: [ai_chatbot]
     */
    public function render_chatbot_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'height' => '600px',
            'width' => '100%'
        ), $atts);

        ob_start();
        include AI_CHATBOT_LLAMA_PLUGIN_DIR . 'templates/chatbot-inline.php';
        return ob_get_clean();
    }
}
