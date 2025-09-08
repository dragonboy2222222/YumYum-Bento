<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../dbconnect.php";

// Insert logic
if (isset($_POST["insertBtn"])) {
    $name = $_POST["pname"];
    $duration_days = $_POST["duration_days"];

    try {
        $sql = "INSERT INTO plans (name, duration_days) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $flag = $stmt->execute([$name, $duration_days]);
        $id = $conn->lastInsertId();
        if ($flag) {
            $_SESSION['message'] = "✅ Plan with ID $id inserted successfully!";
            header("Location: insertPlans.php");
            exit;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// Fetch all plans
$plans = $conn->query("SELECT * FROM plans ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Plans</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --red-dark: #993333;
            --red-medium: #cc3300;
            --cream: #f8f4ec;
            --white: #ffffff;
            --gray-dark: #333333;
            --gray-light: #eeeeee;
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

        .form-card {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            max-width: 700px;
            width: 100%;
            margin: auto;
            margin-bottom: 40px;
        }

        .form-card label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form-card input {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid var(--gray-light);
        }

        .form-card button {
            background-color: var(--red-medium);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-card button:hover {
            background-color: var(--red-dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        table th, table td {
            padding: 14px;
            border-bottom: 1px solid var(--gray-light);
            text-align: center;
        }

        table th {
            background: var(--red-dark);
            color: var(--white);
        }

        table tr:hover {
            background: var(--gray-light);
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            color: var(--white);
        }

        .btn-edit {
            background: #007bff;
        }

        .btn-edit:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
        }

        .btn-delete:hover {
            background: #a71d2a;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                padding-bottom: 15px;
            }
            .main {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Lunchbox Admin</h2>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="insertProduct.php">🍱 Manage Lunchboxes</a>
        <a href="insertMenu.php">🥗 Manage Menus</a>
        <a href="insertPlans.php" class="active">📅 Manage Plans</a>
        <a href="viewUser.php">📋 View Users</a>
        <a href="viewProduct.php">📦 Lunchbox Reports</a>
        <a href="viewMenu.php">📖 Menu Reports</a>
        <div class="logout">
            <a href="../login.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main">
        <h1>Insert Plan</h1>
        <div class="form-card">
            <form action="insertPlans.php" method="post">
                <label>Plan Name</label>
                <input type="text" name="pname" required>

                <label>Duration (Days)</label>
                <input type="number" name="duration_days" required>

                <button type="submit" name="insertBtn">➕ Insert Plan</button>
            </form>
        </div>

        <h1>All Plans</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Plan Name</th>
                <th>Duration (Days)</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($plans as $plan): ?>
                <tr>
                    <td><?php echo $plan['id']; ?></td>
                    <td><?php echo htmlspecialchars($plan['name']); ?></td>
                    <td><?php echo $plan['duration_days']; ?></td>
                    <td>
                        <a href="editPlans.php?id=<?php echo $plan['id']; ?>" class="btn btn-edit">✏️ Edit</a>
                        <a href="deletePlans.php?id=<?php echo $plan['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this plan?');">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
