<?php
session_start();
require_once "../dbconnect.php";

// ✅ Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit;
}

// ✅ Get username from session
$username = $_SESSION["username"];

// ✅ Fetch full user details from database
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ If user not found, force logout
if (!$user) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

$user_id = $user["id"];
$role = $user["role"];

// ✅ Fetch profile info
$stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Profile picture (default if none)
$profilePic = "../productImage/default-avatar.png";
if ($profile && !empty($profile["profile_image"])) {
    $profilePic = "../" . $profile["profile_image"];
}

// ✅ Fetch lunchboxes
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: #993333 !important;
        }
        .navbar-nav .nav-link,
        .navbar-brand {
            color: #fff !important;
        }
        .navbar-nav .nav-link:hover {
            color: #ffd9d9 !important;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }
        .image-container {
            height: 500px;
            overflow: hidden;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.2s ease-in-out; /* Faster transition */
        }
        .image-container:hover img {
            transform: scale(1.05);
        }
        h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        p {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #555;
        }
        section {
            padding: 40px;
            background-color: #f8f4ec;
        }
        .btn {
            background-color: #cc3300;
            border: none;
            color: white;
            padding: 15px 25px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out; /* Faster transition */
        }
        .btn:hover {
            background-color: #993333;
            transform: scale(1.05);
        }
        /* Section for Featured By */
        .section-featured-by {
            background-color: #5a1f1f;
            color: #fff;
            padding: 50px 0;
            position: relative;
            overflow: hidden;
        }
        .featured-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }
        .text-overlay {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }
        .brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            padding: 0.5rem;
            text-transform: uppercase;
            color: #fff; /* Changed to white as requested */
        }
        @media (max-width: 768px) {
            .brand-text {
                font-size: 1.2rem;
            }
        }
        /* Image hover animation for the 'How it works' and 'First Lunchbox' sections */
        .img-fluid.rounded {
            transition: transform 0.2s ease-in-out; /* Faster transition */
        }
        .img-fluid.rounded:hover {
            transform: scale(1.03);
        }
        .predefined-questions-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 8px; /* Spacing between buttons */
            max-height: 150px;
            overflow-y: auto;
        }

        .predefined-q {
            font-size: 0.85rem; /* Smaller font size */
            padding: 6px 10px; /* Smaller padding */
            white-space: nowrap; /* Prevents text from wrapping */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <ul class="navbar-nav d-flex flex-row align-items-center me-auto">
                <li class="nav-item">
                    <a class="navbar-brand" href="home.php">
                        <img src="../productImage/loogo.png" alt="Logo" width="280" class="d-inline-block align-text-top">
                    </a>
                </li>
                
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link dropdown-toggle active" href="#" id="lunchboxDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        LunchBoxes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lunchboxDropdown2">
                        <?php foreach ($lunchboxes as $lunchbox): ?>
                            <li><a class="dropdown-item" href="lunchbox.php?id=<?= $lunchbox['id'] ?>"><?= htmlspecialchars($lunchbox['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link dropdown-toggle" href="#" id="lunchboxDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Menus
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lunchboxDropdown">
                        <?php foreach ($lunchboxes as $lunchbox): ?>
                            <li><a class="dropdown-item" href="menus.php?lunchbox_id=<?= $lunchbox['id'] ?>"><?= htmlspecialchars($lunchbox['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="aboutus.php">About Us</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="reviews.php">Reviews</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="faq.php">FAQ</a></li>
            </ul>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown d-lg-none">
                        <a class="nav-link dropdown-toggle active" href="#" id="lunchboxDropdownMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            LunchBoxes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="lunchboxDropdownMobile">
                            <?php foreach ($lunchboxes as $lunchbox): ?>
                                <li><a class="dropdown-item" href="lunchbox.php?id=<?= $lunchbox['id'] ?>"><?= htmlspecialchars($lunchbox['name']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown d-lg-none">
                        <a class="nav-link dropdown-toggle" href="#" id="lunchboxDropdownMobile2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Menus
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="lunchboxDropdownMobile2">
                            <?php foreach ($lunchboxes as $lunchbox): ?>
                                <li><a class="dropdown-item" href="menus.php?lunchbox_id=<?= $lunchbox['id'] ?>"><?= htmlspecialchars($lunchbox['name']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item d-lg-none"><a class="nav-link" href="aboutus.php">About Us</a></li>
                    <li class="nav-item d-lg-none"><a class="nav-link" href="#">Reviews</a></li>
                    <li class="nav-item d-lg-none"><a class="nav-link" href="faq.php">FAQ</a></li>
                    
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <?php
                                $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                                if ($cartCount > 0):
                            ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $cartCount ?>
                                    <span class="visually-hidden">cart items</span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="profile.php">
                            <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile" class="profile-img">
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="../logout.php" class="btn btn-outline-light">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>




<main class="container-fluid px-0">

<section class="row align-items-center g-0" style="background-color:#f8f4ec;">
    <div class="col-md-6 image-container">
        <img src="../productImage/fam.webp" alt="Our Team" class="img-fluid object-fit-cover">
    </div>
    <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
        <h6 class="text-uppercase fw-bold mb-3" style="color:#993333;">What Makes Us Different</h6>
        <h2 class="fw-bold mb-4">Our Commitment to Craft & Culture!</h2>
        <p class="mb-4">
            With a Lunchbox subscription, enjoy delicious meals while supporting small family-run businesses and preserving culinary traditions.
        </p>
        <a href="lunchbox.php" class="btn btn-primary btn-lg">
            Subscribe Now
        </a>
    </div>
</section>


<section class="py-5" style="background-color:#5a1f1f; color:#fff;">
    <div class="container text-center">
        <h6 class="mb-4 text-uppercase">Featured By</h6>
        <div class="row justify-content-center align-items-center g-4">
            <div class="col-6 col-md-3">
                <p class="brand-text">Bon Appetit</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="brand-text">NY Times</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="brand-text">Forbes</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="brand-text">Today Show</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="brand-text">Strategist</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="brand-text">Byrdie</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="brand-text">TechCrunch</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="brand-text">Fast Company</p>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <h2 class="text-center mb-5">How it works</h2>
    <div class="row text-center g-4">
        <div class="col-md-4">
            <img src="../productImage/pic1.webp" alt="Subscribe" class="img-fluid mb-3 rounded">
            <h5 class="fw-bold">SUBSCRIBE</h5>
            <p class="text-muted">
                and get a box delivered monthly filled with snacks, tea, rewards, and more.
            </p>
        </div>
        <div class="col-md-4">
            <img src="../productImage/pic2.webp" alt="Receive" class="img-fluid mb-3 rounded">
            <h5 class="fw-bold">RECEIVE</h5>
            <p class="text-muted">
                Authentic treats like cakes, mochi, & chips packed with care and delivered to your door.
            </p>
        </div>
        <div class="col-md-4">
            <img src="../productImage/pic3.webp" alt="Experience" class="img-fluid mb-3 rounded">
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
            <img src="../productImage/pic3.webp" alt="Lunchbox" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h6 class="text-uppercase fw-bold mb-3" style="color:#993333;">Your First Lunchbox Includes:</h6>
            <h2 class="fw-bold mb-4">Fresh and Delicious Meals Delivered to You!</h2>
            <ul class="list-unstyled mb-4">
                <li class="mb-2">🍱 <strong>3 Delicious Meals</strong> – Enjoy three different dishes, each packed with nutritious ingredients and made fresh daily.</li>
                <li class="mb-2">🥗 <strong>Healthy Sides</strong> – Complement your meal with a variety of healthy sides, such as fresh salads or steamed vegetables.</li>
                <li class="mb-2">🍓 <strong>Seasonal Fruits</strong> – A sweet and refreshing touch to your meal with handpicked seasonal fruits.</li>
                <li class="mb-2">🍶 <strong>Drink Pairing</strong> – Each lunchbox includes a refreshing beverage, from juices to herbal teas.</li>
                <li class="mb-2">🍰 <strong>Sweet Treats</strong> – End your meal on a sweet note with a variety of desserts like cupcakes or cookies.</li>
                <li class="mb-2">🛍️ <strong>Convenient Delivery</strong> – Delivered straight to your door for a hassle-free meal experience.</li>
            </ul>
            <a href="lunchbox.php" class="btn btn-primary btn-lg">
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
                <p style="color: #fff;">Delivering healthy meals straight to your doorstep. Contact us for more info!</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="text-uppercase">Links</h6>
                <ul class="list-unstyled mb-0">
                    <li><a href="term.php" class="text-white">Terms and Conditions</a></li>
                    <li><a href="policy.php" class="text-white">Privacy Policy</a></li>
                    <li><a href="aboutus.php" class="text-white">About Us</a></li>
                    <li><a href="faq.php" class="text-white">FAQ</a></li>
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

<button id="chat-toggle" class="btn btn-danger rounded-circle position-fixed" 
        style="bottom:20px; right:20px; width:60px; height:60px; z-index:999;">
    💬
</button>

<div id="chat-box" class="card shadow position-fixed d-none" 
      style="bottom:90px; right:20px; width:300px; max-height:400px; z-index:999;">
    <div class="card-header bg-danger text-white">Chat with us</div>
    <div id="chat-messages" class="card-body overflow-auto" style="height:250px;"></div>
    
    <div id="predefined-questions" class="card-body overflow-auto" style="max-height: 150px;"></div>

    <form id="chat-form" class="card-footer d-flex">
        <input type="text" id="chat-input" class="form-control me-2" placeholder="Type a message..." />
        <button class="btn btn-danger" type="submit">Send</button>
    </form>
</div>

<script src="../assets/chatbot.js"></script>


</body>
</html>