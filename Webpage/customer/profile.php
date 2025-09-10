<?php
session_start();
require_once("../dbconnect.php");

// Check login
if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit;
}

// Get current user from DB
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION["username"]]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$user_id = $user["id"];

// Handle profile form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    // Upload profile image
    $profile_image = null;
    if (!empty($_FILES["profile_image"]["name"])) {
        $targetDir = "../uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            $profile_image = "uploads/" . $fileName;
        }
    }

    // Check if profile exists
    $check = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
    $check->execute([$user_id]);
    $existing = $check->fetch();

    if ($existing) {
        // Update profile
        $sql = "UPDATE profiles SET full_name=?, phone=?, address=?, 
                profile_image=IFNULL(?, profile_image), updated_at=NOW() WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $phone, $address, $profile_image, $user_id]);
    } else {
        // Create new profile
        $sql = "INSERT INTO profiles (user_id, full_name, phone, address, profile_image) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $full_name, $phone, $address, $profile_image]);
    }

    // ✅ After creation, redirect to home.php
    header("Location: home.php");
    exit;
}

// Fetch user profile
$stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center text-primary">My Profile</h2>

        <?php if (!$profile): ?>
            <!-- Create Profile Form -->
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Create Profile</button>
            </form>
        <?php else: ?>
            <div class="text-center">
                <?php if ($profile["profile_image"]): ?>
                    <img src="../<?php echo htmlspecialchars($profile["profile_image"]); ?>" 
                         alt="Profile" class="rounded-circle mb-3" width="120" height="120">
                <?php else: ?>
                    <img src="https://via.placeholder.com/120" class="rounded-circle mb-3">
                <?php endif; ?>
                <h4><?php echo htmlspecialchars($profile["full_name"]); ?></h4>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile["phone"]); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($profile["address"]); ?></p>
                <a href="editprofile.php" class="btn btn-warning">Edit Profile</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
