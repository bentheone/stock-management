<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_code'])) {
    $product_code = intval($_POST['product_code']);
    
    $sql = "DELETE FROM products_out WHERE product_code = $product_code";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: out.php?msg=Product deleted successfully");
        exit();
    } else {
        header("Location: out.php?error=Error deleting product");
        exit();
    }
}

$conn->close();
?>
