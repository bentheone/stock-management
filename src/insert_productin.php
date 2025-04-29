<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_code = $_POST['product_code'];  
    $qty = $_POST['qty'];
    $in_date = date('Y-m-d', strtotime($_POST['in_date']));
    $unit_price = $_POST['unit_price'];
    echo "$product_code,$in_date, $qty, $in_date, $unit_price";

    if (!empty($product_code) && !empty($qty) && !empty($in_date) && !empty($unit_price)) {
        $total = $qty * $unit_price;

        $stmt_check = $conn->prepare("SELECT id FROM products WHERE product_code = ?");
        $stmt_check->bind_param("s", $product_code);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("INSERT INTO products_in (product_code, qty, in_date, unit_price, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sissd", $product_code, $qty, $in_date, $unit_price, $total);  

            if ($stmt->execute()) {
                header("Location: in.php?msg=Product added successfully");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error: The selected product does not exist!";
        }
    } else {
        echo "All fields are required!";
    }
}
?>
