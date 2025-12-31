<?php
/**
 * API handler for Llama integration
 */

class AI_Chatbot_Llama_API
{

    public function __construct()
    {
        add_action('wp_ajax_ai_chatbot_send_message', array($this, 'handle_chat_request'));
        add_action('wp_ajax_nopriv_ai_chatbot_send_message', array($this, 'handle_chat_request'));
    }

    /**
     * Handle AJAX chat request
     */
    public function handle_chat_request()
    {
        check_ajax_referer('ai_chatbot_llama_nonce', 'nonce');

        $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

        if (empty($message)) {
            wp_send_json_error(array('message' => 'Message cannot be empty'));
        }

        $session_id = AI_Chatbot_Llama_Core::get_session_id();
        $history = AI_Chatbot_Llama_Core::get_conversation_history($session_id, 5);

        // Get response from Gemini
        $response = $this->get_gemini_response($message, $history);

        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => $response->get_error_message()));
        }

        // Save conversation
        AI_Chatbot_Llama_Core::save_conversation($session_id, $message, $response);

        wp_send_json_success(array('response' => $response));
    }

    /**
     * Get response from Ollama API (local or remote)
     */
    private function get_ollama_response($message, $history = array())
    {
        $provider = get_option('ai_chatbot_llama_provider', 'ollama_local');

        // Determine API endpoint based on provider
        if ($provider === 'ollama_remote') {
            $remote_url = get_option('ai_chatbot_llama_remote_url', '');
            if (empty($remote_url)) {
                return new WP_Error('config_error', 'Remote Ollama URL is not configured');
            }
            $api_endpoint = trailingslashit($remote_url) . 'api/chat';
        } else {
            $api_endpoint = get_option('ai_chatbot_llama_api_endpoint', 'http://localhost:11434/api/chat');
        }

        $model = get_option('ai_chatbot_llama_model', 'llama2');
        $temperature = floatval(get_option('ai_chatbot_llama_temperature', '0.7'));
        $system_prompt = get_option('ai_chatbot_llama_system_prompt', 'You are a helpful AI assistant.');

        // Build messages array with history
        $messages = array();

        // Add system message
        $messages[] = array(
            'role' => 'system',
            'content' => $system_prompt
        );

        // Add conversation history
        foreach ($history as $item) {
            $messages[] = array(
                'role' => 'user',
                'content' => $item['message']
            );
            $messages[] = array(
                'role' => 'assistant',
                'content' => $item['response']
            );
        }

        // Add current message
        $messages[] = array(
            'role' => 'user',
            'content' => $message
        );

        // Prepare request body
        $body = array(
            'model' => $model,
            'messages' => $messages,
            'stream' => false,
            'options' => array(
                'temperature' => $temperature
            )
        );

        // Make API request
        $response = wp_remote_post($api_endpoint, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Bypass-Tunnel-Reminder' => 'true'
            ),
            'body' => json_encode($body),
            'timeout' => 60
        ));

        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Failed to connect to Ollama API: ' . $response->get_error_message());
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            return new WP_Error('api_error', 'Ollama API returned error code: ' . $response_code);
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['message']['content'])) {
            return new WP_Error('api_error', 'Invalid response from Ollama API');
        }

        return $data['message']['content'];
    }

    /**
     * Get response from OpenAI API
     */
    private function get_openai_response($message, $history = array())
    {
        $api_key = get_option('ai_chatbot_llama_openai_api_key', '');

        if (empty($api_key)) {
            return new WP_Error('config_error', 'OpenAI API key is not configured');
        }

        $model = get_option('ai_chatbot_llama_openai_model', 'gpt-3.5-turbo');
        $temperature = floatval(get_option('ai_chatbot_llama_temperature', '0.7'));
        $system_prompt = get_option('ai_chatbot_llama_system_prompt', 'You are a helpful AI assistant.');

        // Build messages array with history
        $messages = array();

        // Add system message
        $messages[] = array(
            'role' => 'system',
            'content' => $system_prompt
        );

        // Add conversation history
        foreach ($history as $item) {
            $messages[] = array(
                'role' => 'user',
                'content' => $item['message']
            );
            $messages[] = array(
                'role' => 'assistant',
                'content' => $item['response']
            );
        }

        // Add current message
        $messages[] = array(
            'role' => 'user',
            'content' => $message
        );

        // Prepare request body
        $body = array(
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature
        );

        // Make API request to OpenAI
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key
            ),
            'body' => json_encode($body),
            'timeout' => 60
        ));

        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Failed to connect to OpenAI API: ' . $response->get_error_message());
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($response_code !== 200) {
            $error_message = isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error';
            return new WP_Error('api_error', 'OpenAI API error: ' . $error_message);
        }

        if (!isset($data['choices'][0]['message']['content'])) {
            return new WP_Error('api_error', 'Invalid response from OpenAI API');
        }

        return $data['choices'][0]['message']['content'];
    }

    /**
     * Get response from Google Gemini API
     */
    private function get_gemini_response($message, $history = array())
    {
        $api_key = get_option('ai_chatbot_llama_gemini_api_key', '');

        if (empty($api_key)) {
            return new WP_Error('config_error', 'Google Gemini API key is not configured');
        }

        $model = get_option('ai_chatbot_llama_gemini_model', 'gemini-1.5-flash');
        $system_prompt = get_option('ai_chatbot_llama_system_prompt', 'You are a helpful AI assistant.');

        // Build conversation context
        $conversation_text = $system_prompt . "\n\n";

        // Add conversation history
        foreach ($history as $item) {
            $conversation_text .= "User: " . $item['message'] . "\n";
            $conversation_text .= "Assistant: " . $item['response'] . "\n";
        }

        // Add current message
        $conversation_text .= "User: " . $message . "\nAssistant:";

        // Prepare request body for Gemini
        $body = array(
            'contents' => array(
                array(
                    'parts' => array(
                        array('text' => $conversation_text)
                    )
                )
            )
        );

        // Make API request to Google Gemini
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $api_key;

        $response = wp_remote_post($url, array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($body),
            'timeout' => 60
        ));

        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Failed to connect to Google Gemini API: ' . $response->get_error_message());
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($response_code !== 200) {
            $error_message = isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error';
            return new WP_Error('api_error', 'Google Gemini API error: ' . $error_message);
        }

        if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return new WP_Error('api_error', 'Invalid response from Google Gemini API');
        }

        return $data['candidates'][0]['content']['parts'][0]['text'];
    }

    /**
     * Test API connection
     */
    public static function test_connection()
    {
        return self::test_gemini_connection();
    }

    /**
     * Test Ollama connection (local or remote)
     */
    private static function test_ollama_connection($is_remote = false)
    {
        if ($is_remote) {
            $remote_url = get_option('ai_chatbot_llama_remote_url', '');
            if (empty($remote_url)) {
                return array(
                    'success' => false,
                    'message' => 'Remote Ollama URL is not configured'
                );
            }
            $api_endpoint = trailingslashit($remote_url) . 'api/tags';
        } else {
            $api_endpoint = get_option('ai_chatbot_llama_api_endpoint', 'http://localhost:11434/api/chat');
            $api_endpoint = str_replace('/api/chat', '/api/tags', $api_endpoint);
        }

        $response = wp_remote_get($api_endpoint, array(
            'headers' => array(
                'Bypass-Tunnel-Reminder' => 'true'
            ),
            'timeout' => 10
        ));

        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => 'Connection failed: ' . $response->get_error_message()
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code === 200) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            $models = array();
            if (isset($body['models'])) {
                foreach ($body['models'] as $model) {
                    $models[] = $model['name'];
                }
            }
            return array(
                'success' => true,
                'message' => 'Connected successfully',
                'models' => $models
            );
        }

        return array(
            'success' => false,
            'message' => 'Connection failed with status code: ' . $response_code
        );
    }

    /**
     * Test OpenAI connection
     */
    private static function test_openai_connection()
    {
        $api_key = get_option('ai_chatbot_llama_openai_api_key', '');

        if (empty($api_key)) {
            return array(
                'success' => false,
                'message' => 'OpenAI API key is not configured'
            );
        }

        // Test with a simple models list request
        $response = wp_remote_get('https://api.openai.com/v1/models', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key
            ),
            'timeout' => 10
        ));

        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => 'Connection failed: ' . $response->get_error_message()
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($response_code === 200) {
            $models = array('gpt-3.5-turbo', 'gpt-4', 'gpt-4-turbo-preview');
            return array(
                'success' => true,
                'message' => 'Connected successfully',
                'models' => $models
            );
        }

        $error_message = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error';
        return array(
            'success' => false,
            'message' => 'Connection failed: ' . $error_message
        );
    }

    /**
     * Test Google Gemini connection
     */
    private static function test_gemini_connection()
    {
        $api_key = get_option('ai_chatbot_llama_gemini_api_key', '');

        if (empty($api_key)) {
            return array(
                'success' => false,
                'message' => 'Google Gemini API key is not configured'
            );
        }

        // Test with a simple request
        $url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $api_key;

        $response = wp_remote_get($url, array(
            'timeout' => 10
        ));

        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => 'Connection failed: ' . $response->get_error_message()
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($response_code === 200) {
            $models = array('gemini-1.5-flash (Free)', 'gemini-1.5-pro', 'gemini-pro');
            return array(
                'success' => true,
                'message' => 'Connected successfully',
                'models' => $models
            );
        }

        $error_message = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error';
        return array(
            'success' => false,
            'message' => 'Connection failed: ' . $error_message
        );
    }
}
