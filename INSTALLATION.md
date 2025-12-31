# WordPress Llama Chatbot Plugin - Installation & Setup Guide

## Quick Start

This guide will help you install and configure the AI Chatbot with Llama plugin on your WordPress site.

## Prerequisites

Before installing the plugin, ensure you have:

1. **WordPress** 5.0 or higher
2. **PHP** 7.4 or higher
3. **Ollama** installed and running
4. A **Llama model** pulled in Ollama

## Step 1: Install Ollama

### On macOS/Linux:

```bash
curl https://ollama.ai/install.sh | sh
```

### On Windows:

Download from [ollama.ai](https://ollama.ai/download)

### Verify Installation:

```bash
ollama --version
```

## Step 2: Pull a Llama Model

Choose and pull a model (examples):

```bash
# Recommended for most users (7B parameters)
ollama pull llama2

# For better quality (13B parameters, requires more RAM)
ollama pull llama2:13b

# For coding assistance
ollama pull codellama

# Latest Llama 3
ollama pull llama3
```

### Start Ollama Server:

```bash
ollama serve
```

The server will run on `http://localhost:11434` by default.

## Step 3: Install WordPress Plugin

### Method 1: Upload via WordPress Admin

1. Download the plugin folder
2. Zip the `ai-chatbot-llama` folder
3. Go to WordPress Admin → Plugins → Add New
4. Click "Upload Plugin"
5. Choose the zip file and click "Install Now"
6. Click "Activate Plugin"

### Method 2: Manual Installation

1. Upload the `ai-chatbot-llama` folder to `/wp-content/plugins/`
2. Go to WordPress Admin → Plugins
3. Find "AI Chatbot with Llama" and click "Activate"

## Step 4: Configure the Plugin

1. Go to **WordPress Admin → AI Chatbot → Settings**

2. **API Configuration:**
   - **API Endpoint**: `http://localhost:11434/api/chat` (default)
   - **Model**: Enter the model name you pulled (e.g., `llama2`)
   - **Temperature**: `0.7` (default, range 0.0-2.0)
   - **System Prompt**: Customize the AI's personality

3. **Test Connection:**
   - Click the "Test Connection" button
   - You should see "Connected successfully" with available models

4. **Appearance Settings:**
   - **Primary Color**: Choose your brand color
   - **Chat Position**: Bottom-right or bottom-left
   - **Enable Chatbot**: Toggle to show/hide the widget

5. Click **"Save Changes"**

## Step 5: Verify Installation

1. Visit any page on your website
2. Look for the chatbot icon in the bottom corner
3. Click to open the chat
4. Type a message and verify you get a response

## Usage

### Widget Mode (Default)

The chatbot automatically appears as a floating widget on all pages when enabled.

### Shortcode Mode

Embed the chatbot inline on specific pages:

```
[ai_chatbot]
```

With custom dimensions:

```
[ai_chatbot height="600px" width="100%"]
```

## Customization

### System Prompt Examples

**Customer Support:**
```
You are a helpful customer support assistant for [Company Name]. 
Provide friendly, professional assistance and always ask if there's 
anything else you can help with.
```

**Technical Support:**
```
You are a technical support specialist. Provide clear, step-by-step 
solutions to technical problems. Ask clarifying questions when needed.
```

**Sales Assistant:**
```
You are a knowledgeable sales assistant. Help customers find the right 
products, answer questions about features, and guide them through the 
purchase process.
```

## Troubleshooting

### Chatbot Not Appearing

1. Check if the plugin is enabled in settings
2. Clear browser cache
3. Check browser console for JavaScript errors
4. Verify no theme conflicts

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

1. Use a smaller model (e.g., `llama2:7b` instead of `llama2:70b`)
2. Reduce temperature setting
3. Ensure adequate server resources (RAM, CPU)

### Markdown Not Rendering

1. Clear browser cache
2. Check browser console for JavaScript errors
3. Verify marked.js and highlight.js are loading

## Remote Ollama Setup

To use Ollama on a different server:

1. Start Ollama with host binding:
   ```bash
   OLLAMA_HOST=0.0.0.0:11434 ollama serve
   ```

2. Update plugin settings:
   - **API Endpoint**: `http://your-server-ip:11434/api/chat`

3. Ensure firewall allows connections on port 11434

## Security Considerations

- Keep WordPress and the plugin updated
- Use strong passwords for WordPress admin
- Consider using HTTPS for production sites
- Limit Ollama access to trusted networks
- Review conversation logs regularly

## Performance Tips

1. **Choose the Right Model:**
   - 7B models: Fast, good for most use cases
   - 13B models: Better quality, slower
   - 70B models: Best quality, requires significant resources

2. **Optimize Settings:**
   - Lower temperature for more consistent responses
   - Limit conversation history (default: 5 messages)

3. **Server Resources:**
   - Minimum 8GB RAM for 7B models
   - 16GB+ RAM for 13B models
   - SSD storage recommended

## Support

For issues or questions:
- Check the [README.md](file:///Users/0rwell/.gemini/antigravity/scratch/ai-chatbot-llama/README.md)
- Review conversation logs in WordPress Admin → AI Chatbot → Conversations
- Check Ollama logs for API errors

## Next Steps

- Customize the system prompt for your use case
- Adjust colors to match your brand
- Test with different Llama models
- Review conversation history in the admin panel
- Consider implementing rate limiting for production use
