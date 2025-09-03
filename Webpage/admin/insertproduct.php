<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../dbconnect.php";

try {
    $sql = "SELECT * FROM category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (isset($_POST["insertBtn"])) {
    $name = $_POST["pname"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $qty = $_POST["qty"];
    $description = $_POST["description"];
    $fileImage = $_FILES["productImage"];
    $filePath = "productImage/" . basename($fileImage['name']);

    if (move_uploaded_file($fileImage['tmp_name'], $filePath)) {
        try {
            $sql = "INSERT INTO product VALUES (?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $flag = $stmt->execute([null, $name, $category, $price, $qty, $description, $filePath]);
            $id = $conn->lastInsertId();
            if ($flag) {
                $_SESSION['message'] = "✅ Product with ID $id inserted successfully!";
                header("Location: viewProduct.php");
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        echo "❌ File upload failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        :root {
            --purple-dark: #4a284e;
            --purple-medium: #6a3e6f;
            --purple-light: #9e6fa0;
            --cream: #f4f1e6;
            --white: #ffffff;
            --gray-dark: #333333;
            --gray-light: #eeeeee;
        }

        /* Best practice: Apply box-sizing to all elements for consistent layout behavior */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--gray-light);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background-color: var(--purple-dark);
            color: var(--white);
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
            color: var(--cream);
            text-transform: uppercase;
        }

        .sidebar a {
            display: block;
            color: var(--cream);
            text-decoration: none;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: var(--purple-medium);
            color: var(--white);
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
            width: 100%; /* Changed from calc to 100% to work better with flexbox */
            flex-grow: 1; /* Allows the main content to take up remaining space */
        }

        .main h1 {
            color: var(--purple-dark);
            margin-bottom: 25px;
            font-weight: 700;
        }

        .form-card {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            max-width: 700px;
            width: 100%; /* Ensures it fills parent on small screens */
            margin: auto; /* Centers the form card */
        }

        .form-card label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form-card input,
        .form-card select,
        .form-card textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid var(--gray-light);
        }

        .form-card button {
            background-color: var(--purple-medium);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-card button:hover {
            background-color: var(--purple-light);
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

            .main {
                margin-left: 0; /* Removes the margin */
                width: 100%; /* Main content takes full width */
                padding: 20px; /* Reduces padding for smaller screens */
            }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Lunchbox Admin</h2>
        <a href="dashboard.php">🍱 View Orders</a>
        <a href="insertProduct.php" class="active">🥪 Manage Products</a>
        <a href="viewUser.php">📋 View Users</a>
        <a href="viewProduct.php">📈 Products Reports</a>
        <a href="#">⚙️ Settings</a>

        <div class="logout">
            <a href="../login.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main">
        <h1>Insert Product</h1>
        <div class="form-card">
            <form action="insertProduct.php" method="post" enctype="multipart/form-data">
                <label>Product Name</label>
                <input type="text" name="pname" required>

                <label>Price</label>
                <input type="number" step="0.01" name="price" required>

                <label>Category</label>
                <select name="category" required>
                    <option value="">-- Choose Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['catId'] ?>"><?= htmlspecialchars($cat['catName']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Quantity</label>
                <input type="number" name="qty" required>

                <label>Description</label>
                <textarea name="description" rows="4"></textarea>

                <label>Product Image</label>
                <input type="file" name="productImage" required>

                <button type="submit" name="insertBtn">➕ Insert Product</button>
            </form>
        </div>
    </div>

</body>
</html>