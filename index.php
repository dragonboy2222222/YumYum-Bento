<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunchbox Subscription</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand" href="#">
      <img src="logo.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
      Lunchbox Co.
    </a>

    <!-- Toggle button for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar items -->
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Plans</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
      </ul>
    </div>
  </div></nav>

<!-- ===== BODY ===== -->
<main class="container my-5">
  <div class="text-center mb-5">
    <h1>Welcome to Lunchbox Subscription</h1>
    <p class="lead">Delicious lunchboxes delivered right to your door!</p>
  </div>

  <!-- Example content cards -->
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
      <div class="card h-100">
        <img src="lunchbox1.jpg" class="card-img-top" alt="Lunchbox 1">
        <div class="card-body">
          <h5 class="card-title">Lunchbox A</h5>
          <p class="card-text">Healthy and tasty lunch for your day.</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100">
        <img src="lunchbox2.jpg" class="card-img-top" alt="Lunchbox 2">
        <div class="card-body">
          <h5 class="card-title">Lunchbox B</h5>
          <p class="card-text">Fresh ingredients and balanced nutrition.</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100">
        <img src="lunchbox3.jpg" class="card-img-top" alt="Lunchbox 3">
        <div class="card-body">
          <h5 class="card-title">Lunchbox C</h5>
          <p class="card-text">Delicious options for busy professionals.</p>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ===== FOOTER ===== -->
<footer class="bg-light text-center text-lg-start mt-5 border-top">
  <div class="container p-4">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-4">
        <h5 class="text-uppercase">Lunchbox Co.</h5>
        <p>Delivering healthy meals straight to your doorstep. Contact us for more info!</p>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h6 class="text-uppercase">Links</h6>
        <ul class="list-unstyled mb-0">
          <li><a href="#" class="text-dark">Menu</a></li>
          <li><a href="#" class="text-dark">Plans</a></li>
          <li><a href="#" class="text-dark">About Us</a></li>
          <li><a href="#" class="text-dark">Contact Us</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h6 class="text-uppercase">Follow Us</h6>
        <ul class="list-unstyled mb-0">
          <li><a href="#" class="text-dark">Facebook</a></li>
          <li><a href="#" class="text-dark">Instagram</a></li>
          <li><a href="#" class="text-dark">Twitter</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="text-center p-3 bg-light border-top">
    © 2025 Lunchbox Co. All rights reserved.
  </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
