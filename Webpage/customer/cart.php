<?php
session_start();
require_once "../dbconnect.php";

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === "add" && isset($_GET['id'])) {
        $id = intval($_GET['id']);

        // Fetch product from DB
        $stmt = $conn->prepare("SELECT * FROM lunchboxes WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // If product already in cart, increase quantity
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity']++;
            } else {
                $_SESSION['cart'][$id] = [
                    "id" => $product['id'],
                    "name" => $product['name'],
                    "price" => $product['price'],
                    "quantity" => 1,
                    "image" => $product['image']
                ];
            }
        }
    }

    // Remove item
    if ($action === "remove" && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        unset($_SESSION['cart'][$id]);
    }

    // Clear cart
    if ($action === "clear") {
        $_SESSION['cart'] = [];
    }
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar { background-color: #993333 !important; }
    .navbar .nav-link, .navbar .navbar-brand { color: #fff !important; }
    .navbar .nav-link:hover { color: #ffd9d9 !important; }
    .btn-primary { background-color: #993333; border: none; }
    .btn-primary:hover { background-color: #7a2727; }
    footer { background-color: #993333 !important; color: #fff !important; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">Lunchbox Co.</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="bentoplans.php">Plans</a></li>
        <li class="nav-item"><a class="nav-link active" href="cart.php">Cart</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- BODY -->
<main class="container my-5">
  <h1 class="mb-4">Your Cart</h1>

  <?php if ($cart): ?>
    <table class="table table-bordered text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $grandTotal = 0;
        foreach ($cart as $item): 
          $total = $item['price'] * $item['quantity'];
          $grandTotal += $total;
        ?>
        <tr>
          <td><img src="<?= htmlspecialchars($item['image']) ?>" width="100" class="img-fluid"></td>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td>$<?= number_format($item['price'], 2) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td>$<?= number_format($total, 2) ?></td>
          <td>
            <a href="cart.php?action=remove&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Remove</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="4" class="text-end fw-bold">Grand Total</td>
          <td colspan="2" class="fw-bold">$<?= number_format($grandTotal, 2) ?></td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-between">
      <a href="bentoplans.php" class="btn btn-secondary">Continue Shopping</a>
      <div>
        <a href="cart.php?action=clear" class="btn btn-warning">Clear Cart</a>
        <a href="#" class="btn btn-success">Checkout</a>
      </div>
    </div>
  <?php else: ?>
    <p class="text-center">Your cart is empty. <a href="bentoplans.php">Browse plans</a></p>
  <?php endif; ?>
</main>

<!-- FOOTER -->
<footer class="text-center p-3 mt-5">
  © 2025 Lunchbox Co. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
