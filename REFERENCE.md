# AI Chatbot with Llama - Quick Reference

## Plugin Files Structure

```
ai-chatbot-llama/
├── ai-chatbot-llama.php          # Main plugin file
├── README.md                      # Plugin documentation
├── INSTALLATION.md                # Setup guide
├── includes/                      # PHP classes
│   ├── class-chatbot-core.php    # Core functionality
│   ├── class-chatbot-admin.php   # Admin panel
│   ├── class-chatbot-api.php     # Llama API integration
│   └── class-chatbot-shortcode.php # Shortcode handler
├── assets/                        # Frontend assets
│   ├── css/
│   │   ├── chatbot.css           # Widget styles
│   │   └── admin.css             # Admin styles
│   └── js/
│       ├── chatbot.js            # Widget JavaScript
│       └── admin.js              # Admin JavaScript
└── templates/                     # HTML templates
    ├── chatbot-widget.php        # Floating widget
    ├── chatbot-inline.php        # Inline shortcode
    ├── admin-settings.php        # Settings page
    └── admin-conversations.php   # Conversations page
```

## Key Features

### ✅ Implemented
- Llama AI integration via Ollama
- Floating chatbot widget
- Inline shortcode support
- Markdown rendering with syntax highlighting
- Message timestamps
- Copy to clipboard
- Clear chat functionality
- Conversation history storage
- Admin settings panel
- Connection testing
- Customizable colors and positioning

### 🔜 Future Enhancements
- Dark mode support
- Export conversations (JSON/CSV)
- Rate limiting
- Sound notifications
- File upload support
- Analytics dashboard

## Common Tasks

### Change AI Personality
Edit the System Prompt in **AI Chatbot → Settings**

### Change Colors
Update Primary Color in **AI Chatbot → Settings → Appearance**

### View Conversations
Go to **AI Chatbot → Conversations**

### Test Connection
Click "Test Connection" in **AI Chatbot → Settings**

### Embed Inline
Use shortcode: `[ai_chatbot]`

## API Endpoints

### Chat Request
- **Action**: `ai_chatbot_send_message`
- **Method**: POST (AJAX)
- **Parameters**: `message`, `nonce`
- **Response**: JSON with AI response

### Test Connection
- **Action**: `ai_chatbot_test_connection`
- **Method**: POST (AJAX)
- **Parameters**: `nonce`
- **Response**: Connection status and available models

## Database

### Table: `wp_ai_chatbot_conversations`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) | Primary key |
| session_id | varchar(255) | Unique session identifier |
| user_id | bigint(20) | WordPress user ID (if logged in) |
| message | text | User's message |
| response | text | AI's response |
| created_at | datetime | Timestamp |

## WordPress Options

| Option Name | Default Value | Description |
|-------------|---------------|-------------|
| `ai_chatbot_llama_api_endpoint` | `http://localhost:11434/api/chat` | Ollama API URL |
| `ai_chatbot_llama_model` | `llama2` | Model name |
| `ai_chatbot_llama_temperature` | `0.7` | Response randomness |
| `ai_chatbot_llama_max_tokens` | `500` | Max response length |
| `ai_chatbot_llama_system_prompt` | Default prompt | AI instructions |
| `ai_chatbot_llama_chat_position` | `bottom-right` | Widget position |
| `ai_chatbot_llama_primary_color` | `#6366f1` | Theme color |
| `ai_chatbot_llama_enabled` | `1` | Enable/disable widget |

## JavaScript API

### Initialize Chatbot
```javascript
new AIChatbot();
```

### Add Message Programmatically
```javascript
chatbot.addMessage('Hello!', 'user');
chatbot.addMessage('Hi there!', 'bot');
```

### Clear Chat
```javascript
chatbot.clearChat();
```

## CSS Custom Properties

```css
:root {
    --chatbot-primary: #6366f1;
    --chatbot-primary-hover: #4f46e5;
    --chatbot-bg: #ffffff;
    --chatbot-text: #1f2937;
    --chatbot-border: #e5e7eb;
    --chatbot-message-user-bg: #6366f1;
    --chatbot-message-bot-bg: #f3f4f6;
    --chatbot-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
```

## Hooks & Filters

### Available Actions
- `plugins_loaded` - Plugin initialization
- `wp_enqueue_scripts` - Frontend assets
- `admin_enqueue_scripts` - Admin assets
- `admin_menu` - Admin menu items
- `admin_init` - Settings registration

### Available AJAX Actions
- `wp_ajax_ai_chatbot_send_message` - Handle chat (logged in)
- `wp_ajax_nopriv_ai_chatbot_send_message` - Handle chat (guests)
- `wp_ajax_ai_chatbot_test_connection` - Test API connection

## Security Features

- WordPress nonce verification
- Input sanitization
- Output escaping
- Prepared SQL statements
- XSS prevention in markdown
- CSRF protection

## Performance Considerations

- CDN-hosted libraries (marked.js, highlight.js)
- Lazy syntax highlighting
- Efficient DOM updates
- Conversation history limit (5 messages)
- 60-second API timeout

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Minimum Requirements

- **WordPress**: 5.0+
- **PHP**: 7.4+
- **Ollama**: Latest version
- **RAM**: 8GB (for 7B models)
- **Storage**: 5GB+ (for models)

## Useful Commands

### Ollama Management
```bash
# Start server
ollama serve

# List models
ollama list

# Pull model
ollama pull llama2

# Remove model
ollama rm llama2

# Check version
ollama --version
```

### WordPress CLI (if available)
```bash
# Activate plugin
wp plugin activate ai-chatbot-llama

# Deactivate plugin
wp plugin deactivate ai-chatbot-llama

# Check plugin status
wp plugin status ai-chatbot-llama
```

## Troubleshooting Quick Fixes

| Issue | Solution |
|-------|----------|
| Chatbot not appearing | Check if enabled in settings |
| Connection failed | Verify Ollama is running |
| Slow responses | Use smaller model (7B) |
| Markdown not rendering | Clear browser cache |
| Copy button not working | Check browser console for errors |

## Version Information

- **Plugin Version**: 1.0.0
- **Marked.js**: 11.1.1
- **Highlight.js**: 11.9.0
- **WordPress Tested**: 6.4+
- **PHP Tested**: 7.4 - 8.2

## Support & Resources

- [Ollama Documentation](https://github.com/ollama/ollama)
- [Marked.js Documentation](https://marked.js.org/)
- [Highlight.js Documentation](https://highlightjs.org/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
