<?php 
include 'navbar.php';
include 'conn.php';

$sql = "SELECT products_out.*, products.product_name 
        FROM products_out 
        JOIN products ON products_out.product_code = products.product_code";
$products_query = "SELECT product_code, product_name FROM products;";
$products = $conn->query($products_query);
if (!$products) {
    die("Products query failed: " . $conn->error);
}

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<div class="table flex flex-col justify-center items-center w-[95%] mx-auto">
    <div class="flex justify-between w-full">
        <p class="text-2xl font-bold py-3">Products Out</p>
        <button id="open-modal" class="bg-green-900 text-white hover:bg-green-700 px-2 my-2 rounded-md h-[50px] w-[100px]">
            New
        </button>
    </div>
    <table class="table-auto border-collapse rounded-md w-full border border-gray-300">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 text-left">Product Code</th>
            <th class="px-4 py-2 text-left">Name</th>
            <th class="px-4 py-2 text-left">Out Date</th>
            <th class="px-4 py-2 text-left">Quantity</th>
            <th class="px-4 py-2 text-left">Unit Price (RWF)</th>
            <th class="px-4 py-2 text-left">Total (RWF)</th>
            <th class="px-4 py-2 text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr class="hover:bg-gray-100">
            <td class="px-4 py-2"><?= htmlspecialchars($row['product_code']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['product_name']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['out_date']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['qty']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['unit_price']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['total']) ?></td>
            <td class="px-4 py-2 flex items-center space-x-2">
                <button class="edit-btn text-blue-500 hover:text-blue-700" 
                    data-code="<?= htmlspecialchars($row['product_code']); ?>"
                    data-name="<?= htmlspecialchars($row['product_name']); ?>"
                    data-qty="<?= htmlspecialchars($row['qty']); ?>"
                    data-date="<?= htmlspecialchars($row['out_date']); ?>"
                    data-price="<?= htmlspecialchars($row['unit_price']); ?>">
                    <i class="fa fa-edit"></i>
                </button>
                <form action="delete_productout.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
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

<!-- Modal Form -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 id="modal-title" class="text-xl font-semibold text-center mb-4">New Product Out</h2>
        <form id="product-form" method="POST" action="insert_productout.php">
            <select name="product_code" id="product_code" class="w-full px-3 py-2 border rounded mb-3" required>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($product['product_code']); ?>">
                        <?= htmlspecialchars($product['product_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="out Date" class="px-2 font-semibold">Out Date</label>
            <input type="date" id="out_date" name="out_date" class="w-full px-3 py-2 border rounded mb-3" required>
            <label for="quantity" class="px-2 font-semibold">Quantity</label>
            <input type="number" id="qty" name="qty" placeholder="Quantity" class="w-full px-3 py-2 border rounded mb-3" required>
            <label for="unit price" class="px-2 font-semibold">Unit Price</label>
            <input type="number" id="unit_price" name="unit_price" placeholder="Unit Price" class="w-full px-3 py-2 border rounded mb-3" required>
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
        document.getElementById('product-form').action = "insert_productout.php";
        document.getElementById('modal-title').innerText = "New Product Out";
    });

    closeModalBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            document.getElementById('product_code').value = button.dataset.code;
            document.getElementById('out_date').value = button.dataset.date;
            document.getElementById('qty').value = button.dataset.qty;
            document.getElementById('unit_price').value = button.dataset.price;

            document.getElementById('product-form').action = "update_productout.php";
            document.getElementById('modal-title').innerText = "Update Product Out";
            modal.classList.remove("hidden");
        });
    });
</script>
