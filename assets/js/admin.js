// Admin Panel JavaScript
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Initialize color picker
        if ($('.ai-chatbot-color-picker').length) {
            $('.ai-chatbot-color-picker').wpColorPicker();
        }

        // Test connection button
        $('#ai-chatbot-test-connection').on('click', function () {
            const button = $(this);
            const status = $('#ai-chatbot-connection-status');

            button.prop('disabled', true).text('Testing...');
            status.removeClass('success error').addClass('loading').text('Connecting...');

            $.ajax({
                url: aiChatbotAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'ai_chatbot_test_connection',
                    nonce: aiChatbotAdmin.nonce
                },
                success: function (response) {
                    button.prop('disabled', false).text('Test Connection');

                    if (response.success) {
                        status.removeClass('loading error').addClass('success');
                        let message = '✓ ' + response.data.message;

                        if (response.data.models && response.data.models.length > 0) {
                            message += ' (Models: ' + response.data.models.join(', ') + ')';
                        }

                        status.text(message);
                    } else {
                        status.removeClass('loading success').addClass('error');
                        status.text('✗ ' + response.data.message);
                    }
                },
                error: function () {
                    button.prop('disabled', false).text('Test Connection');
                    status.removeClass('loading success').addClass('error');
                    status.text('✗ Connection failed');
                }
            });
        });
    });

})(jQuery);
