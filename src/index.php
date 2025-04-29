<?php 
include 'navbar.php';
include 'conn.php';
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<div class="table flex flex-col justify-center items-center w-[95%] mx-auto ">
    <div class="flex justify-between w-full">
        <p class="text-2xl font-bold py-3">Products </p>
        <button id="open-modal" class="bg-green-900 text-white hover:bg-green-700 px-2 my-2 rounded-md h-[50px] w-[100px]">
            New
        </button>
    </div>
    <table class="table-auto border-collapse rounded-md w-full border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-left w-[20px]">Product Code</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-300 px-4 py-2 text-left w-[20px]">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="hover:bg-gray-100">
                <td><?= htmlspecialchars($row['product_code']); ?></td>
                <td><?= htmlspecialchars($row['product_name']); ?> <i class="text-xs text-gray-300"><?= htmlspecialchars($row['createdAt']); ?></i> </td>
                <td class="border border-gray-300 px-4 py-2 text-left">
                    <!-- Edit Button -->
                    <button class="edit-btn text-blue-500 hover:text-blue-700">
                        <i class="fa fa-edit"></i>
                    </button>
                    <!-- Delete Button -->
                    <form action="delete_product.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <input type="hidden" name="product_code" value="<?= htmlspecialchars($row['product_code']); ?>">
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-xl font-semibold text-center mb-4" id="modal-title">New Product</h2>
        <form id="product-form" method="POST" action="insert_product.php">
            <input type="text" id="product_name" placeholder="Product Name" class="w-full px-3 py-2 border rounded mb-3 focus:outline-none focus:ring focus:border-blue-300" name="product_name" required>
            <input type="text" id="product_code" placeholder="Code" class="w-full px-3 py-2 border rounded mb-3 focus:outline-none focus:ring focus:border-blue-300" name="product_code" required>
            <div class="flex justify-between">
                <button type="button" id="close-modal" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Cancel</button>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById("modal");
    const openModalBtn = document.getElementById("open-modal");
    const closeModalBtn = document.getElementById("close-modal");

    
    openModalBtn.addEventListener("click", () => {
        modal.classList.remove("hidden");
        document.getElementById('product-form').action = "insert_product.php"; 
        document.getElementById('modal-title').innerText = "New Product"; 
    });

    closeModalBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const row = event.target.closest('tr');
            const product_code = row.querySelector('td:first-child').innerText;
            const product_name = row.querySelector('td:nth-child(2)').innerText;

            document.getElementById('product_name').value = product_name;
            document.getElementById('product_code').value = product_code;

            document.getElementById('product-form').action = "update_product.php";
            document.getElementById('modal-title').innerText = "Update Product"; 

            modal.classList.remove("hidden");
        });
    });
</script>
</body>
</html>
