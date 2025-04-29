<?php 
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_code = $_POST['product_code'];
    $qty = $_POST['qty'];
    $in_date = $_POST['in_date'];
    $unit_price = $_POST['unit_price'];
    $total = $qty * $unit_price;

    $sql_check = "SELECT * FROM products_in WHERE product_code = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $product_code);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $sql = "UPDATE products_in SET qty = ?, in_date = ?, unit_price = ?, total = ? WHERE product_code = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isdss", $qty, $in_date, $unit_price, $total, $product_code);
        
            if ($stmt->execute()) {
                echo "Product in updated successfully.";
                header('Location: in.php?msg=Product edited successfully!');
            } else {
                echo "Error updating product in: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Product not found in the 'products_in' table.";
    }
    $stmt_check->close();
}
?>
