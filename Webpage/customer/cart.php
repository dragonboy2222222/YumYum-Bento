<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit;
}

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding item from lunchbox.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lunchbox_id = intval($_POST['lunchbox_id']);
    $plan_id     = intval($_POST['plan_id']);
    $price       = floatval($_POST['price']);
    $image       = $_POST['image'];

    // Check if item already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['lunchbox_id'] == $lunchbox_id && $item['plan_id'] == $plan_id) {
            $item['quantity'] += 1; // increase quantity
            $found = true;
            break;
        }
    }
    unset($item); // break reference

    // If not found, add new
    if (!$found) {
        $_SESSION['cart'][] = [
            'lunchbox_id' => $lunchbox_id,
            'plan_id'     => $plan_id,
            'price'       => $price,
            'image'       => $image,
            'quantity'    => 1
        ];
    }
}

// Handle quantity update (+/-)
if (isset($_GET['action'], $_GET['index'])) {
    $index = intval($_GET['index']);
    if ($_GET['action'] === 'plus') {
        $_SESSION['cart'][$index]['quantity']++;
    } elseif ($_GET['action'] === 'minus') {
        $_SESSION['cart'][$index]['quantity']--;
        if ($_SESSION['cart'][$index]['quantity'] <= 0) {
            array_splice($_SESSION['cart'], $index, 1); // remove item
        }
    }
    header("Location: cart.php");
    exit;
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
          <th>Quantity</th>
          <th>Line Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $total = 0; ?>
        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
          <?php $lineTotal = $item['price'] * $item['quantity']; ?>
          <tr>
            <td><img src="<?= htmlspecialchars($item['image']) ?>" width="80"></td>
            <td><?= $item['lunchbox_id'] ?></td>
            <td><?= $item['plan_id'] ?></td>
            <td>$<?= number_format($item['price'], 2) ?></td>
            <td>
              <a href="cart.php?action=minus&index=<?= $index ?>" class="btn btn-sm btn-outline-danger">-</a>
              <?= $item['quantity'] ?>
              <a href="cart.php?action=plus&index=<?= $index ?>" class="btn btn-sm btn-outline-success">+</a>
            </td>
            <td>$<?= number_format($lineTotal, 2) ?></td>
            <td><a href="cart.php?action=minus&index=<?= $index ?>&remove=1" class="btn btn-sm btn-danger">Remove</a></td>
          </tr>
          <?php $total += $lineTotal; ?>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h4 class="text-end">Total: <span class="text-success">$<?= number_format($total, 2) ?></span></h4>

    <div class="d-flex justify-content-between mt-4">
      <a href="cart.php?clear=1" class="btn btn-danger">Clear Cart</a>
      <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    </div>
  <?php else: ?>
    <p class="alert alert-info">Your cart is empty.</p>
  <?php endif; ?>
</div>

</body>
</html>
