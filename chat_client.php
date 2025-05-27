<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = rand(1000, 9999);
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $msg = trim($_POST['message']);
    if ($msg !== '') {
        $stmt = $conn->prepare("INSERT INTO chat_messages (user_id, sender_type, message) VALUES (?, 'user', ?)");
        $stmt->bind_param("is", $user_id, $msg);
        $stmt->execute();
        exit(json_encode(['status' => 'success']));
    }
    exit(json_encode(['status' => 'empty']));
}

if (isset($_GET['fetch']) && $_GET['fetch'] === '1') {
    $stmt = $conn->prepare("SELECT sender_type, message, created_at FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat - Joy Smart Gas</title>
    <style>
        /* styles same as before */
        /* Add basic layout and chat bubble styling */
        /* Reuse the styles from your last implementation */
    </style>
</head>
<body>
<!-- Chat UI -->
<div id="chat-bubble">💬</div>
<div id="chat-window">
    <div id="chat-header">Live Chat</div>
    <div id="chat-messages"></div>
    <div id="chat-input-area">
        <input type="text" id="chat-input" placeholder="Type a message...">
        <button id="chat-send">Send</button>
    </div>
</div>

<script>
const chatWindow = document.getElementById('chat-window');
document.getElementById('chat-bubble').onclick = () => {
    chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
};

document.getElementById('chat-send').onclick = sendMessage;
document.getElementById('chat-input').addEventListener('keypress', e => {
    if (e.key === 'Enter') sendMessage();
});

function sendMessage() {
    const msg = document.getElementById('chat-input').value.trim();
    if (msg !== '') {
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(msg)
        }).then(res => res.json()).then(() => {
            document.getElementById('chat-input').value = '';
            fetchMessages();
        });
    }
}

function fetchMessages() {
    fetch('?fetch=1')
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById('chat-messages');
            box.innerHTML = '';
            data.forEach(msg => {
                const div = document.createElement('div');
                div.className = msg.sender_type === 'admin' ? 'admin-msg' : 'user-msg';
                div.textContent = (msg.sender_type === 'admin' ? 'Admin: ' : 'You: ') + msg.message;
                box.appendChild(div);
            });
            box.scrollTop = box.scrollHeight;
        });
}

fetchMessages();
setInterval(fetchMessages, 3000);
</script>
</body>
</html>
