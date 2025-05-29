<?php
    session_start();
    include 'config.php';

    // Only allow admin users
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'A') {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['product_name']);
        $image_url = trim($_POST['product_image_url']);
        $price = trim($_POST['product_price']);
        $date = trim($_POST['product_date']);

        // Basic validation
        if (empty($name) || empty($image_url) || empty($price) || empty($date)) {
            echo "<script>alert('All fields are required.'); window.history.back();</script>";
            exit();
        }

        // Insert product
        $stmt = $conn->prepare("INSERT INTO products (product_name, product_image_url, product_price, product_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $image_url, $price, $date);

        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            echo "<script>alert('Error adding product.'); window.history.back();</script>";
        }
        $stmt->close();
        $conn->close();
    } else {
        header("Location: admin.php");
        exit();
    }
?>