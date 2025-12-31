<?php
/**
 * Plugin Name: AI Chatbot with Llama
 * Plugin URI: https://example.com/ai-chatbot-llama
 * Description: A powerful AI chatbot plugin that integrates with Llama models for intelligent conversations
 * Version: 1.0.0
 * Author: Oğuzhan Acar
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-chatbot-llama
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AI_CHATBOT_LLAMA_VERSION', '1.0.0');
define('AI_CHATBOT_LLAMA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AI_CHATBOT_LLAMA_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once AI_CHATBOT_LLAMA_PLUGIN_DIR . 'includes/class-chatbot-core.php';
require_once AI_CHATBOT_LLAMA_PLUGIN_DIR . 'includes/class-chatbot-admin.php';
require_once AI_CHATBOT_LLAMA_PLUGIN_DIR . 'includes/class-chatbot-api.php';
require_once AI_CHATBOT_LLAMA_PLUGIN_DIR . 'includes/class-chatbot-shortcode.php';

// Initialize the plugin
function ai_chatbot_llama_init()
{
    $chatbot_core = new AI_Chatbot_Llama_Core();
    $chatbot_admin = new AI_Chatbot_Llama_Admin();
    $chatbot_api = new AI_Chatbot_Llama_API();
    $chatbot_shortcode = new AI_Chatbot_Llama_Shortcode();
}
add_action('plugins_loaded', 'ai_chatbot_llama_init');

// Activation hook
register_activation_hook(__FILE__, 'ai_chatbot_llama_activate');
function ai_chatbot_llama_activate()
{
    // Create database table for chat history
    global $wpdb;
    $table_name = $wpdb->prefix . 'ai_chatbot_conversations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        session_id varchar(255) NOT NULL,
        user_id bigint(20) DEFAULT NULL,
        message text NOT NULL,
        response text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY session_id (session_id),
        KEY user_id (user_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Set default options
    add_option('ai_chatbot_llama_temperature', '0.7');
    add_option('ai_chatbot_llama_max_tokens', '500');
    add_option('ai_chatbot_llama_system_prompt', 'You are a helpful AI assistant. Provide clear, concise, and accurate responses.');
    add_option('ai_chatbot_llama_chat_position', 'bottom-right');
    add_option('ai_chatbot_llama_primary_color', '#B91C1C');
    add_option('ai_chatbot_llama_enabled', '1');

    // Google Gemini options (only provider)
    add_option('ai_chatbot_llama_gemini_api_key', 'AIzaSyDITPa_2fG8x6aUjZ8DsA_f9l3AdWA7ToQ');
    add_option('ai_chatbot_llama_gemini_model', 'gemini-1.5-flash'); // Free tier model
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'ai_chatbot_llama_deactivate');
function ai_chatbot_llama_deactivate()
{
    // Cleanup if needed
}
