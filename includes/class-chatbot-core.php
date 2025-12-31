<?php
/**
 * Core functionality for the AI Chatbot
 */

class AI_Chatbot_Llama_Core
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_chatbot'));
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts()
    {
        if (!get_option('ai_chatbot_llama_enabled')) {
            return;
        }

        // Enqueue Highlight.js for syntax highlighting
        wp_enqueue_style(
            'ai-chatbot-highlight-css',
            'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css',
            array(),
            '11.9.0'
        );

        wp_enqueue_script(
            'ai-chatbot-highlight-js',
            'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js',
            array(),
            '11.9.0',
            true
        );

        // Enqueue Marked.js for markdown parsing
        wp_enqueue_script(
            'ai-chatbot-marked-js',
            'https://cdn.jsdelivr.net/npm/marked@11.1.1/marked.min.js',
            array(),
            '11.1.1',
            true
        );

        wp_enqueue_style(
            'ai-chatbot-llama-style',
            AI_CHATBOT_LLAMA_PLUGIN_URL . 'assets/css/chatbot.css',
            array('ai-chatbot-highlight-css'),
            AI_CHATBOT_LLAMA_VERSION
        );

        wp_enqueue_script(
            'ai-chatbot-llama-script',
            AI_CHATBOT_LLAMA_PLUGIN_URL . 'assets/js/chatbot.js',
            array('jquery', 'ai-chatbot-marked-js', 'ai-chatbot-highlight-js'),
            AI_CHATBOT_LLAMA_VERSION,
            true
        );

        // Localize script with settings
        wp_localize_script('ai-chatbot-llama-script', 'aiChatbotLlama', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ai_chatbot_llama_nonce'),
            'primaryColor' => get_option('ai_chatbot_llama_primary_color', '#B91C1C'),
            'position' => get_option('ai_chatbot_llama_chat_position', 'bottom-right')
        ));
    }

    /**
     * Render chatbot HTML in footer
     */
    public function render_chatbot()
    {
        if (!get_option('ai_chatbot_llama_enabled')) {
            return;
        }

        include AI_CHATBOT_LLAMA_PLUGIN_DIR . 'templates/chatbot-widget.php';
    }

    /**
     * Generate unique session ID
     */
    public static function get_session_id()
    {
        if (!isset($_COOKIE['ai_chatbot_session_id'])) {
            $session_id = uniqid('chatbot_', true);
            setcookie('ai_chatbot_session_id', $session_id, time() + (86400 * 30), '/');
            return $session_id;
        }
        return $_COOKIE['ai_chatbot_session_id'];
    }

    /**
     * Save conversation to database
     */
    public static function save_conversation($session_id, $message, $response)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ai_chatbot_conversations';

        $wpdb->insert(
            $table_name,
            array(
                'session_id' => $session_id,
                'user_id' => get_current_user_id(),
                'message' => sanitize_text_field($message),
                'response' => sanitize_textarea_field($response)
            ),
            array('%s', '%d', '%s', '%s')
        );
    }

    /**
     * Get conversation history
     */
    public static function get_conversation_history($session_id, $limit = 10)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ai_chatbot_conversations';

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT message, response, created_at FROM $table_name 
                WHERE session_id = %s 
                ORDER BY created_at DESC 
                LIMIT %d",
                $session_id,
                $limit
            ),
            ARRAY_A
        );

        return array_reverse($results);
    }
}
