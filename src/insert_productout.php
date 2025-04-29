<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_code = $_POST['product_code'];  
    $qty = intval($_POST['qty']);
    $out_date = date('Y-m-d', strtotime($_POST['out_date']));
    $unit_price = floatval($_POST['unit_price']);

    if (!empty($product_code) && $qty > 0 && !empty($out_date) && $unit_price > 0) {
        $total = $qty * $unit_price;

        // Check available quantity from products_in
        $stmt_check = $conn->prepare("SELECT SUM(qty) AS total_in_qty FROM products_in WHERE product_code = ?");
        if (!$stmt_check) {
            echo "Error preparing SELECT query: " . $conn->error;
            exit();
        }
        $stmt_check->bind_param("s", $product_code);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $available_qty = intval($row['total_in_qty']);

            if ($available_qty >= $qty) {
                // Insert into products_out
                $stmt = $conn->prepare("INSERT INTO products_out (product_code, qty, out_date, unit_price, total) VALUES (?, ?, ?, ?, ?)");
                if (!$stmt) {
                    echo "Error preparing INSERT query: " . $conn->error;
                    exit();
                }
                $stmt->bind_param("sissd", $product_code, $qty, $out_date, $unit_price, $total);
                
                if ($stmt->execute()) {
                    if ($available_qty == $qty) {
                        $stmt_delete_in = $conn->prepare("DELETE FROM products_in WHERE product_code = ?");
                        $stmt_update_in = $conn->prepare("UPDATE products_in SET qty = qty - $qty WHERE product_code = ?");
                        $stmt_delete_in->bind_param("s", $product_code);
                        $stmt_delete_in->execute();
                        
                        $stmt_delete_product = $conn->prepare("DELETE FROM products WHERE product_code = ?");
                        $stmt_delete_product->bind_param("s", $product_code);
                        $stmt_delete_product->execute();
                    }

                    
                    header("Location: out.php?msg=Product successfully processed");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error: Insufficient stock!";
            }
        } else {
            echo "Error: No stock available for the selected product!";
        }
    } else {
        echo "All fields are required and must be valid!";
    }
}
?>
