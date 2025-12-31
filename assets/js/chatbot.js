// AI Chatbot Frontend JavaScript
(function ($) {
    'use strict';

    class AIChatbot {
        constructor() {
            this.widget = $('#ai-chatbot-widget');
            this.container = $('#ai-chatbot-container');
            this.toggle = $('#ai-chatbot-toggle');
            this.minimize = $('#ai-chatbot-minimize');
            this.form = $('#ai-chatbot-form');
            this.input = $('#ai-chatbot-input');
            this.messages = $('#ai-chatbot-messages');
            this.isOpen = false;
            this.isProcessing = false;

            this.init();
        }

        init() {
            // Set position
            this.widget.attr('data-position', aiChatbotLlama.position);

            // Set primary color
            document.documentElement.style.setProperty('--chatbot-primary', aiChatbotLlama.primaryColor);

            // Event listeners
            this.toggle.on('click', () => this.toggleChat());
            this.minimize.on('click', () => this.toggleChat());
            this.form.on('submit', (e) => this.handleSubmit(e));

            // Clear chat button
            $('#ai-chatbot-clear').on('click', () => this.clearChat());

            // Auto-resize input
            this.input.on('input', () => this.autoResizeInput());
        }

        clearChat() {
            if (confirm('Are you sure you want to clear the chat history?')) {
                // Remove all messages except the welcome message
                this.messages.find('.ai-chatbot-message').slice(1).remove();
            }
        }

        toggleChat() {
            this.isOpen = !this.isOpen;
            this.widget.toggleClass('open', this.isOpen);

            if (this.isOpen) {
                this.input.focus();
            }
        }

        autoResizeInput() {
            // Future enhancement for multi-line input
        }

        async handleSubmit(e) {
            e.preventDefault();

            if (this.isProcessing) return;

            const message = this.input.val().trim();
            if (!message) return;

            // Add user message
            this.addMessage(message, 'user');
            this.input.val('');

            // Show typing indicator
            this.showTyping();
            this.isProcessing = true;

            try {
                const response = await this.sendMessage(message);
                this.hideTyping();
                this.addMessage(response, 'bot');
            } catch (error) {
                this.hideTyping();
                this.addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                console.error('Chatbot error:', error);
            } finally {
                this.isProcessing = false;
            }
        }

        addMessage(text, type) {
            const isUser = type === 'user';
            const avatar = this.getAvatarSVG(isUser);
            const timestamp = this.formatTimestamp(new Date());

            // Parse markdown for bot messages, escape HTML for user messages
            let content;
            if (isUser) {
                content = `<p>${this.escapeHtml(text)}</p>`;
            } else {
                content = this.parseMarkdown(text);
            }

            // Add copy button for bot messages
            const copyButton = !isUser ? `
                <button class="ai-chatbot-copy-btn" aria-label="Copy message" title="Copy to clipboard">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                </button>
            ` : '';

            const messageHtml = `
                <div class="ai-chatbot-message ai-chatbot-message-${type}">
                    <div class="ai-chatbot-message-avatar">
                        ${avatar}
                    </div>
                    <div class="ai-chatbot-message-content">
                        ${content}
                        <div class="ai-chatbot-message-footer">
                            <span class="ai-chatbot-timestamp">${timestamp}</span>
                            ${copyButton}
                        </div>
                    </div>
                </div>
            `;

            this.messages.append(messageHtml);

            // Apply syntax highlighting to code blocks
            if (!isUser) {
                this.messages.find('pre code').each(function () {
                    if (typeof hljs !== 'undefined') {
                        hljs.highlightElement(this);
                    }
                });

                // Add copy button event listener
                this.messages.find('.ai-chatbot-copy-btn').last().on('click', (e) => {
                    this.copyMessage(e.currentTarget);
                });
            }

            this.scrollToBottom();
        }

        showTyping() {
            const avatar = this.getAvatarSVG(false);
            const typingHtml = `
                <div class="ai-chatbot-message ai-chatbot-message-bot ai-chatbot-typing-message">
                    <div class="ai-chatbot-message-avatar">
                        ${avatar}
                    </div>
                    <div class="ai-chatbot-message-content">
                        <div class="ai-chatbot-typing">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            `;

            this.messages.append(typingHtml);
            this.scrollToBottom();
        }

        hideTyping() {
            this.messages.find('.ai-chatbot-typing-message').remove();
        }

        async sendMessage(message) {
            const response = await $.ajax({
                url: aiChatbotLlama.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'ai_chatbot_send_message',
                    nonce: aiChatbotLlama.nonce,
                    message: message
                }
            });

            if (response.success) {
                return response.data.response;
            } else {
                throw new Error(response.data.message || 'Unknown error');
            }
        }

        scrollToBottom() {
            this.messages.scrollTop(this.messages[0].scrollHeight);
        }

        getAvatarSVG(isUser) {
            if (isUser) {
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                `;
            } else {
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                `;
            }
        }
        parseMarkdown(text) {
            if (typeof marked === 'undefined') {
                return `<p>${this.escapeHtml(text)}</p>`;
            }

            // Configure marked options
            marked.setOptions({
                breaks: true,
                gfm: true,
                headerIds: false,
                mangle: false
            });

            // Parse markdown
            const html = marked.parse(text);

            // Basic sanitization - remove script tags and event handlers
            const sanitized = html
                .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
                .replace(/on\w+\s*=\s*["'][^"']*["']/gi, '')
                .replace(/javascript:/gi, '');

            return sanitized;
        }

        formatTimestamp(date) {
            const hours = date.getHours();
            const minutes = date.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
            return `${displayHours}:${displayMinutes} ${ampm}`;
        }

        copyMessage(button) {
            const messageContent = $(button).closest('.ai-chatbot-message-content');
            const textToCopy = messageContent.find('p, pre, ul, ol, blockquote').map(function () {
                return $(this).text();
            }).get().join('\n\n');

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalHTML = $(button).html();
                $(button).html(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                `);
                $(button).addClass('copied');

                setTimeout(() => {
                    $(button).html(originalHTML);
                    $(button).removeClass('copied');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    // Initialize on document ready
    $(document).ready(function () {
        if ($('#ai-chatbot-widget').length) {
            new AIChatbot();
        }
    });

})(jQuery);
