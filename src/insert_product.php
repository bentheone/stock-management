<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];

    if (!empty($product_code) && !empty($product_name)) {
        $stmt = $conn->prepare("INSERT INTO Products (product_code, product_name) VALUES (?, ?)");
        $stmt->bind_param("ss", $product_code, $product_name); 

        if ($stmt->execute()) {
            header("Location: index.php?msg=Product added successfully");
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Product code and name cannot be empty!";
    }
}