<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../dbconnect.php";

$id = $_GET['id'] ?? 0;

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $imgPath = "";
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "productImage/";
        $imgPath = $targetDir . uniqid() . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $imgPath);

        // delete old image
        $stmt = $conn->prepare("SELECT imgPath FROM product WHERE productID = ?");
        $stmt->execute([$id]);
        $old = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($old && file_exists($old['imgPath'])) unlink($old['imgPath']);

        $updateImg = ", imgPath = :imgPath";
    } else {
        $updateImg = "";
    }

    try {
        $sql = "UPDATE product SET productName = :productName, price = :price, qty = :qty,
                description = :description, category = :category $updateImg
                WHERE productID = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":productName", $productName);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":qty", $qty);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":category", $category);
        if ($updateImg) $stmt->bindParam(":imgPath", $imgPath);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $_SESSION['message'] = "✅ Product updated successfully!";
        header("Location: viewProduct.php");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch product info
$stmt = $conn->prepare("SELECT * FROM product WHERE productID = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch categories
$stmt = $conn->prepare("SELECT * FROM category");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <style>
        body { font-family: Poppins, sans-serif; background:#f5f5f5; padding:40px; }
        form { max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
        input, textarea, select { width:100%; padding:10px; margin:10px 0; border:1px solid #ddd; border-radius:6px; }
        button { padding:10px 20px; background:#6a3e6f; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer; }
        button:hover { background:#4a284e; }
    </style>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="productName" value="<?= htmlspecialchars($product['productName']) ?>" required>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
        <input type="number" name="qty" value="<?= $product['qty'] ?>" required>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <select name="category" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['catId'] ?>" <?= $cat['catId']==$product['category']?"selected":"" ?>>
                    <?= htmlspecialchars($cat['catName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <p>Current Image:</p>
        <?php if ($product['imgPath']): ?>
            <img src="<?= $product['imgPath'] ?>" width="120"><br>
        <?php endif; ?>
        <input type="file" name="image">

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
