<?php
    session_start();
    include 'config.php';

    // Only allow admin users
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'A') {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        // Delete the product
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            echo "<script>alert('Error deleting product.'); window.history.back();</script>";
        }
        $stmt->close();
        $conn->close();
    } else {
        header("Location: admin.php");
        exit();
    }
?>