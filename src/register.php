<?php 
include 'conn.php'; 
$error_message = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'register_process.php'; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register</title>
</head>
<body class="flex justify-center items-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded shadow-md w-80">
        <h2 class="text-3xl font-semibold text-center">STOCK SYSTEM</h2>
        <h2 class="text-xl font-semibold text-center">Welcome!</h2>
        <p class="text-center text-md text-gray mb-4">Let's get you started...</p>

        <?php if ($error_message): ?>
            <p class="text-red-500 text-sm text-center"><?= $error_message ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="text" placeholder="Username" name="username" class="w-full px-3 py-2 border rounded mb-3 focus:outline-none focus:ring focus:border-green-900" required>
            <input type="password" name="password" placeholder="Password" class="w-full px-3 py-2 border rounded mb-3 focus:outline-none focus:ring focus:border-green-900" required>
            <button type="submit" class="w-full bg-green-700 text-white py-2 rounded hover:bg-green-600">Register</button>
        </form>
        <p>Already have an account? <a href="login.php" class="text-blue-500">Log In Here!</a></p>
    </div>
</body>
</html>
