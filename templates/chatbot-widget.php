<!-- AI Chatbot Widget -->
<div id="ai-chatbot-widget" class="ai-chatbot-widget">
    <button id="ai-chatbot-toggle" class="ai-chatbot-toggle" aria-label="Toggle chatbot">
        <img src="<?php echo AI_CHATBOT_LLAMA_PLUGIN_URL . 'assets/images/chat-icon.jpg'; ?>"
            class="ai-chatbot-icon ai-chatbot-custom-icon" alt="Chat" />
        <svg class="ai-chatbot-close-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <div id="ai-chatbot-container" class="ai-chatbot-container">
        <div class="ai-chatbot-header">
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
            <div class="ai-chatbot-header-actions">
                <button id="ai-chatbot-clear" class="ai-chatbot-header-btn" aria-label="Clear chat" title="Clear chat">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
                <button id="ai-chatbot-minimize" class="ai-chatbot-header-btn" aria-label="Minimize">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div id="ai-chatbot-messages" class="ai-chatbot-messages">
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
            <form id="ai-chatbot-form">
                <input type="text" id="ai-chatbot-input" class="ai-chatbot-input" placeholder="Type your message..."
                    autocomplete="off" />
                <button type="submit" id="ai-chatbot-send" class="ai-chatbot-send" aria-label="Send message">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>