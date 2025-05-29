<?php
    include 'config.php';
    include 'navbar.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $user_type = trim($_POST['user_type']);

        // Basic validation
        if (empty($username) || empty($password) || empty($user_type)) {
            echo "<script>alert('All fields are required.'); window.history.back();</script>";
            exit();
        }

        // Check if username already exists
        $stmt = $conn->prepare("SELECT user_id FROM customer_data WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Username already exists.'); window.history.back();</script>";
            $stmt->close();
            exit();
        }
        $stmt->close();

        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO customer_data (user_name, user_password, user_type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $user_type);

        if ($stmt->execute()) {
            // Redirect to login page after successful signup
            header("Location: login.php?signup=success");
            exit();
        } else {
            echo "<script>alert('Error during sign up.'); window.history.back();</script>";
        }
        $stmt->close();
        $conn->close();
    } else {
        header("Location: login.php");
        exit();
    }
?>