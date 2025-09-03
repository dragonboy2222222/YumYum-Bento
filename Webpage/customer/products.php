<?php
session_start();
require_once "../dbconnect.php";

try {
    // Fetch all products with their category
    $sql = "SELECT p.productID, p.productName, p.price, p.qty, p.description, p.imgPath, c.catName 
            FROM product p 
            JOIN category c ON p.category = c.catId";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home - Products</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f8f9fa;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4a284e;
        }
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .product-card h3 {
            margin: 10px 0;
            color: #6a3e6f;
        }
        .product-card p {
            font-size: 14px;
            color: #555;
        }
        .product-card .price {
            font-weight: bold;
            color: #4a284e;
            margin: 8px 0;
        }
        .product-card button {
            background: #6a3e6f;
            border: none;
            padding: 10px 15px;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }
        .product-card button:hover {
            background: #9e6fa0;
        }
    </style>
</head>
<body>

    <h1>Available Products</h1>
    <div class="products-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="../admin/<?= htmlspecialchars($product['imgPath']) ?>" alt="<?= htmlspecialchars($product['productName']) ?>">
                    <h3><?= htmlspecialchars($product['productName']) ?></h3>
                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($product['catName']) ?></p>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($product['qty']) ?></p>
                    <button>Add to Cart</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">No products available yet.</p>
        <?php endif; ?>
    </div>

</body>
</html>
