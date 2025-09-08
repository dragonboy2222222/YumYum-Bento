<?php
require_once "../dbconnect.php";

// Fetch all lunchboxes for navbar dropdown
$stmt = $conn->prepare("SELECT * FROM lunchboxes ORDER BY id DESC");
$stmt->execute();
$lunchboxes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get selected lunchbox
$lunchboxId = isset($_GET['lunchbox_id']) ? (int)$_GET['lunchbox_id'] : 0;

// If a lunchbox is selected → fetch its menus
if ($lunchboxId > 0) {
    // Get lunchbox name
    $stmt = $conn->prepare("SELECT name FROM lunchboxes WHERE id = ?");
    $stmt->execute([$lunchboxId]);
    $lunchbox = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM menus WHERE lunchbox_id = ? ORDER BY id DESC");
    $stmt->execute([$lunchboxId]);
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // No lunchbox selected → show latest menus
    $lunchbox = ["name" => "All Lunchboxes"];
    $stmt = $conn->prepare("SELECT m.*, l.name AS lunchbox_name 
                                 FROM menus m
                                 JOIN lunchboxes l ON m.lunchbox_id = l.id
                                 ORDER BY m.id DESC");
    $stmt->execute();
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($lunchbox['name']) ?> - Menus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <style>
    /* Set Poppins as the default font for the body and headings */
    body {
      font-family: 'Poppins', sans-serif;
    }
    
    h1, h2, h3, h4, h5, h6, .navbar-brand {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
    }
    
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
      background-color: #993333; /* slightly darker */
    }

    /* Footer */
    footer {
      background-color: #993333 !important;
      color: #fff !important;
    }
    footer a {
      color: #fff !important;
      text-decoration: none;
    }
    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../productImage/loogo.png" alt="Logo" width="300" class="d-inline-block align-text-top">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="lunchboxDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Lunchboxes
          </a>
          <ul class="dropdown-menu" aria-labelledby="lunchboxDropdown">
            <?php foreach ($lunchboxes as $lunchboxItem): ?>
              <li>
                <a class="dropdown-item" href="menus.php?lunchbox_id=<?= $lunchboxItem['id'] ?>">
                  <?= htmlspecialchars($lunchboxItem['name']) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5">
  <h2 class="text-center mb-5"><?= htmlspecialchars($lunchbox['name']) ?> Menus</h2>
  <div class="row g-4">
    <?php if (count($menus) > 0): ?>
      <?php foreach ($menus as $menu): ?>
        <div class="col-md-4">
  <div class="card menu-card h-100 shadow-sm">
    <?php if (!empty($menu['image'])): ?>
      <img src="../uploads/<?= htmlspecialchars($menu['image']) ?>" 
           alt="<?= htmlspecialchars($menu['name']) ?>" 
           class="card-img-top">
    <?php else: ?>
      <img src="../productImage/placeholder.jpg" alt="No Image" class="card-img-top">
    <?php endif; ?>
    <div class="card-body d-flex flex-column">
      <h5 class="card-title"><?= htmlspecialchars($menu['name']) ?></h5>
      <p class="card-text text-muted"><?= htmlspecialchars($menu['description']) ?></p>
      <div class="mt-auto text-center">
        <a href="lunchbox.php?id=<?= $menu['lunchbox_id'] ?>" class="btn btn-subscribe">
          Subscribe
        </a>
      </div>
    </div>
  </div>
</div>

      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">No menus found for this lunchbox.</p>
    <?php endif; ?>
  </div>
</main>


<footer class="text-center text-lg-start mt-5" style="background-color: #993333; color: #fff;">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>