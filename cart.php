<?php
session_start();
include 'config.php';
include 'navbar.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Handle "Place Order" button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    if (!empty($_SESSION['cart'])) {
        // Get user id
        $user_name = $_SESSION['user'];
        $user_query = $conn->prepare("SELECT user_id FROM customer_data WHERE user_name = ?");
        $user_query->bind_param("s", $user_name);
        $user_query->execute();
        $user_query->bind_result($user_id);
        $user_query->fetch();
        $user_query->close();

        // Insert each cart item as an order
        foreach ($_SESSION['cart'] as $pid => $qty) {
            $stmt = $conn->prepare("INSERT INTO orders (customer_id, product_id, order_quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $pid, $qty);
            $stmt->execute();
            $stmt->close();
        }
        // Clear cart after order
        unset($_SESSION['cart']);
        echo '<div class="alert alert-success">Order placed successfully!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Your Cart</h2>
    <?php
    if (!empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $total = 0;
        echo '<table class="table table-bordered w-auto">';
        echo '<thead><tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr></thead><tbody>';
        foreach ($cart as $pid => $qty) {
            $pid = intval($pid);
            $res = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $pid");
            if ($row = mysqli_fetch_assoc($res)) {
                $subtotal = $row['product_price'] * $qty;
                $total += $subtotal;
                echo '<tr>
                        <td>'.htmlspecialchars($row['product_name']).'</td>
                        <td>'.$qty.'</td>
                        <td>'.number_format($row['product_price'], 2).' PHP</td>
                        <td>'.number_format($subtotal, 2).' PHP</td>
                        <td>
                            <form action="cart_process.php" method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="'.$pid.'">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn-success btn-sm">+</button>
                            </form>
                            <form action="cart_process.php" method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="'.$pid.'">
                                <input type="hidden" name="action" value="remove_one">
                                <button type="submit" class="btn btn-warning btn-sm">-</button>
                            </form>
                            <form action="cart_process.php" method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="'.$pid.'">
                                <input type="hidden" name="action" value="remove_all">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                      </tr>';
            }
        }
        echo '</tbody></table>';
        echo '<h5 class="text-end">Total: '.number_format($total, 2).' PHP</h5>';
        // Place Order button in its own form
        echo '<form method="post" style="display:inline;">
                <button type="submit" name="place_order" class="btn btn-primary mt-3">Place Order</button>
              </form>';
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>
    <a href="index.php" class="btn btn-secondary mt-3">Back to Shop</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>