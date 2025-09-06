<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../dbconnect.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if ($search !== '') {
        $sql = "SELECT m.id, m.name, m.description, m.image, l.name AS lunchbox_name
                FROM menus m
                JOIN lunchboxes l ON m.lunchbox_id = l.id
                WHERE m.id LIKE :search 
                   OR l.name LIKE :search
                ORDER BY m.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
    } else {
        $sql = "SELECT m.id, m.name, m.description, m.image, l.name AS lunchbox_name
                FROM menus m
                JOIN lunchboxes l ON m.lunchbox_id = l.id
                ORDER BY m.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

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
<title>View Menus | Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --red-dark: #993333;
        --red-medium: #cc3300;
        --cream: #f8f4ec;
        --white: #ffffff;
        --gray-dark: #333333;
        --gray-light: #eeeeee;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--cream);
        display: flex;
        min-height: 100vh;
    }
    .sidebar {
        width: 280px;
        background-color: var(--red-dark);
        color: var(--white);
        padding: 30px;
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100%;
    }
    .sidebar h2 { text-align: center; margin-bottom: 40px; color: var(--cream); }
    .sidebar a {
        display: block; color: var(--white); text-decoration: none;
        padding: 15px 20px; margin-bottom: 10px; border-radius: 8px;
        transition: 0.3s; font-weight: 600;
    }
    .sidebar a:hover, .sidebar a.active { background-color: var(--red-medium); transform: translateX(5px); }
    .logout { margin-top: auto; text-align: center; }
    .main {
        margin-left: 280px; padding: 40px; width: calc(100% - 280px);
    }
    .main h1 { color: var(--red-dark); margin-bottom: 20px; }
    .message {
        padding: 12px; background-color: #d4edda; color: #155724;
        border-radius: 8px; margin-bottom: 15px; font-weight: 600;
    }
    .search-bar {
        margin-bottom: 20px;
    }
    .search-bar input[type="text"] {
        padding: 10px; width: 250px; border: 1px solid #ccc;
        border-radius: 6px; font-size: 0.95rem;
    }
    .search-bar button {
        padding: 10px 15px; background: var(--red-medium); color: #fff;
        border: none; border-radius: 6px; cursor: pointer;
        font-weight: 600; margin-left: 5px;
    }
    .search-bar button:hover { background: var(--red-dark); }
    table {
        width: 100%; border-collapse: collapse; background: var(--white);
        border-radius: 8px; overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    th, td {
        padding: 12px 15px; border-bottom: 1px solid #eee; text-align: left;
    }
    th { background-color: var(--red-medium); color: var(--white); }
    tr:hover { background-color: #f9f9f9; }
    .actions a {
        padding: 6px 10px; border-radius: 6px; text-decoration: none;
        font-weight: 600; font-size: 0.9rem; margin-right: 5px;
    }
    .edit { background: var(--red-medium); color: #fff; }
    .edit:hover { background: var(--red-dark); }
    .delete { background: #f44336; color: #fff; }
    .delete:hover { background: #d32f2f; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Lunchbox Admin</h2>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="insertProduct.php">🍱 Manage Lunchboxes</a>
    <a href="viewmenu.php" class="active">📋 View Menus</a>
    <a href="viewUser.php">👥 View Users</a>
    <a href="viewProduct.php">📦 Lunchbox Reports</a>
    <a href="#">⚙️ Settings</a>
    <div class="logout">
        <a href="../login.php">🚪 Logout</a>
    </div>
</div>

<div class="main">
    <h1>All Menus 📋</h1>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="search-bar">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Search by ID or Lunchbox Category" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
            <?php if ($search !== ''): ?>
                <a href="viewmenu.php" style="margin-left:10px; font-weight:600; color:var(--red-dark); text-decoration:none;">❌ Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Menu Name</th>
                <th>Lunchbox Category</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($menus)): ?>
                <?php foreach ($menus as $m): ?>
                    <tr>
                        <td><?= (int)$m['id'] ?></td>
                        <td><?= htmlspecialchars($m['name']) ?></td>
                        <td><?= htmlspecialchars($m['lunchbox_name']) ?></td>
                        <td><?= htmlspecialchars($m['description']) ?></td>
                        <td>
                            <?php if (!empty($m['image'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($m['image']) ?>" alt="menu" style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="editMenu.php?id=<?= (int)$m['id'] ?>" class="edit">✏️ Edit</a>
                            <a href="deleteMenu.php?id=<?= (int)$m['id'] ?>" class="delete"
                               onclick="return confirm('Are you sure you want to delete this menu?')">🗑 Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No menus found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
