<?php
    session_start();
    include 'navbar.php';
    include 'config.php';

    // Show Bootstrap 5 alert if redirected after successful signup
    if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
        echo '<div class="alert alert-success text-center" role="alert">
                Sign up successful! You can now log in.
              </div>';
    }

    // Handle login POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Also select user_type
        $stmt = $conn->prepare("SELECT user_id, user_name, user_password, user_type FROM customer_data WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $user_name, $user_password, $user_type);
            $stmt->fetch();
            // Use password_verify to check hashed password
            if (password_verify($password, $user_password)) {
                $_SESSION['user'] = $user_name;
                $_SESSION['user_type'] = $user_type;
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "index.php";
                    }, 3000);
                </script>';
                echo '<div class="alert alert-success text-center mt-3">Login successful! Redirecting to home page...</div>';
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('User not found.');</script>";
        }
        $stmt->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Login</h1>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="alert alert-success text-center">
                Hello, <?php echo htmlspecialchars($_SESSION['user']); ?>!
            </div>
        <?php else: ?>
        <form action="login.php" method="post" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#signupModal">
                Sign Up
            </button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="signup.php" method="post">
            <div class="modal-header">
              <h5 class="modal-title" id="signupModalLabel">Sign Up</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="signup_username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="signup_username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="signup_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="signup_password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="signup_type" class="form-label">User Type</label>
                    <select class="form-select" id="signup_type" name="user_type" required>
                        <option value="C">Customer</option>
                        <option value="A">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Sign Up</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>