<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $prod_name = trim($_POST['prod_name']);
    $prod_desc = trim($_POST['prod_description']);

    if (!empty($prod_name) && !empty($prod_desc)) {
        $stmt = $conn->prepare("UPDATE products SET prod_name = ?, prod_description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $prod_name, $prod_desc, $id);

        if ($stmt->execute()) {
            header("Location: index.php?msg=Product updated successfully");
        } else {
            header("Location: index.php?error=Error updating product");
        }

        $stmt->close();
    } else {
        header("Location: index.php?error=Please fill all fields");
    }
}

$conn->close();
?>
