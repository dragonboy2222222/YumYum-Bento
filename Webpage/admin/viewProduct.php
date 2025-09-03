<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../dbconnect.php";

try {
    // Fetch lunchboxes (new schema)
    $sql = "SELECT id, name, price, stock_quantity, description, image
            FROM lunchboxes
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $lunchboxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Handle session message (from insert, update, delete)
$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Lunchboxes</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eeeeee;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px; background-color: #4a284e; color: #fff; padding: 30px;
            display: flex; flex-direction: column; position: fixed; height: 100%;
        }
        .sidebar h2 { text-align: center; margin-bottom: 40px; font-weight: 700; color: #f4f1e6; text-transform: uppercase; }
        .sidebar a {
            display: block; color: #f4f1e6; text-decoration: none; padding: 15px 20px; margin-bottom: 10px;
            border-radius: 8px; transition: all 0.3s ease; font-weight: 600;
        }
        .sidebar a:hover, .sidebar a.active { background-color: #6a3e6f; color: #fff; transform: translateX(5px); }
        .logout { margin-top: auto; text-align: center; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); }

        .main { margin-left: 280px; padding: 40px; flex-grow: 1; }
        .main h1 { color: #4a284e; margin-bottom: 25px; font-weight: 700; }

        .message {
            padding: 12px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724;
            border-radius: 8px; margin-bottom: 20px;
        }

        .table-container { overflow-x: auto; }
        table {
            width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #6a3e6f; color: #fff; }
        tr:hover { background: #f9f9f9; }
        img { max-width: 70px; height: auto; border-radius: 8px; }
        .actions { white-space: nowrap; }
        .actions a { margin-right: 10px; text-decoration: none; font-weight: 600; padding: 6px 10px; border-radius: 6px; }
        .edit { background: #ffc107; color: #333; }
        .delete { background: #dc3545; color: #fff; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { position: static; width: 100%; height: auto; padding-bottom: 15px; }
            .sidebar h2 { margin-bottom: 20px; }
            .main { margin-left: 0; width: 100%; padding: 20px; }
            table { font-size: 14px; }
            th, td { padding: 8px 10px; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Lunchbox Admin</h2>
        <a href="dashboard.php">🍱 View Orders</a>
        <a href="insertProduct.php">🥪 Manage Lunchboxes</a>
        <a href="viewUser.php">📋 View Users</a>
        <a href="viewProduct.php" class="active">📦 Lunchbox Reports</a>
        <a href="#">⚙️ Settings</a>
        <div class="logout">
            <a href="../login.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main">
        <h1>All Lunchboxes</h1>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price ($)</th>
                        <th>Stock</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lunchboxes)): ?>
                        <?php foreach ($lunchboxes as $lb): ?>
                            <tr>
                                <td><?= (int)$lb['id'] ?></td>
                                <td>
                                    <?php if (!empty($lb['image'])): ?>
                                        <img src="../admin/<?= htmlspecialchars($lb['image']) ?>" alt="lunchbox">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($lb['name']) ?></td>
                                <td><?= number_format((float)$lb['price'], 2) ?></td>
                                <td><?= (int)$lb['stock_quantity'] ?></td>
                                <td><?= htmlspecialchars($lb['description']) ?></td>
                                <td class="actions">
                                    <!-- Update these pages to use lunchboxes as well -->
                                    <a href="editProduct.php?id=<?= (int)$lb['id'] ?>" class="edit">✏️ Edit</a>
                                    <a href="deleteProduct.php?id=<?= (int)$lb['id'] ?>" class="delete"
                                       onclick="return confirm('Are you sure you want to delete this lunchbox?')">🗑 Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No lunchboxes found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>