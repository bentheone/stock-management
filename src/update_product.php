<?php 
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];
 
    $sql = "UPDATE products SET product_name = ? WHERE product_code = ?";


    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $product_name, $product_code);

        if ($stmt->execute()) {
            echo "Product updated successfully.";
            header('Location: index.php');
        } else {
            echo "Error updating product: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
