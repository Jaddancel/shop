<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// --- Encapsulated Cart Functions ---
function add_to_cart($product_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}

function remove_one_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] -= 1;
        if ($_SESSION['cart'][$product_id] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

function remove_all_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// --- Handle Cart Actions ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'], $_POST['action'])) {
    $product_id = intval($_POST['product_id']);
    switch ($_POST['action']) {
        case 'add':
            add_to_cart($product_id);
            break;
        case 'remove_one':
            remove_one_from_cart($product_id);
            break;
        case 'remove_all':
            remove_all_from_cart($product_id);
            break;
    }
}

header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'));
exit();
?>