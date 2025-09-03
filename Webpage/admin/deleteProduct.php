<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../dbconnect.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // safety

    try {
        // First get image path to delete file
        $stmt = $conn->prepare("SELECT imgPath FROM product WHERE productID = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['imgPath']) && file_exists($row['imgPath'])) {
            unlink($row['imgPath']); // delete old image file
        }

        // Delete product from DB
        $sql = "DELETE FROM product WHERE productID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        $_SESSION['message'] = "✅ Product deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['message'] = "❌ Error deleting product: " . $e->getMessage();
    }
}

header("Location: viewProduct.php");
exit;
?>
