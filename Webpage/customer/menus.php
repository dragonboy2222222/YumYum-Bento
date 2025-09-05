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
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .menu-card img {
      height: 220px;
      object-fit: cover;
      border-radius: 12px 12px 0 0;
    }
    .menu-card {
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .menu-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm" style="background-color:#993333 !important;">
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="home.php">Lunchbox Co.</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white" href="home.php">Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="lunchboxDropdown" role="button" data-bs-toggle="dropdown">
            Lunchboxes
          </a>
          <ul class="dropdown-menu" aria-labelledby="lunchboxDropdown">
            <?php foreach ($lunchboxes as $lb): ?>
              <li><a class="dropdown-item" href="menus.php?lunchbox_id=<?= $lb['id'] ?>">
                <?= htmlspecialchars($lb['name']) ?>
              </a></li>
            <?php endforeach; ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- MENUS SECTION -->
<main class="container py-5">
  <h2 class="text-center mb-5"><?= htmlspecialchars($lunchbox['name']) ?> Menus</h2>
  <div class="row g-4">
    <?php if (count($menus) > 0): ?>
      <?php foreach ($menus as $menu): ?>
        <div class="col-md-4">
          <div class="card menu-card h-100">
            <?php if (!empty($menu['image'])): ?>
              <img src="../uploads/<?= htmlspecialchars($menu['image']) ?>" 
                   alt="<?= htmlspecialchars($menu['name']) ?>" 
                   class="card-img-top">
            <?php else: ?>
              <img src="C:\YumYumBox\Webpage\lunchbox_images\placeholder.jpg" alt="No Image" class="card-img-top">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($menu['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($menu['description']) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">No menus found for this lunchbox.</p>
    <?php endif; ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
