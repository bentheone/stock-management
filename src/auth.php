<?php
session_start();
include('conn.php');

$error_message = '';

// Handle login when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Invalid login credentials!";
        }
    } else {
        $error_message = "No such user found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body class="flex justify-center items-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded shadow-md w-80">
        <h2 class="text-3xl font-semibold text-center">STOCK SYSTEM</h2>
        <h2 class="text-xl font-semibold text-center">Welcome back!</h2>
        <p class="text-center text-md text-gray mb-4">Let's get you back...</p>

        <!-- Display Error Message -->
        <?php if (!empty($error_message)): ?>
            <p class="text-red-500 text-sm text-center"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <!-- Submit form to auth.php itself -->
        <form action="auth.php" method="POST">
            <input type="text" placeholder="Username" name="username" class="w-full px-3 py-2 border rounded mb-3 focus:outline-none focus:ring focus:border-green-900" required>
            <input type="password" name="password" placeholder="Password" class="w-full px-3 py-2 border rounded mb-3 focus:outline-none focus:ring focus:border-green-900" required>
            <button type="submit" class="w-full bg-green-700 text-white py-2 rounded hover:bg-green-600">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php" class="text-blue-500">Sign Up Here!</a></p>
    </div>
</body>
</html>
