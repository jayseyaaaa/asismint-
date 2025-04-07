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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 16px;
            background-color: #f5f5f5;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 24px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
        }

        .product {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 220px;
            padding: 16px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        .product h5 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .product p {
            margin: 0 0 12px;
            color: #555;
        }

        .product input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .product button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .product button:hover {
            background-color: #218838;
        }

        .cart {
            margin-top: 40px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart ul {
            list-style: none;
            padding: 0;
        }

        .cart li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart h4 {
            margin-top: 20px;
        }

        .cart button {
            margin-top: 16px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cart button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Vegetables</h1>
    <div class="products">
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>
                    <img src='images/{$row['image']}' alt='{$row['product_name']}'>
                    <h5>{$row['product_name']}</h5>
                    <p>₱{$row['price']}</p>
                    <form method='POST'>
                        <input type='hidden' name='veg_id' value='{$row['id']}'>
                        <input type='number' name='quantity' value='1' min='1'>
                        <button type='submit'>Add to Cart</button>
                    </form>
                  </div>";
        }
        ?>
    </div>

    <div class="cart">
        <h2>Shopping Cart</h2>
        <?php
        $total_price = 0;
        if (!empty($_SESSION['cart'])) {
            echo "<ul>";
            foreach ($_SESSION['cart'] as $id => $qty) {
                if ($row = $conn->query("SELECT product_name, price FROM products WHERE id = $id")->fetch_assoc()) {
                    $total_price += $row['price'] * $qty;
                    echo "<li>{$row['product_name']} - Qty: $qty - ₱" . number_format($row['price'] * $qty, 2) . "</li>";
                }
            }
            echo "</ul><h4>Total: ₱" . number_format($total_price, 2) . "</h4>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
        <form action="checkout2.php">
            <button type="submit">Checkout</button>
        </form>
    </div>
</body>

</html>

