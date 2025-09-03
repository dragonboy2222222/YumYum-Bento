<?php
require_once "../dbconnect.php";

// Fetch all lunchboxes
$stmt = $conn->prepare("SELECT * FROM lunchboxes ORDER BY id DESC");
$stmt->execute();
$lunchboxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lunchbox Subscription</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
  /* Navbar */
  .navbar {
    background-color: #993333 !important;
  }
  .navbar .nav-link,
  .navbar .navbar-brand {
    color: #fff !important;
  }
  .navbar .nav-link:hover {
    color: #ffd9d9 !important; /* lighter red hover */
  }

  /* Subscribe button */
  .btn-subscribe,
  .btn-primary {
    background-color: #993333;
    border: none;
  }
  .btn-subscribe:hover,
  .btn-primary:hover {
    background-color: #802b2b; /* slightly darker */
  }

  /* Footer */
  footer {
    background-color: #993333 !important;
    color: #fff !important;
  }
  footer a {
    color: #730909ff !important;
    text-decoration: none;
  }
  footer a:hover {
    text-decoration: underline;
  }
</style>


<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand" href="#">
      <img src="../productImage/logo1 (1) (1).png" alt="Logo" width="300" class="d-inline-block align-text-top">
    </a>

    <!-- Toggle button for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar items -->
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Plans</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ===== BODY ===== -->
<main class="container-fluid my-5 px-4">
  <div class="text-center mb-5">
    <h1>Welcome to Lunchbox Subscription</h1>
    <p class="lead">Delicious lunchboxes delivered right to your door!</p>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php if ($lunchboxes): ?>
      <?php foreach ($lunchboxes as $box): ?>
        <div class="col">
          <div class="card h-100">
            <img src="<?= htmlspecialchars($box['image']) ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($box['name']) ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($box['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($box['description']) ?></p>
              <p class="fw-bold">$<?= number_format($box['price'], 2) ?></p>
              <!-- Subscribe button always at bottom -->
              <div class="mt-auto">
                <button class="btn btn-primary w-100">Subscribe</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">No lunchboxes available yet.</p>
    <?php endif; ?>
  </div>
</main>

<!-- ===== FOOTER ===== -->
<footer class="text-center text-lg-start mt-5" style="background-color: #c0392b; color: #fff;">
  <div class="container-fluid p-4">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-4">
        <h5 class="text-uppercase">Lunchbox Co.</h5>
        <p>Delivering healthy meals straight to your doorstep. Contact us for more info!</p>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h6 class="text-uppercase">Links</h6>
        <ul class="list-unstyled mb-0">
          <li><a href="#" class="text-white">Menu</a></li>
          <li><a href="#" class="text-white">Plans</a></li>
          <li><a href="#" class="text-white">About Us</a></li>
          <li><a href="#" class="text-white">Contact Us</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h6 class="text-uppercase">Follow Us</h6>
        <ul class="list-unstyled mb-0">
          <li><a href="#" class="text-white">Facebook</a></li>
          <li><a href="#" class="text-white">Instagram</a></li>
          <li><a href="#" class="text-white">Twitter</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="text-center p-3" style="background-color: #922b21;">
    © 2025 Lunchbox Co. All rights reserved.
  </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
