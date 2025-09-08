<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding item from lunchbox.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = [
        'lunchbox_id' => intval($_POST['lunchbox_id']),
        'plan_id'     => intval($_POST['plan_id']),
        'price'       => floatval($_POST['price']),
        'image'       => $_POST['image']
    ];
    $_SESSION['cart'][] = $item;
}

// Handle "Clear Cart"
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">🛒 Your Cart</h2>

  <?php if (!empty($_SESSION['cart'])): ?>
    <table class="table table-bordered bg-white shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>Image</th>
          <th>Lunchbox ID</th>
          <th>Plan ID</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
        <?php $total = 0; ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
          <tr>
            <td><img src="<?= htmlspecialchars($item['image']) ?>" width="80"></td>
            <td><?= $item['lunchbox_id'] ?></td>
            <td><?= $item['plan_id'] ?></td>
            <td>$<?= number_format($item['price'], 2) ?></td>
          </tr>
          <?php $total += $item['price']; ?>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h4 class="text-end">Total: <span class="text-success">$<?= number_format($total, 2) ?></span></h4>

    <div class="d-flex justify-content-between mt-4">
      <a href="cart.php?clear=1" class="btn btn-danger">Clear Cart</a>
      <a href="pay.php" class="btn btn-success">Pay Now</a>
    </div>
  <?php else: ?>
    <p class="alert alert-info">Your cart is empty.</p>
  <?php endif; ?>
</div>

</body>
</html>
