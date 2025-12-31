# AI Chatbot with Llama - WordPress Plugin

A powerful WordPress plugin that integrates Llama AI models to provide intelligent chatbot functionality on your website.

## Features

- 🤖 **Multiple AI Providers**: Works with Ollama (local/remote) or OpenAI
- 🌐 **Hosting Compatible**: Works on shared hosting, VPS, or dedicated servers
- 💬 **Modern Chat Interface**: Beautiful, responsive chatbot widget
- 📝 **Markdown Support**: Rich text formatting with syntax highlighting
- 🎨 **Customizable Design**: Adjust colors and positioning to match your brand
- 📊 **Conversation History**: Track and review all chatbot conversations
- 🔧 **Easy Configuration**: Simple admin panel for all settings
- 📱 **Responsive**: Works perfectly on desktop and mobile devices
- 🎯 **Shortcode Support**: Embed chatbot anywhere with `[ai_chatbot]`
- 🔒 **Secure**: Built with WordPress security best practices
- ⏰ **Message Timestamps**: Track when messages were sent
- 📋 **Copy to Clipboard**: Easy copying of AI responses

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Ollama installed and running (locally or remotely)
- A Llama model pulled in Ollama

## Installation

1. **Install Ollama** (if not already installed):
   ```bash
   # On macOS/Linux
   curl https://ollama.ai/install.sh | sh
   
   # Pull a Llama model
   ollama pull llama2
   ```

2. **Upload the plugin**:
   - Upload the `ai-chatbot-llama` folder to `/wp-content/plugins/`
   - Or install via WordPress admin panel

3. **Activate the plugin**:
   - Go to WordPress Admin → Plugins
   - Activate "AI Chatbot with Llama"

4. **Configure the plugin**:
   - Go to WordPress Admin → AI Chatbot → Settings
   - Set your Ollama API endpoint (default: `http://localhost:11434/api/chat`)
   - Choose your Llama model
   - Click "Test Connection" to verify
   - Enable the chatbot

## Configuration

### Choose Your AI Provider

The plugin supports three deployment options:

#### 1. **Local Ollama** (VPS/Dedicated Servers)
- ✅ Free, unlimited usage
- ✅ Full privacy and control
- ❌ Requires server setup
- **Best for:** Users with root access

#### 2. **Remote Ollama** (Any Hosting)
- ✅ Works on shared hosting
- ✅ Free, unlimited usage
- ❌ Requires separate Ollama server
- **Best for:** Shared hosting users who can run Ollama elsewhere

#### 3. **OpenAI** (Easiest Setup)
- ✅ Works on ANY hosting
- ✅ Zero setup required
- ❌ Pay-per-use ($0.002/1K tokens for GPT-3.5)
- **Best for:** Quick setup, no technical knowledge needed

See [HOSTING.md](HOSTING.md) for detailed setup instructions.

### Basic Configuration

1. Go to **WordPress Admin → AI Chatbot → Settings**

2. **Select Provider:**
   - Choose between Local Ollama, Remote Ollama, or OpenAI

3. **Configure Provider:**
   - **Local Ollama:** Set API endpoint and model name
   - **Remote Ollama:** Enter remote server URL and model
   - **OpenAI:** Enter API key and select model

4. **Test Connection:**
   - Click "Test Connection" to verify setup

5. **Customize:**
   - Set system prompt for AI personality
   - Adjust temperature (creativity level)
   - Choose colors and position

6. **Enable:**
   - Toggle "Enable Chatbot" to activate

## Configuration

### API Settings

- **API Endpoint**: URL to your Ollama instance (e.g., `http://localhost:11434/api/chat`)
- **Model**: Name of the Llama model to use (e.g., `llama2`, `llama3`, `codellama`)
- **Temperature**: Controls response randomness (0.0 - 2.0, default: 0.7)
- **System Prompt**: Instructions for the AI's behavior and personality

### Appearance

- **Primary Color**: Main color for the chatbot interface
- **Chat Position**: Choose between bottom-right or bottom-left

## Usage

### Widget Mode

The chatbot automatically appears as a floating widget on all pages when enabled.

### Shortcode Mode

Embed the chatbot inline on any page or post:

```
[ai_chatbot]
```

With custom dimensions:

```
[ai_chatbot height="600px" width="100%"]
```

## Available Llama Models

You can use any model available in Ollama:

- `llama2` - Meta's Llama 2 (7B, 13B, 70B)
- `llama3` - Meta's Llama 3 (latest)
- `codellama` - Specialized for code
- `mistral` - Mistral AI's model
- `mixtral` - Mixture of experts model
- And many more...

Pull models with:
```bash
ollama pull <model-name>
```

## Customization

### Modify System Prompt

Customize the AI's personality and behavior by editing the System Prompt in settings. Examples:

**Customer Support**:
```
You are a helpful customer support assistant for [Company Name]. 
Provide friendly, professional assistance and always ask if there's anything else you can help with.
```

**Technical Support**:
```
You are a technical support specialist. Provide clear, step-by-step solutions to technical problems. 
Ask clarifying questions when needed.
```

**Sales Assistant**:
```
You are a knowledgeable sales assistant. Help customers find the right products, 
answer questions about features, and guide them through the purchase process.
```

## Troubleshooting

### Connection Failed

1. Ensure Ollama is running:
   ```bash
   ollama serve
   ```

2. Check if the model is available:
   ```bash
   ollama list
   ```

3. Verify the API endpoint in plugin settings

### Slow Responses

- Use a smaller model (e.g., `llama2:7b` instead of `llama2:70b`)
- Reduce the temperature setting
- Ensure your server has adequate resources

### Chatbot Not Appearing

1. Check if the plugin is enabled in settings
2. Clear your browser cache
3. Check for JavaScript errors in browser console

## Security

- All AJAX requests are protected with WordPress nonces
- User inputs are sanitized and escaped
- Database queries use prepared statements
- Follows WordPress coding standards

## Support

For issues, questions, or feature requests, please visit:
- Plugin support forum
- GitHub repository (if applicable)

## License

GPL v2 or later

## Credits

- Built with WordPress best practices
- Powered by Ollama and Llama models
- Icons from Feather Icons

## Changelog

### 1.0.0
- Initial release
- Llama integration via Ollama
- Modern chat interface
- Admin panel with settings
- Conversation history tracking
- Shortcode support
- Responsive design
