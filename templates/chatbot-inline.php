<!-- AI Chatbot Inline -->
<div class="ai-chatbot-inline"
    style="height: <?php echo esc_attr($atts['height']); ?>; width: <?php echo esc_attr($atts['width']); ?>;">
    <div class="ai-chatbot-inline-header">
        <div class="ai-chatbot-header-content">
            <div class="ai-chatbot-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
            </div>
            <div>
                <h3 class="ai-chatbot-title">Descartes AI</h3>
                <p class="ai-chatbot-status">
                    <span class="ai-chatbot-status-dot"></span>
                    Online
                </p>
            </div>
        </div>
    </div>

    <div class="ai-chatbot-messages ai-chatbot-inline-messages">
        <div class="ai-chatbot-message ai-chatbot-message-bot">
            <div class="ai-chatbot-message-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
            </div>
            <div class="ai-chatbot-message-content">
                <p>Hello! I'm Descartes AI. How can I help you today?</p>
            </div>
        </div>
    </div>

    <div class="ai-chatbot-input-container">
        <form class="ai-chatbot-form">
            <input type="text" class="ai-chatbot-input" placeholder="Type your message..." autocomplete="off" />
            <button type="submit" class="ai-chatbot-send" aria-label="Send message">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>