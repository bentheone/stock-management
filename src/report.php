<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'navbar.php';
include 'conn.php';
?>

<div class="table flex flex-col justify-center items-center w-[95%] mx-auto mt-10">
    <form action="" method="post" class="flex flex-wrap gap-4 justify-center">
        <div class="flex flex-col mt-4 w-[350px]">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="border p-2 rounded" required>
        </div>
        <div class="flex flex-col mt-4 w-[350px]"> 
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="border p-2 rounded" required>
        </div>
        <div class="flex flex-col mt-4 w-[350px]"> 
            <label for="product">Product</label>
            <select name="product" id="product" class="border p-2 rounded" required>
                <option value="all">All</option>
                <?php
                $stmt = $conn->prepare("SELECT product_code, product_name FROM products");
                if (!$stmt) {
                    die("SQL Prepare Error: " . $conn->error); // Error Handling
                }
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while ($product = $result->fetch_assoc()) {
                        echo "<option value='{$product['product_code']}'>{$product['product_name']}</option>";
                    }
                } else {
                    echo "<option disabled>No products found</option>";
                }
                ?>
            </select>
        </div>
        <div class="flex w-full justify-center mt-4">
            <button type="submit" name="view_report" 
                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                View Report
            </button>
        </div>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['view_report'])) {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $product_id = $_POST['product'];

        $query = "SELECT p.product_name AS product_name, po.qty AS out_qty, po.out_date, pi.qty AS in_qty, pi.in_date 
                  FROM Products p
                  LEFT JOIN products_out po ON p.product_code = po.product_code AND po.out_date BETWEEN ? AND ?
                  LEFT JOIN products_in pi ON p.product_code = pi.product_code AND pi.in_date BETWEEN ? AND ?";

        if ($product_id !== "all") {
            $query .= " WHERE p.product_code = ?";
        }

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("SQL Prepare Error: " . $conn->error); 
        }

        if ($product_id === "all") {
            $stmt->bind_param("ssss", $start_date, $end_date, $start_date, $end_date);
        } else {
            $stmt->bind_param("sssss", $start_date, $end_date, $start_date, $end_date, $product_id);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='mt-6 w-full'>";
                echo "<h2 class='text-lg font-bold mb-4'>Product Report from $start_date to $end_date</h2>";
                echo "<table class='border-collapse border w-full'>
                        <thead>
                            <tr class='bg-gray-200'>
                                <th class='border px-4 py-2'>Product Name</th>
                                <th class='border px-4 py-2'>In Quantity</th>
                                <th class='border px-4 py-2'>In Date</th>
                                <th class='border px-4 py-2'>Out Quantity</th>
                                <th class='border px-4 py-2'>Out Date</th>
                            </tr>
                        </thead>
                        <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td class='border px-4 py-2'>{$row['product_name']}</td>
                            <td class='border px-4 py-2'>{$row['in_qty']}</td>
                            <td class='border px-4 py-2'>{$row['in_date']}</td>
                            <td class='border px-4 py-2'>{$row['out_qty']}</td>
                            <td class='border px-4 py-2'>{$row['out_date']}</td>
                          </tr>";
                }

                echo "</tbody></table></div>";
            } else {
                echo "<p class='text-red-500 mt-4'>No records found for the selected product:". $_POST['product']. "and date range: ".$start_date ." and". $end_date."</p>";
            }
        } else {
            echo "<p class='text-red-500 mt-4'>Error executing query: " . $stmt->error . "</p>";
        }
    }
    ?>
    
    <div class="mt-6">
        <button onclick="printReport()" class="bg-red-500 text-white py-2 px-4 rounded">Print Report</button>
    </div>
</div>

<script>
    function printReport() {
        window.print();
    }
</script>
