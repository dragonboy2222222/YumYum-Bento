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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
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
    color: #730909ff !important;
    text-decoration: none;
  }
  footer a:hover {
    text-decoration: underline;
  }
</style>


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
    <?php foreach ($lunchboxes as $lunchbox): ?>
      <li>
        <a class="dropdown-item" href="menus.php?lunchbox_id=<?= $lunchbox['id'] ?>">
          <?= htmlspecialchars($lunchbox['name']) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</li>


        <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="">Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container-fluid px-0">

  <section class="row align-items-center g-0" style="background-color:#f8f4ec;">
    <div class="col-md-6">
      <img src="../productImage/yourteam.jpg" alt="Our Team" class="img-fluid w-100 h-100 object-fit-cover">
    </div>
    <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
      <h6 class="text-uppercase fw-bold mb-3" style="color:#993333;">What Makes Us Different</h6>
      <h2 class="fw-bold mb-4">Our Commitment to Craft & Culture!</h2>
      <p class="mb-4">
        With a Lunchbox subscription, enjoy delicious meals while supporting small 
        family-run businesses and preserving culinary traditions.
      </p>
      <a href="bentoplans.php" class="btn btn-primary btn-lg" style="background-color:#cc3300; border:none;">
        Subscribe Now
      </a>
    </div>
  </section>

  <section class="py-5" style="background-color:#5a1f1f; color:#fff;">
    <div class="container text-center">
      <h6 class="mb-4 text-uppercase">Featured By</h6>
      <div class="row justify-content-center align-items-center g-4">
        <div class="col-6 col-md-3">
          <img src="../productImage/bonappetit.png" alt="Bon Appetit" class="img-fluid">
        </div>
        <div class="col-6 col-md-3">
          <img src="../productImage/nytimes.png" alt="NY Times" class="img-fluid">
        </div>
        <div class="col-6 col-md-3">
          <img src="../productImage/forbes.png" alt="Forbes" class="img-fluid">
        </div>
        <div class="col-6 col-md-3">
          <img src="../productImage/today.png" alt="Today Show" class="img-fluid">
        </div>
        <div class="col-6 col-md-3">
          <img src="../productImage/strategist.png" alt="Strategist" class="img-fluid">
        </div>
        <div class="col-6 col-md-3">
          <img src="../productImage/byrdie.png" alt="Byrdie" class="img-fluid">
        </div>
        <div class="col-6 col-md-3">
          <img src="../productImage/techcrunch.png" alt="TechCrunch" class="img-fluid">
        </div>
        <div class="col-6 col-md-3">
          <img src="../productImage/fastcompany.png" alt="Fast Company" class="img-fluid">
        </div>
      </div>
    </div>
  </section>

  <section class="container py-5">
  <h2 class="text-center mb-5">How it works</h2>
  <div class="row text-center g-4">
    <div class="col-md-4">
      <img src="../productImage/subscribe.jpg" alt="Subscribe" class="img-fluid mb-3">
      <h5 class="fw-bold">SUBSCRIBE</h5>
      <p class="text-muted">
        and get a box delivered monthly filled with snacks, tea, rewards, and more.
      </p>
    </div>
    <div class="col-md-4">
      <img src="../productImage/receive.jpg" alt="Receive" class="img-fluid mb-3">
      <h5 class="fw-bold">RECEIVE</h5>
      <p class="text-muted">
        Authentic treats like cakes, mochi, & chips packed with care and delivered to your door.
      </p>
    </div>
    <div class="col-md-4">
      <img src="../productImage/experience.jpg" alt="Experience" class="img-fluid mb-3">
      <h5 class="fw-bold">EXPERIENCE</h5>
      <p class="text-muted">
        New curated themes each month around festivals, prefectures, and holidays!
      </p>
    </div>
  </div>
</section>

<section class="container py-5">
  <div class="row align-items-center g-4">
    <div class="col-md-6 text-center">
      <img src="../productImage/firstbox.jpg" alt="First Box" class="img-fluid rounded">
    </div>
    <div class="col-md-6">
      <h6 class="text-uppercase fw-bold mb-3" style="color:#993333;">Your First Box Includes:</h6>
      <h2 class="fw-bold mb-4">22 Japanese snacks, candies, & tea!</h2>
      <ul class="list-unstyled mb-4">
        <li class="mb-2">🍘 <strong>2 Bonus Rare Snacks</strong> – Subscribe for 3, 6, or 12 months and enjoy 2 rare Japanese snacks added to your first box.</li>
        <li class="mb-2">🍵 <strong>Tea Pairing</strong> – New tea each month, from matcha to hojicha.</li>
        <li class="mb-2">📖 <strong>22–24 Page Guide</strong> – Snack origins, allergen info, and more!</li>
        <li class="mb-2">🍪 <strong>Authentic Treats</strong> – Mochi, senbei, cakes, cookies, chips, & more.</li>
        <li class="mb-2">🍭 <strong>Sweet & Savory</strong> – A mix of authentic flavors for every taste.</li>
        <li class="mb-2">🏮 <strong>Exclusive</strong> – Made by local makers only for subscribers.</li>
      </ul>
      <a href="bentoplans.php" class="btn btn-primary btn-lg" style="background-color:#cc3300; border:none;">
        SUBSCRIBE NOW
      </a>
    </div>
  </div>
</section>

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

<!-- Chatbot Widget -->
<button id="chat-toggle" class="btn btn-danger rounded-circle position-fixed" 
        style="bottom:20px; right:20px; width:60px; height:60px; z-index:999;">
  💬
</button>

<div id="chat-box" class="card shadow position-fixed d-none" 
     style="bottom:90px; right:20px; width:300px; max-height:400px; z-index:999;">
  <div class="card-header bg-danger text-white">Chat with us</div>
  <div id="chat-messages" class="card-body overflow-auto" style="height:250px;"></div>
  <form id="chat-form" class="card-footer d-flex">
    <input type="text" id="chat-input" class="form-control me-2" placeholder="Type a message..." />
    <button class="btn btn-danger" type="submit">Send</button>
  </form>
</div>

<script src="../assets/chatbot.js"></script>


</body>
</html>