<?php
require_once "../dbconnect.php"; // adjust path if needed
header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = strtolower(trim($input["message"] ?? ""));

// Session ID for chat (or use logged-in user ID if available)
$sessionId = $_COOKIE["chat_session"] ?? bin2hex(random_bytes(16));
setcookie("chat_session", $sessionId, time() + 86400 * 30, "/");

// Ensure session exists
try {
    $stmt = $conn->prepare("INSERT IGNORE INTO chat_sessions (id, created_at) VALUES (?, NOW())");
    $stmt->execute([$sessionId]);
} catch (PDOException $e) {
    echo json_encode(["reply" => "DB error: " . $e->getMessage()]);
    exit;
}

// Save user message if any
if ($userMessage) {
    $stmt = $conn->prepare("INSERT INTO chat_messages (session_id, sender, message, created_at) VALUES (?, 'user', ?, NOW())");
    $stmt->execute([$sessionId, $userMessage]);
}

// Default bot reply
$botReply = "Sorry, I didn't understand. Try 'show lunchboxes', 'plans', or 'help'.";

// === Simple Rule-Based Logic === //
if (strpos($userMessage, "hello") !== false || strpos($userMessage, "hi") !== false) {
    $botReply = "Hello 👋! I can help you with lunchbox subscriptions, daily orders, and checking plans. Type 'help' for options.";
}
elseif (strpos($userMessage, "help") !== false) {
    $botReply = "Here are some things you can ask me:\n- 'show lunchboxes'\n- 'plans'\n- 'subscribe normal for 30 days'\n- 'order 2 veg today'\n- 'check my deliveries'";
}
elseif (strpos($userMessage, "lunchbox") !== false || strpos($userMessage, "show") !== false) {
    // Show lunchboxes from DB
    $stmt = $conn->query("SELECT name, price, stock_quantity FROM lunchboxes LIMIT 6");
    $boxes = $stmt->fetchAll();
    if ($boxes) {
        $lines = ["📦 Available Lunchboxes:"];
        foreach ($boxes as $b) {
            $lines[] = "- {$b['name']} ({$b['price']} USD) [Stock: {$b['stock_quantity']}]";
        }
        $botReply = implode("\n", $lines);
    } else {
        $botReply = "No lunchboxes found in stock.";
    }
}
elseif (strpos($userMessage, "plan") !== false) {
    // Show subscription plans
    $stmt = $conn->query("SELECT name, duration_days, price FROM plans");
    $plans = $stmt->fetchAll();
    if ($plans) {
        $lines = ["🗓 Subscription Plans:"];
        foreach ($plans as $p) {
            $lines[] = "- {$p['name']} ({$p['duration_days']} days): {$p['price']} USD";
        }
        $botReply = implode("\n", $lines);
    } else {
        $botReply = "No plans available.";
    }
}
elseif (strpos($userMessage, "subscribe") !== false) {
    $botReply = "✅ Subscription request received. Please log in and confirm your order on the website.";
}
elseif (strpos($userMessage, "order") !== false) {
    $botReply = "✅ Order noted. Please log in to confirm payment.";
}
elseif (strpos($userMessage, "delivery") !== false || strpos($userMessage, "status") !== false) {
    $botReply = "🚚 Deliveries are made daily between 10 AM - 1 PM. You can check your dashboard for details.";
}

// Save bot reply
$stmt = $conn->prepare("INSERT INTO chat_messages (session_id, sender, message, created_at) VALUES (?, 'bot', ?, NOW())");
$stmt->execute([$sessionId, $botReply]);

// Return reply as JSON
echo json_encode(["reply" => $botReply]);
exit;
