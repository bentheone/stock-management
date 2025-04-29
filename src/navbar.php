<?php 
session_start();

if(isset($_SESSION['username'])) {
    $username =  $_SESSION['username']; 
} else {
    header('Location: auth.php'); 
}
include 'conn.php';
$products_query = "SELECT * FROM products";
$products_in_query = "SELECT * FROM products_in";
$products_out_query = "SELECT * FROM products_out";
$products = $conn->query($products_query);
$products_in = $conn->query($products_in_query);
$products_out = $conn->query($products_out_query);

$total_products = $products->num_rows;
$total_products_in = $products_in->num_rows;
$total_products_out = $products_out->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body class="bg-gray-100 flex">
    <!-- Sidebar Navigation -->
    <div class="nav fixed bg-green-900 text-center items-center h-screen w-[200px]">
        <div class="flex flex-col justify-start">
        <ul class="space-y-4 pt-[10px]">
            <li>
                <a href="index.php" class="block text-lg p-2 text-white hover:text-gray-300" id="products-link">
                    <i class="fas fa-box-open"></i>
                    Products
                </a>
            </li>
            <li>
                <a href="in.php" class="block text-lg p-2 text-white hover:text-green-400" id="in-link">
                    <i class="fas fa-dolly"></i>
                    In
                </a>
            </li>
            <li>
                <a href="out.php" class="block text-lg p-2 text-white hover:text-red-400" id="out-link">
                    <i class="fas fa-truck-loading"></i>
                    Out
                </a>
            </li>
            <li>
                <a href="report.php" class="block text-lg p-2 text-white hover:text-green-400" id="report-link">
                    <i class="fas fa-file-alt"></i>
                    Report
                </a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="logout.php" class="block text-lg p-2 text-white hover:text-gray-300">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
        </div>
    </div>

   
    <div class="main w-full ml-[200px]">
        <div class="top  flex bg-gray-200 justify-between w-full h-[65px] px-4 pt-4 ">
            <div class="stats flex">
                <a href="#" class="text-xl font-bold h-[20px] px-3">STOCK <i class="text-sm">SYSTEM</i></a>
                <p class="text-xl font-bold text-green-900 cursor-pointer p-2 rounded">Total <?php echo htmlspecialchars($total_products) ?></p>
                <p class="text-xl font-bold text-green-900 cursor-pointer p-2 rounded">In <?php echo htmlspecialchars($total_products_in) ?></p>
                <p class="text-xl font-bold text-green-900 cursor-pointer p-2 rounded">Out <?php echo htmlspecialchars($total_products_out) ?></p>
            </div>
            <div class="user">
                Logged in as <i class="font-bold text-xl"><?php echo $_SESSION['username'] ?></i>
            </div>
        </div>