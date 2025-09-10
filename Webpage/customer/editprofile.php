<?php
session_start();
require_once("../dbconnect.php");

// Check login
if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit;
}

// Get current user
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION["username"]]);
$user = $stmt->fetch();
$user_id = $user["id"];

// Fetch profile
$stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

if (!$profile) {
    header("Location: profile.php"); // redirect to create if not exists
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    // Upload profile image
    $profile_image = $profile["profile_image"]; // default to existing
    if (!empty($_FILES["profile_image"]["name"])) {
        $targetDir = "../uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            $profile_image = "uploads/" . $fileName;
        }
    }

    // Update profile
    $sql = "UPDATE profiles SET full_name=?, phone=?, address=?, profile_image=?, updated_at=NOW() WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$full_name, $phone, $address, $profile_image, $user_id]);

    header("Location: home.php"); // go back to home after edit
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center text-primary">Edit Profile</h2>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($profile["full_name"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($profile["phone"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($profile["address"]); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <input type="file" name="profile_image" class="form-control">
                <?php if ($profile["profile_image"]): ?>
                    <img src="../<?= htmlspecialchars($profile["profile_image"]); ?>" alt="Profile" class="mt-2" width="120" height="120">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>

</body>
</html>
