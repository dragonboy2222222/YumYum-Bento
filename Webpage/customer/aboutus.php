<?php
session_start();
require_once "../dbconnect.php";

// login is optional here
$loggedIn = isset($_SESSION["username"]);

// Fetch all lunchboxes for navbar dropdown
$stmt = $conn->prepare("SELECT * FROM lunchboxes ORDER BY id DESC");
$stmt->execute();
$lunchboxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Lunchbox Subscription</title>
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

        .about-section {
            background-color: #f8f4ec;
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
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
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
                <li class="nav-item"><a class="nav-link active" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Reviews</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <section class="container-fluid about-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="fw-bold mb-4" style="color:#993333;">Our Story</h2>
                    <p class="lead">
                        Lunchbox Co. was born from a simple idea: to bring the joy of wholesome, delicious meals to everyone's doorstep. We believe that good food is not just about sustenance; it's about culture, community, and care. Our journey began with a passion for supporting local, family-owned food businesses and a mission to make their culinary traditions accessible to you.
                    </p>
                    <p>
                        We meticulously curate our menus to offer a diverse range of flavors and experiences. Each meal is a tribute to authentic recipes, crafted with the freshest ingredients and packed with love. We're more than just a subscription service; we are a bridge connecting you to the rich tapestry of flavors from local kitchens.
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="../productImage/ourstory.jpg" alt="Our Story" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>
    
    <section class="container py-5">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="fw-bold mb-5">Meet Our Team</h2>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0">
                    <img src="../productImage/team1.jpg" class="card-img-top rounded-circle mx-auto" alt="Team Member 1" style="width: 150px; height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title mt-3">Jane Doe</h5>
                        <p class="card-text text-muted">Co-Founder & Head Chef</p>
                        <p>With over 20 years of experience, Jane brings her passion for traditional cooking to every menu.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0">
                    <img src="../productImage/team2.jpg" class="card-img-top rounded-circle mx-auto" alt="Team Member 2" style="width: 150px; height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title mt-3">John Smith</h5>
                        <p class="card-text text-muted">Co-Founder & Operations</p>
                        <p>John ensures our logistics run smoothly, so your delicious meals arrive fresh and on time.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0">
                    <img src="../productImage/team3.jpg" class="card-img-top rounded-circle mx-auto" alt="Team Member 3" style="width: 150px; height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title mt-3">Emily White</h5>
                        <p class="card-text text-muted">Community Manager</p>
                        <p>Emily is our voice and your main point of contact. She loves hearing your feedback and stories!</p>
                    </div>
                </div>
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
</body>
</html>