<?php
session_start();
include('conn.php');
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check_sql = "SELECT * FROM Users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "Username already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Users(username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password); 
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['username'] = $username;
            header('Location: auth.php');
            exit();
        } else {
            $error_message = "Something went wrong, please try again!";
        }
    }
}
?>
