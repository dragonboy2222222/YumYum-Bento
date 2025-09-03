<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../dbconnect.php";

try {
    // Fetch products with category name
    $sql = "SELECT p.productID, p.productName, p.price, p.qty, p.description, p.imgPath, c.catName
            FROM product p 
            JOIN category c ON p.category = c.catId
            ORDER BY p.productID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>View Products</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        /* Best practice: Apply box-sizing to all elements for consistent layout behavior */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eeeeee;
            display: flex; /* Use flexbox for a modern layout */
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background-color: #4a284e;
            color: #fff;
            padding: 30px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            color: #f4f1e6;
            text-transform: uppercase;
        }
        .sidebar a {
            display: block;
            color: #f4f1e6;
            text-decoration: none;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #6a3e6f;
            color: #fff;
            transform: translateX(5px);
        }
        .logout {
            margin-top: auto;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .main {
            margin-left: 280px;
            padding: 40px;
            flex-grow: 1;
        }
        .main h1 {
            color: #4a284e;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .message {
            padding: 12px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto; /* Allows table to scroll horizontally on small screens */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #6a3e6f;
            color: #fff;
        }
        tr:hover {
            background: #f9f9f9;
        }
        img {
            max-width: 70px;
            height: auto; /* Ensure aspect ratio is maintained */
            border-radius: 8px;
        }
        .actions {
            white-space: nowrap; /* Prevents action buttons from wrapping */
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 6px;
        }
        .edit {
            background: #ffc107;
            color: #333;
        }
        .delete {
            background: #dc3545;
            color: #fff;
        }

        /* --- Responsive Styles --- */
        @media (max-width: 768px) {
            body {
                flex-direction: column; /* Stacks sidebar and main content vertically */
            }

            .sidebar {
                position: static; /* Removes fixed position */
                width: 100%; /* Allows sidebar to take full width */
                height: auto;
                padding-bottom: 15px; /* Adds space at the bottom */
            }
            .sidebar h2 {
                margin-bottom: 20px;
            }

            .main {
                margin-left: 0; /* Removes the margin */
                width: 100%; /* Main content takes full width */
                padding: 20px; /* Reduces padding for smaller screens */
            }

            table {
                font-size: 14px; /* Adjusts font size for better readability */
            }
            th, td {
                padding: 8px 10px; /* Reduces padding for a more compact table */
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Lunchbox Admin</h2>
        <a href="dashboard.php">🍱 View Orders</a>
        <a href="insertProduct.php">🥪 Manage Products</a>
        <a href="viewUser.php">📋 View Users</a>
        <a href="viewProduct.php" class="active">📦 Products Reports</a>
        <a href="#">⚙️ Settings</a>
        <div class="logout">
            <a href="../login.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main">
        <h1>All Products</h1>

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
                        <th>Category</th>
                        <th>Price ($)</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><?= $p['productID'] ?></td>
                                <td>
                                    <?php if (!empty($p['imgPath'])): ?>
                                        <img src="../admin/<?= htmlspecialchars($p['imgPath']) ?>" alt="product">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($p['productName']) ?></td>
                                <td><?= htmlspecialchars($p['catName']) ?></td>
                                <td><?= number_format($p['price'], 2) ?></td>
                                <td><?= $p['qty'] ?></td>
                                <td><?= htmlspecialchars($p['description']) ?></td>
                                <td class="actions">
                                    <a href="editProduct.php?id=<?= $p['productID'] ?>" class="edit">✏️ Edit</a>
                                    <a href="deleteProduct.php?id=<?= $p['productID'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this product?')">🗑️ Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8">No products found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>