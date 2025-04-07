<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mkrn") or die("Connection Failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['veg_id'])) {
    $_SESSION['cart'][$_POST['veg_id']] = ($_SESSION['cart'][$_POST['veg_id']] ?? 0) + max(1, (int)$_POST['quantity']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-3">
        <h1 class="text-center">Vegetables</h1>
        <div class="row">
            <?php
            $result = $conn->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-3'>
                        <div class='card'>
                            <img src='images/{$row['image']}' class='card-img-top' alt='{$row['product_name']}'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$row['product_name']}</h5>
                                <p class='card-text'>₱{$row['price']}</p>
                                <form method='POST'>
                                    <input type='hidden' name='veg_id' value='{$row['id']}'>
                                    <input type='number' name='quantity' class='form-control' value='1' min='1'>
                                    <button type='submit' class='btn btn-success mt-2'>Add to Cart</button>
                                </form>
                            </div>
                        </div>
                      </div>";
            }
            ?>
        </div>

        <h2 class="mt-5">Shopping Cart</h2>
        <div class="container cart">
            <?php
            $total_price = 0;
            if (!empty($_SESSION['cart'])) {
                echo "<ul class='list-group'>";
                foreach ($_SESSION['cart'] as $id => $qty) {
                    if ($row = $conn->query("SELECT product_name, price FROM products WHERE id = $id")->fetch_assoc()) {
                        $total_price += $row['price'] * $qty;
                        echo "<li class='list-group-item'>{$row['product_name']} - Qty: $qty - ₱" . number_format($row['price'] * $qty, 2) . "</li>";
                    }
                }
                echo "</ul><h4 class='mt-3'>Total: ₱" . number_format($total_price, 2) . "</h4>";
            } else {
                echo "<p>Your cart is empty.</p>";
            }
            ?>
            <form action="checkout2.php">
                <button type="submit" class="btn btn-primary">Checkout</button>
            </form>
        </div>
</body>

</html>
