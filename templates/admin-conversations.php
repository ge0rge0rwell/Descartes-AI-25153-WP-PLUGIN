<div class="wrap ai-chatbot-admin">
    <h1>Conversation History</h1>

    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'ai_chatbot_conversations';

    // Pagination
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    // Get total count
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_pages = ceil($total_items / $per_page);

    // Get conversations
    $conversations = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY created_at DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        )
    );
    ?>

    <div class="ai-chatbot-card">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 10%;">Date</th>
                    <th style="width: 10%;">Session</th>
                    <th style="width: 35%;">User Message</th>
                    <th style="width: 35%;">AI Response</th>
                    <th style="width: 10%;">User</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($conversations)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            No conversations yet
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($conversations as $conversation): ?>
                        <tr>
                            <td>
                                <?php echo esc_html(date('Y-m-d H:i', strtotime($conversation->created_at))); ?>
                            </td>
                            <td>
                                <?php echo esc_html(substr($conversation->session_id, 0, 8)); ?>...
                            </td>
                            <td>
                                <?php echo esc_html($conversation->message); ?>
                            </td>
                            <td>
                                <?php echo esc_html(substr($conversation->response, 0, 150)) . (strlen($conversation->response) > 150 ? '...' : ''); ?>
                            </td>
                            <td>
                                <?php
                                if ($conversation->user_id) {
                                    $user = get_userdata($conversation->user_id);
                                    echo esc_html($user ? $user->display_name : 'Unknown');
                                } else {
                                    echo 'Guest';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $current_page
                    ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>