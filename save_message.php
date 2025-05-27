<?php
// save_message.php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (message, sender_type) VALUES (?, 'user')");
        $stmt->bind_param("s", $message);
        $stmt->execute();
        $stmt->close();

        // Optional: notify admin via email
        // require 'send_email.php';
        // sendOrderEmail('admin@example.com', 'New Chat Message', $message);
    }
}
?>
<script>
function sendMessage() {
    const message = chatInput.value.trim();
    if (message !== '') {
        // Show in UI
        const messageElement = document.createElement('div');
        messageElement.textContent = "You: " + message;
        chatMessages.appendChild(messageElement);
        chatInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Send to server
        fetch('save_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(message)
        });
    }
}
</script>
