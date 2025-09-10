<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lunchbox Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Define new color variables based on the previous design */
        :root {
            --red-dark: #993333;
            --red-medium: #cc3300;
            --cream: #f8f4ec;
            --white: #ffffff;
            --gray-dark: #333333;
            --gray-light: #eeeeee;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background-color: var(--red-dark);
            color: var(--white);
            padding: 30px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            color: var(--cream);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            color: var(--white);
            text-decoration: none;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: var(--red-medium);
            color: var(--white);
            transform: translateX(5px);
        }

        .logout {
            margin-top: auto;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .logout a {
            color: var(--white);
            font-weight: 600;
            text-transform: uppercase;
        }

        .logout a:hover {
            color: var(--cream);
        }

        .main {
            margin-left: 280px;
            padding: 40px;
            width: calc(100% - 280px);
        }

        .main h1 {
            color: var(--red-dark);
            margin-bottom: 25px;
            font-weight: 700;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .card h2 {
            color: var(--red-medium);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .card p,
        .card ul {
            color: var(--gray-dark);
        }

        .card ul {
            list-style-type: none;
        }

        .card li {
            padding: 8px 0;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .card li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Lunchbox Admin</h2>
    <a href="dashboard.php" class="active">📊 Dashboard</a>
    <a href="insertProduct.php" >🍱 Manage Lunchboxes</a>
    <a href="viewUser.php">📋 View Users</a>
    <a href="viewProduct.php" >📦 Lunchbox Reports</a>
    <a href="insertmenu.php">🧾 Insert Menus</a>
    <a href="viewmenu.php">📖 View Menus</a>
    <a href="insertPlans.php">📅 Insert Plans</a>

    <div class="logout">
        <a href="../login.php">🚪 Logout</a>
    </div>
    </div>

    <div class="main">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>! 👋</h1>
        
        <div class="card-container">
            <div class="card">
                <h2>Dashboard Overview</h2>
                <p>Welcome to the Lunchbox Admin Panel. Here you can manage everything related to your e-commerce store, from orders and products to customer information and communication.</p>
            </div>

            <div class="card">
                <h2>Quick Stats</h2>
                <ul>
                    <li>Total Orders Today: 12</li>
                    <li>Pending Shipments: 3</li>
                    <li>New Customers: 5</li>
                    <li>Revenue This Week: $1,250</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>Recent Orders</h2>
                <ul>
                    <li>Order #12345 - **Pending**</li>
                    <li>Order #12344 - **Shipped**</li>
                    <li>Order #12343 - **Delivered**</li>
                    <li>Order #12342 - **Pending**</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>Support Chats</h2>
                <ul>
                    <li>Chat with Customer A - **Active**</li>
                    <li>Chat with Customer B - **Pending**</li>
                    <li>Chat with Customer C - **Closed**</li>
                    <li>Chat with Customer D - **Active**</li>
                </ul>
            </div>
            
        </div>
    </div>

</body>
</html>