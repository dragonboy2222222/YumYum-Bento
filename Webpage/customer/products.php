<?php
require_once "../dbconnect.php";

// Pagination setup
$plansPerPage = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $plansPerPage;

// Count total plans
$totalStmt = $conn->query("SELECT COUNT(*) FROM lunchboxes");
$totalPlans = $totalStmt->fetchColumn();
$totalPages = ceil($totalPlans / $plansPerPage);

// Fetch plans for this page
$stmt = $conn->prepare("SELECT * FROM lunchboxes ORDER BY id ASC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $plansPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bento Plans</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar { background-color: #993333 !important; }
    .navbar .nav-link, .navbar .navbar-brand { color: #fff !important; }
    .navbar .nav-link:hover { color: #ffd9d9 !important; }
    .btn-primary { background-color: #993333; border: none; }
    .btn-primary:hover { background-color: #7a2727; }
    footer { background-color: #993333 !important; color: #fff !important; }
    footer a { color: #fff !important; text-decoration: none; }
    footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">
      <img src="../productImage/loogo.png" alt="Logo" width="380" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="bentoplans.php">Plans</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ===== BODY ===== -->
<main class="container my-5">
  <div class="text-center mb-5">
    <h1>Bento Plans</h1>
    <p class="lead">Choose from our carefully designed subscription plans.</p>
  </div>

  <?php if ($plans): ?>
    <?php foreach ($plans as $plan): ?>
      <div class="row mb-4">
        <div class="col-12">
          <div class="card h-100">
            <div class="row g-0">
              <div class="col-md-4">
                <img src="<?= htmlspecialchars($plan['image']) ?>" 
                     class="img-fluid rounded-start" 
                     alt="<?= htmlspecialchars($plan['name']) ?>">
              </div>
              <div class="col-md-8">
                <div class="card-body d-flex flex-column">
                  <h4 class="card-title"><?= htmlspecialchars($plan['name']) ?></h4>
                  <p class="card-text"><?= htmlspecialchars($plan['description']) ?></p>
                  <p class="fw-bold">$<?= number_format($plan['price'], 2) ?></p>
                  <div class="mt-auto">
                    <button class="btn btn-primary w-100">Subscribe</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center">No bento plans available yet.</p>
  <?php endif; ?>

  <!-- Pagination -->
  <nav aria-label="Bento plan pages">
    <ul class="pagination justify-content-center mt-4">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</main>

<!-- ===== FOOTER ===== -->
<footer class="text-center text-lg-start mt-5">
  <div class="container-fluid p-4">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-4">
        <h5 class="text-uppercase">Lunchbox Co.</h5>
        <p>Delivering healthy meals straight to your doorstep. Contact us for more info!</p>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h6 class="text-uppercase">Links</h6>
        <ul class="list-unstyled mb-0">
          <li><a href="home.php" class="text-white">Menu</a></li>
          <li><a href="bentoplans.php" class="text-white">Plans</a></li>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
