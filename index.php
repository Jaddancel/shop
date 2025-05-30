<?php
    session_start(); // Start the session at the very top
    include 'config.php';
    include 'navbar.php';

    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-md mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Computer Shop</h1>
            <div>
            <?php if (empty($_SESSION['user'])): ?>
                <a href="login.php" class="btn btn-success">Log In</a>
            <?php else: ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</span>
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'A'): ?>
                    <div class="d-inline-flex gap-2">
                        <a href="admin.php" class="btn btn-warning">Admin Panel</a>
                        <a href="orders.php" class="btn btn-primary">Orders</a>
                        <a href="logout.php" class="btn btn-danger">Log Out</a>
                    </div>
                <?php else: ?>
                    <a href="cart.php" class="btn btn-info">Cart</a>
                    <a href="logout.php" class="btn btn-danger">Log Out</a>
                <?php endif; ?>
            <?php endif; ?>
            </div>
        </div>
        <hr>
        
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <?php
            while ($row = mysqli_fetch_array($result)){
                echo '<div class="col">
                        <div class="card h-100" style="height: 200px;">
                            <img class="card-img-top" src="'. $row['product_image_url'] .'" alt="Card image" style="padding: 10px;">
                            <div class="card-body">
                                <h4 class="card-title">'. $row['product_name'] .'</h4>
                                <p class="card-text">'. $row['product_price'] .' PHP'.'</p>
                                <form action="add_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="'. $row['product_id'] .'">
                                    <button type="submit" class="btn btn-primary">Add To Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>';
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>