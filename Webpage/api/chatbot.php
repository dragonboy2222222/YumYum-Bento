<?php
header("Content-Type: application/json");

// Allow CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Read incoming JSON
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";

// Simple rule-based response
$reply = "I didn’t understand that. Can you try again?";
if (stripos($userMessage, "hello") !== false) {
    $reply = "Hi there! 👋 How can I help you today?";
} elseif (stripos($userMessage, "plan") !== false) {
    $reply = "We offer 30, 60, and 90 day lunchbox subscription plans.";
} elseif (stripos($userMessage, "bye") !== false) {
    $reply = "Goodbye! 👋 Have a great day!";
}

// Send back JSON
echo json_encode(["reply" => $reply]);
