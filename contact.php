<?php
session_start();
require 'db_connection.php';

// Assign unique user ID if not set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = rand(1000, 9999);
}
$user_id = $_SESSION['user_id'];

// Handle sending message
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

// Fetch messages
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
    <title>Contact Us - Joy Smart Gas</title>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #ff6600;
            font-size: 36px;
            text-align: center;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #555;
            text-align: center;
            margin-bottom: 30px;
        }
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .contact-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: 0.3s;
            border: 1px solid #ddd;
        }
        .contact-card:hover {
            transform: scale(1.05);
        }
        .contact-card img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .contact-card h3 {
            margin: 10px 0;
            color: #333;
            font-size: 18px;
        }
        .contact-card p {
            font-size: 14px;
            color: #777;
        }
        .map-container {
            margin-top: 30px;
        }
        iframe {
            width: 100%;
            height: 300px;
            border: 0;
            border-radius: 10px;
        }
        .whatsapp-btn {
            display: inline-block;
            padding: 12px 25px;
            background: #25D366;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            margin-top: 15px;
        }
        .whatsapp-btn:hover {
            background: #1ebe5f;
        }

        /* Chat Styles */
        #chat-bubble {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        #chat-window {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            max-height: 400px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }

        #chat-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        #chat-messages {
            padding: 10px;
            flex-grow: 1;
            overflow-y: auto;
            font-size: 14px;
            display: flex;
            flex-direction: column;
        }

        #chat-input-area {
            display: flex;
            border-top: 1px solid #ccc;
        }

        #chat-input {
            flex-grow: 1;
            padding: 10px;
            border: none;
            border-bottom-left-radius: 12px;
        }

        #chat-send {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-bottom-right-radius: 12px;
        }

        .user-msg, .admin-msg {
            margin-bottom: 6px;
            padding: 8px 10px;
            border-radius: 10px;
            max-width: 80%;
        }

        .user-msg {
            background-color: #d4edff;
            align-self: flex-end;
        }

        .admin-msg {
            background-color: #f1f1f1;
            align-self: flex-start;
        }

        @media (max-width: 768px) {
            .contact-info { grid-template-columns: 1fr; }
            iframe { height: 250px; }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Contact Joy Smart Gas</h1>
    <p>Have questions? Need assistance with orders or services? Reach out to us below!</p>

    <div class="contact-info">
        <div class="contact-card">
            <img src="images/phone.jpg" alt="Phone">
            <h3>Call Us</h3>
            <p>📞 +254 796 155690</p>
        </div>
        <div class="contact-card">
            <img src="images/whatsapp.jpg" alt="WhatsApp">
            <h3>WhatsApp</h3>
            <p><a href="https://wa.me/254796155690" class="whatsapp-btn">Chat on WhatsApp</a></p>
        </div>
        <div class="contact-card">
            <img src="images/email.jpg" alt="Email">
            <h3>Email Us</h3>
            <p>📧 joysmartgas@gmail.com</p>
        </div>
        <div class="contact-card">
            <img src="images/location.jpg" alt="Location">
            <h3>Visit Us</h3>
            <p>📍 Ruiru, Kenya</p>
        </div>
    </div>

    <div class="map-container">
        <h2>Find Us Here</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18..." allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>

<!-- Chat Bubble and Window -->
<div id="chat-bubble" title="Chat with us">💬</div>
<div id="chat-window">
    <div id="chat-header">Live Chat</div>
    <div id="chat-messages"></div>
    <div id="chat-input-area">
        <input type="text" id="chat-input" placeholder="Type a message..." />
        <button id="chat-send">Send</button>
    </div>
</div>

<script>
const chatBubble = document.getElementById('chat-bubble');
const chatWindow = document.getElementById('chat-window');
const chatInput = document.getElementById('chat-input');
const chatMessages = document.getElementById('chat-messages');

chatBubble.addEventListener('click', () => {
    chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
});

document.getElementById('chat-send').addEventListener('click', sendMessage);
chatInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
});

function sendMessage() {
    const message = chatInput.value.trim();
    if (message !== '') {
        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'message=' + encodeURIComponent(message)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                chatInput.value = '';
                fetchMessages();
            }
        });
    }
}

function fetchMessages() {
    fetch('?fetch=1')
        .then(res => res.json())
        .then(messages => {
            chatMessages.innerHTML = '';
            messages.forEach(msg => {
                const msgDiv = document.createElement('div');
                msgDiv.className = msg.sender_type === 'admin' ? 'admin-msg' : 'user-msg';
                msgDiv.textContent = (msg.sender_type === 'admin' ? 'Admin: ' : 'You: ') + msg.message;
                chatMessages.appendChild(msgDiv);
            });
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
}

fetchMessages();
setInterval(fetchMessages, 5000);
</script>

</body>
</html>
