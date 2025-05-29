<?php
    session_start();
    include 'config.php';
    include 'navbar.php';

    // Only allow admin users
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'A') {
        header("Location: index.php");
        exit();
    }

    // Fetch all products
    $result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Products</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rounded-table {
            border-radius: 1rem !important;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        }
        .btn-rounded {
            border-radius: 2rem !important;
        }
        .modal-content {
            border-radius: 1.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Product List</h2>
            <button class="btn btn-success btn-rounded" data-bs-toggle="modal" data-bs-target="#addProductModal">
                + Add Product
            </button>
        </div>
        <table class="table table-bordered table-striped rounded-table align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image URL</th>
                    <th>Image Preview</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="bg-white"><?php echo htmlspecialchars($row['product_id']); ?></td>
                    <td class="bg-white"><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td class="bg-white" style="max-width:200px; word-break:break-all;"><?php echo htmlspecialchars($row['product_image_url']); ?></td>
                    <td class="bg-white">
                        <img src="<?php echo htmlspecialchars($row['product_image_url']); ?>" alt="Product Image" style="max-width:80px; max-height:80px; border-radius: 0.75rem;">
                    </td>
                    <td class="bg-white"><?php echo htmlspecialchars($row['product_price']); ?></td>
                    <td class="bg-white"><?php echo htmlspecialchars($row['product_date']); ?></td>
                    <td class="bg-white">
                        <form action="delete_product.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm btn-rounded" onclick="return confirm('Are you sure you want to delete this product?');">
                                Remove
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content p-3">
          <form action="add_product.php" method="post">
            <div class="modal-header border-0">
              <h5 class="modal-title fw-bold" id="addProductModalLabel">Add Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Name</label>
                    <input type="text" class="form-control rounded-pill" id="product_name" name="product_name" required>
                </div>
                <div class="mb-3">
                    <label for="product_image_url" class="form-label">Image URL</label>
                    <input type="text" class="form-control rounded-pill" id="product_image_url" name="product_image_url" required>
                </div>
                <div class="mb-3">
                    <label for="product_price" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control rounded-pill" id="product_price" name="product_price" required>
                </div>
                <div class="mb-3">
                    <label for="product_date" class="form-label">Date</label>
                    <input type="date" class="form-control rounded-pill" id="product_date" name="product_date" required
        value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success btn-rounded">Add Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>