<?php 
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_code = $_POST['product_code'];
    $qty = $_POST['qty'];
    $out_date = $_POST['out_date'];
    $unit_price = $_POST['unit_price'];
    $total = $qty * $unit_price;

    // Check if product exists in products_out
    $sql_check = "SELECT * FROM products_out WHERE product_code = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $product_code);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $sql = "UPDATE products_out SET qty = ?, out_date = ?, unit_price = ?, total = ? WHERE product_code = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isdss", $qty, $out_date, $unit_price, $total, $product_code);
        
            if ($stmt->execute()) {
                echo "Product out updated successfully.";
                header('Location: out.php?msg=Product edited successfully!');
            } else {
                echo "Error updating product out: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Product not found in the 'products_out' table.";
    }
    $stmt_check->close();
}
?>
