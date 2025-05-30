<?php
session_start();
include 'config.php';
include 'navbar.php';

// Only allow admin users
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'A') {
    header("Location: index.php");
    exit();
}

// Fetch all orders with user and product info
$sql = "SELECT 
            o.order_id,
            c.user_name,
            p.product_name,
            o.order_quantity,
            p.product_price,
            (o.order_quantity * p.product_price) AS total_price,
            o.order_timestamp
        FROM orders o
        JOIN customer_data c ON o.customer_id = c.user_id
        JOIN products p ON o.product_id = p.product_id
        ORDER BY o.order_timestamp DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>All Orders</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price per Item</th>
                    <th>Total Price</th>
                    <th>Order Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_quantity']); ?></td>
                    <td><?php echo number_format($row['product_price'], 2); ?> PHP</td>
                    <td><?php echo number_format($row['total_price'], 2); ?> PHP</td>
                    <td><?php echo date('d M Y g:i:s A', strtotime($row['order_timestamp'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No orders found.</div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>