<?php
if (!isset($conn)) {
    chdir(__DIR__ . '/..');
    $page = 'add_order';
    include('dashboard.php');
    exit();
}
?>

<div class="max-w-7xl mx-auto">
    <div class="glass-card rounded-3xl p-6 hover-lift">
        <div class=" mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800"> Add Order </h2>
                <p class="text-slate-500 text-sm mt-1"> Create customer order</p>
            </div>
        </div>

        <form method="POST" action="order_save.php">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Name
                    </label>
                    <input type="text" name="customer_name" required placeholder="Enter customer name" class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Order Date
                    </label>
                    <input type="date" name="order_date" min="<?php echo date('Y-m-d'); ?>" required class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-gray-200">
                <table id="orderTable" class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-gray-700 text-sm">
                            <th class="px-5 py-4 text-left"> Sr. No.</th>
                            <th class="px-5 py-4 text-left"> Product</th>
                            <th class="px-5 py-4 text-left"> Qty</th>
                            <th class="px-5 py-4 text-left"> Price </th>
                            <th class="px-5 py-4 text-left">Total</th>
                            <th class="px-5 py-4 text-left">
                                <div class="flex items-center gap-2">
                                    <span>Action</span>
                                    <button type="button" class="addRow w-8 h-8 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-bold shadow flex items-center justify-center transition pb-1 text-center leading-none text-lg">+</button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t" data-detail-id="" data-head-id="">
                            <td class="p-4">
                                <input type="text"  readonly value="1" class="w-full rounded-xl bg-gray-100 border border-gray-200 px-3 py-2 sr-no">
                            </td>

                             <td class="p-4">
                                 <input type="hidden" name="detail_id[]" value="">
                                 <input type="text" name="product_name[]" required placeholder="Product name" class="w-full rounded-xl border border-gray-200 px-3 py-2">
                             </td>
                            <td class="p-4">
                                <input  type="number"  name="quantity[]" required min="1" placeholder="0" class="w-full rounded-xl border border-gray-200 px-3 py-2 qty">
                            </td>
                            <td class="p-4">
                                <input type="number" step="0.01"  name="price[]" required min="0" placeholder="0.00" class="w-full rounded-xl border border-gray-200 px-3 py-2 price">
                            </td>
                            <td class="p-4">
                                <input type="text" name="total[]" readonly value="0.00" class="w-full rounded-xl bg-gray-100 border border-gray-200 px-3 py-2 total">
                            </td>

                            <td class="p-4">
                                <button type="button" class="removeRow text-red-500 hover:text-red-700 transition">
                                    <i class="fa-solid fa-trash text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button  type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-md shadow-indigo-600/20 hover:shadow-lg transition duration-200 hover:-translate-y-0.5 transform"> Save Order </button>
                 <a href="order_list.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-3 rounded-xl font-semibold transition"> Cancel </a>
            </div>
        </form>
    </div>
</div>
<script src="js/order.js"></script>