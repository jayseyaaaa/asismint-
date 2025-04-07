<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mkrn") or die("DB Error");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    $u = htmlspecialchars($_POST['username']);
    $c = htmlspecialchars($_POST['contact_number']);

    foreach ($_SESSION['cart'] as $id => $qty) {
        $row = $conn->query("SELECT product_name, price FROM products WHERE id = $id")->fetch_assoc();
        $conn->query("INSERT INTO orders (username, contact_number, product, qty, price) 
                      VALUES ('$u', '$c', '{$row['product_name']}', $qty, {$row['price']} * $qty)");
    }
    $_SESSION['cart'] = [];
    echo "<script>alert('Order placed!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            justify-content: center;
            margin-top: 80px;
        }

        .card {
            background: #fff;
            padding: 32px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 24px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 16px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h2>Checkout</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <button type="submit">Buy Now</button>
            </form>
        </div>
    </div>
</body>

</html>

