<?php
require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['user_id'])) {
    $msg = trim($_POST['message']);
    $user_id = (int)$_POST['user_id'];
    if ($msg !== '') {
        $stmt = $conn->prepare("INSERT INTO chat_messages (user_id, sender_type, message) VALUES (?, 'admin', ?)");
        $stmt->bind_param("is", $user_id, $msg);
        $stmt->execute();
        header("Location: admin_messages.php?user_id=$user_id");
        exit;
    }
}

$users = $conn->query("SELECT DISTINCT user_id FROM chat_messages ORDER BY user_id ASC");
$current_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$messages = [];

if ($current_user_id) {
    $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat Panel</title>
</head>
<body>
    <h2>Users</h2>
    <ul>
        <?php while($row = $users->fetch_assoc()): ?>
            <li><a href="?user_id=<?= $row['user_id'] ?>">User <?= $row['user_id'] ?></a></li>
        <?php endwhile; ?>
    </ul>

    <?php if ($current_user_id): ?>
    <h3>Chat with User <?= $current_user_id ?></h3>
    <div style="border: 1px solid #ccc; padding: 10px; max-height: 300px; overflow-y: auto;">
        <?php foreach ($messages as $msg): ?>
            <div><strong><?= ucfirst($msg['sender_type']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?> <em>(<?= $msg['created_at'] ?>)</em></div>
        <?php endforeach; ?>
    </div>

    <form method="POST">
        <input type="hidden" name="user_id" value="<?= $current_user_id ?>">
        <input type="text" name="message" placeholder="Type reply..." required>
        <button type="submit">Send</button>
    </form>
    <?php endif; ?>
</body>
</html>
