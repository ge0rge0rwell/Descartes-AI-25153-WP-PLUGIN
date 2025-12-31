// Admin Panel JavaScript
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Create worker for PDF.js
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = aiChatbotAdmin.pdfWorkerSrc;
        }

        // Initialize color picker
        if ($('.ai-chatbot-color-picker').length) {
            $('.ai-chatbot-color-picker').wpColorPicker();
        }

        // Tabs Logic
        $('.nav-tab-wrapper a').on('click', function (e) {
            e.preventDefault();

            // Remove active class from all tabs
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $('.ai-chatbot-tab-content').removeClass('active');

            // Add active class to clicked tab
            $(this).addClass('nav-tab-active');

            // Show corresponding content
            const target = $(this).attr('href').substring(1); // remove #
            $('#tab-' + target).addClass('active');
        });

        // PDF Processing Logic
        $('#ai_chatbot_process_pdf').on('click', async function () {
            const fileInput = $('#ai_chatbot_pdf_upload')[0];
            const statusSpan = $('#ai_chatbot_pdf_status');
            const contextTextarea = $('#ai_chatbot_llama_context');

            if (fileInput.files.length === 0) {
                alert('Please select a PDF file first.');
                return;
            }

            const file = fileInput.files[0];
            if (file.type !== 'application/pdf') {
                alert('Please select a valid PDF file.');
                return;
            }

            // Start processing
            statusSpan.text('Processing...').css('color', '#6366f1');
            $(this).prop('disabled', true);

            try {
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                let fullText = '';

                // Iterate through each page
                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);
                    const textContent = await page.getTextContent();
                    const pageText = textContent.items.map(item => item.str).join(' ');
                    fullText += `[Page ${i}]\n${pageText}\n\n`;

                    // Update status
                    statusSpan.text(`Reading page ${i} of ${pdf.numPages}...`);
                }

                // Append to textarea
                const currentContent = contextTextarea.val();
                const separator = currentContent ? '\n\n--- PDF Content ---\n\n' : '';
                contextTextarea.val(currentContent + separator + fullText);

                statusSpan.text('Done! Text added to Context.').css('color', '#10b981');

            } catch (error) {
                console.error('PDF Error:', error);
                statusSpan.text('Error reading PDF.').css('color', '#ef4444');
                alert('Error processing PDF: ' + error.message);
            } finally {
                $(this).prop('disabled', false);
            }
        });

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
