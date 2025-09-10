<?php
session_start();
require_once "../dbconnect.php"; // Include DB connection

header("Content-Type: application/json"); // Set response type to JSON

// Check if the user is logged in
if (empty($_SESSION["user_id"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION["user_id"];

// Fetch user data to check if spin is used
$stmt = $conn->prepare("SELECT spin_used FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

if ($user["spin_used"]) {
    echo json_encode(["error" => "Spin already used"]);
    exit;
}

// Validate the discount
$allowed = [10, 15, 20, 25]; // Allowed discount percentages
$discount_value = isset($_POST["discount_value"]) ? intval($_POST["discount_value"]) : 0;

if (!in_array($discount_value, $allowed)) {
    echo json_encode(["error" => "Invalid discount"]);
    exit;
}

// Update user data to reflect discount usage
$stmt = $conn->prepare("UPDATE users SET discount_type=?, discount_value=?, spin_used=1, discount_redeemed=0 WHERE id=?");
$ok = $stmt->execute(["percent", $discount_value, $user_id]);

if ($ok) {
    $_SESSION["spin_available"] = 0; // Disable spin modal after success
    echo json_encode(["success" => true, "discount_value" => $discount_value]);
} else {
    echo json_encode(["error" => "DB update failed"]);
}
?>
