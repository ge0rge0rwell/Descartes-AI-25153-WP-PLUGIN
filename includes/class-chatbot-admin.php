<?php
/**
 * Admin panel functionality
 */

class AI_Chatbot_Llama_Admin
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_ai_chatbot_test_connection', array($this, 'test_connection'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu()
    {
        add_menu_page(
            'AI Chatbot Settings',
            'AI Chatbot',
            'manage_options',
            'ai-chatbot-llama',
            array($this, 'render_settings_page'),
            'dashicons-format-chat',
            30
        );

        add_submenu_page(
            'ai-chatbot-llama',
            'Conversations',
            'Conversations',
            'manage_options',
            'ai-chatbot-conversations',
            array($this, 'render_conversations_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        // Google Gemini settings
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_gemini_api_key');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_gemini_model');

        // Ollama settings
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_provider');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_remote_url');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_api_endpoint');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_model');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_openai_api_key');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_openai_model');

        // Common settings
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_temperature');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_max_tokens');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_system_prompt');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_context'); // New context setting
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_chat_position');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_primary_color');
        register_setting('ai_chatbot_llama_settings', 'ai_chatbot_llama_enabled');
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook)
    {
        if (strpos($hook, 'ai-chatbot-llama') === false) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // Enqueue PDF.js for client-side parsing
        wp_enqueue_script('pdf-js', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js', array(), '3.11.174', true);
        wp_enqueue_script('pdf-js-worker', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js', array(), '3.11.174', true);

        wp_enqueue_style(
            'ai-chatbot-llama-admin',
            AI_CHATBOT_LLAMA_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            AI_CHATBOT_LLAMA_VERSION
        );

        wp_enqueue_script(
            'ai-chatbot-llama-admin',
            AI_CHATBOT_LLAMA_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'wp-color-picker', 'pdf-js'),
            AI_CHATBOT_LLAMA_VERSION,
            true
        );

        wp_localize_script('ai-chatbot-llama-admin', 'aiChatbotAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ai_chatbot_admin_nonce'),
            'pdfWorkerSrc' => 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js'
        ));
    }

    /**
     * Render settings page
     */
    public function render_settings_page()
    {
        include AI_CHATBOT_LLAMA_PLUGIN_DIR . 'templates/admin-settings.php';
    }

    /**
     * Render conversations page
     */
    public function render_conversations_page()
    {
        include AI_CHATBOT_LLAMA_PLUGIN_DIR . 'templates/admin-conversations.php';
    }

    /**
     * Test API connection via AJAX
     */
    public function test_connection()
    {
        check_ajax_referer('ai_chatbot_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $result = AI_Chatbot_Llama_API::test_connection();

        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
}
