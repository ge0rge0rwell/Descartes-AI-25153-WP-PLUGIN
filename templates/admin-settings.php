<div class="wrap ai-chatbot-admin">
    <h1>AI Chatbot Settings</h1>

    <div class="ai-chatbot-admin-container">
        <div class="ai-chatbot-admin-main">
            <form method="post" action="options.php">
                <?php settings_fields('ai_chatbot_llama_settings'); ?>

                <!-- General Settings -->
                <div class="ai-chatbot-card">
                    <h2>General Settings</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_enabled">Enable Chatbot</label>
                            </th>
                            <td>
                                <label class="ai-chatbot-toggle-switch">
                                    <input type="checkbox" id="ai_chatbot_llama_enabled" name="ai_chatbot_llama_enabled"
                                        value="1" <?php checked(get_option('ai_chatbot_llama_enabled'), '1'); ?> />
                                    <span class="ai-chatbot-toggle-slider"></span>
                                </label>
                                <p class="description">Enable or disable the chatbot on your website</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Provider Selection -->
                <div class="ai-chatbot-card">
                    <h2>AI Provider</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label>Select Provider</label>
                            </th>
                            <td>
                                <?php $provider = get_option('ai_chatbot_llama_provider', 'ollama_local'); ?>
                                <fieldset>
                                    <label style="display: block; margin-bottom: 10px;">
                                        <input type="radio" name="ai_chatbot_llama_provider" value="ollama_local" 
                                            <?php checked($provider, 'ollama_local'); ?> class="ai-chatbot-provider-radio" />
                                        <strong>Local Ollama</strong> - Run Ollama on the same server as WordPress
                                    </label>
                                    <label style="display: block; margin-bottom: 10px;">
                                        <input type="radio" name="ai_chatbot_llama_provider" value="ollama_remote" 
                                            <?php checked($provider, 'ollama_remote'); ?> class="ai-chatbot-provider-radio" />
                                        <strong>Remote Ollama</strong> - Connect to Ollama on a different server
                                    </label>
                                    <label style="display: block; margin-bottom: 10px;">
                                        <input type="radio" name="ai_chatbot_llama_provider" value="openai" 
                                            <?php checked($provider, 'openai'); ?> class="ai-chatbot-provider-radio" />
                                        <strong>OpenAI</strong> - Use GPT models (requires API key, pay-per-use)
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Ollama Local Settings -->
                <div class="ai-chatbot-card ai-chatbot-provider-settings" id="ollama-local-settings" 
                    style="display: <?php echo $provider === 'ollama_local' ? 'block' : 'none'; ?>">
                    <h2>Local Ollama Settings</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_api_endpoint">API Endpoint</label>
                            </th>
                            <td>
                                <input type="text" id="ai_chatbot_llama_api_endpoint"
                                    name="ai_chatbot_llama_api_endpoint"
                                    value="<?php echo esc_attr(get_option('ai_chatbot_llama_api_endpoint')); ?>"
                                    class="regular-text" />
                                <p class="description">Ollama API endpoint (default: http://localhost:11434/api/chat)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_model">Model</label>
                            </th>
                            <td>
                                <input type="text" id="ai_chatbot_llama_model" name="ai_chatbot_llama_model"
                                    value="<?php echo esc_attr(get_option('ai_chatbot_llama_model')); ?>"
                                    class="regular-text" />
                                <p class="description">Model name (e.g., llama2, llama3, codellama, mistral)</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Ollama Remote Settings -->
                <div class="ai-chatbot-card ai-chatbot-provider-settings" id="ollama-remote-settings" 
                    style="display: <?php echo $provider === 'ollama_remote' ? 'block' : 'none'; ?>">
                    <h2>Remote Ollama Settings</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_remote_url">Remote URL</label>
                            </th>
                            <td>
                                <input type="text" id="ai_chatbot_llama_remote_url"
                                    name="ai_chatbot_llama_remote_url"
                                    value="<?php echo esc_attr(get_option('ai_chatbot_llama_remote_url')); ?>"
                                    class="regular-text" placeholder="https://your-server.com:11434" />
                                <p class="description">Full URL to your remote Ollama server (e.g., https://ollama.example.com:11434)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_model_remote">Model</label>
                            </th>
                            <td>
                                <input type="text" id="ai_chatbot_llama_model_remote" name="ai_chatbot_llama_model"
                                    value="<?php echo esc_attr(get_option('ai_chatbot_llama_model')); ?>"
                                    class="regular-text" />
                                <p class="description">Model name available on the remote server</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- OpenAI Settings -->
                <div class="ai-chatbot-card ai-chatbot-provider-settings" id="openai-settings" 
                    style="display: <?php echo $provider === 'openai' ? 'block' : 'none'; ?>">
                    <h2>OpenAI Settings</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_openai_api_key">API Key</label>
                            </th>
                            <td>
                                <input type="password" id="ai_chatbot_llama_openai_api_key"
                                    name="ai_chatbot_llama_openai_api_key"
                                    value="<?php echo esc_attr(get_option('ai_chatbot_llama_openai_api_key')); ?>"
                                    class="regular-text" placeholder="sk-..." />
                                <p class="description">
                                    Your OpenAI API key. Get one at <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_openai_model">Model</label>
                            </th>
                            <td>
                                <select id="ai_chatbot_llama_openai_model" name="ai_chatbot_llama_openai_model">
                                    <option value="gpt-3.5-turbo" <?php selected(get_option('ai_chatbot_llama_openai_model'), 'gpt-3.5-turbo'); ?>>
                                        GPT-3.5 Turbo (Fast, affordable)
                                    </option>
                                    <option value="gpt-4" <?php selected(get_option('ai_chatbot_llama_openai_model'), 'gpt-4'); ?>>
                                        GPT-4 (Most capable, higher cost)
                                    </option>
                                    <option value="gpt-4-turbo-preview" <?php selected(get_option('ai_chatbot_llama_openai_model'), 'gpt-4-turbo-preview'); ?>>
                                        GPT-4 Turbo (Latest, balanced)
                                    </option>
                                </select>
                                <p class="description">Choose the OpenAI model to use</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Common Settings -->
                <div class="ai-chatbot-card">
                    <h2>AI Configuration</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_temperature">Temperature</label>
                            </th>
                            <td>
                                <input type="number" id="ai_chatbot_llama_temperature"
                                    name="ai_chatbot_llama_temperature"
                                    value="<?php echo esc_attr(get_option('ai_chatbot_llama_temperature')); ?>"
                                    step="0.1" min="0" max="2" class="small-text" />
                                <p class="description">Controls randomness (0.0 = focused, 2.0 = creative, default: 0.7)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_system_prompt">System Prompt</label>
                            </th>
                            <td>
                                <textarea id="ai_chatbot_llama_system_prompt" name="ai_chatbot_llama_system_prompt"
                                    rows="4"
                                    class="large-text"><?php echo esc_textarea(get_option('ai_chatbot_llama_system_prompt')); ?></textarea>
                                <p class="description">Instructions for the AI assistant's behavior and personality</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <button type="button" id="ai-chatbot-test-connection" class="button">
                                    Test Connection
                                </button>
                                <span id="ai-chatbot-connection-status"></span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Appearance Settings -->
                <div class="ai-chatbot-card">
                    <h2>Appearance</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_primary_color">Primary Color</label>
                            </th>
                            <td>
                                <input type="text" id="ai_chatbot_llama_primary_color"
                                    name="ai_chatbot_llama_primary_color"
                                    value="<?php echo esc_attr(get_option('ai_chatbot_llama_primary_color')); ?>"
                                    class="ai-chatbot-color-picker" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="ai_chatbot_llama_chat_position">Chat Position</label>
                            </th>
                            <td>
                                <select id="ai_chatbot_llama_chat_position" name="ai_chatbot_llama_chat_position">
                                    <option value="bottom-right" <?php selected(get_option('ai_chatbot_llama_chat_position'), 'bottom-right'); ?>>
                                        Bottom Right
                                    </option>
                                    <option value="bottom-left" <?php selected(get_option('ai_chatbot_llama_chat_position'), 'bottom-left'); ?>>
                                        Bottom Left
                                    </option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <?php submit_button(); ?>
            </form>
        </div>

        <div class="ai-chatbot-admin-sidebar">
            <div class="ai-chatbot-card">
                <h3>💡 Quick Start</h3>
                <p><strong>Choose your provider:</strong></p>
                <ul style="margin-left: 20px;">
                    <li><strong>Local Ollama:</strong> Best for VPS/dedicated servers</li>
                    <li><strong>Remote Ollama:</strong> Connect to external Ollama server</li>
                    <li><strong>OpenAI:</strong> Easiest for shared hosting (costs apply)</li>
                </ul>
            </div>

            <div class="ai-chatbot-card">
                <h3>📝 Shortcode</h3>
                <p>Embed the chatbot inline:</p>
                <code>[ai_chatbot]</code>
            </div>

            <div class="ai-chatbot-card">
                <h3>📚 Documentation</h3>
                <ul>
                    <li><a href="https://ollama.ai/" target="_blank">Ollama Docs</a></li>
                    <li><a href="https://platform.openai.com/docs" target="_blank">OpenAI Docs</a></li>
                </ul>
            </div>

            <div class="ai-chatbot-card">
                <h3>💰 Cost Comparison</h3>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td><strong>Local Ollama:</strong></td>
                        <td>Free (server costs)</td>
                    </tr>
                    <tr>
                        <td><strong>Remote Ollama:</strong></td>
                        <td>Free (hosting costs)</td>
                    </tr>
                    <tr>
                        <td><strong>OpenAI GPT-3.5:</strong></td>
                        <td>~$0.002/1K tokens</td>
                    </tr>
                    <tr>
                        <td><strong>OpenAI GPT-4:</strong></td>
                        <td>~$0.03/1K tokens</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Show/hide provider settings based on selection
    $('.ai-chatbot-provider-radio').on('change', function() {
        const provider = $(this).val();
        $('.ai-chatbot-provider-settings').hide();
        
        if (provider === 'ollama_local') {
            $('#ollama-local-settings').show();
        } else if (provider === 'ollama_remote') {
            $('#ollama-remote-settings').show();
        } else if (provider === 'openai') {
            $('#openai-settings').show();
        }
    });
});
</script>