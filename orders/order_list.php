<?php
if (!isset($conn)) {
    chdir(__DIR__ . '/..');
    $page = 'orders';
    include('dashboard.php');
    exit();
}
$success = $_GET['success'] ?? '';
?>

<div id="ordersContent">
    <div class="glass-card rounded-3xl p-6 hover-lift">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Orders
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    Manage customer orders
                </p>
            </div>

            <a
                href="order_add.php"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-semibold shadow-md shadow-indigo-600/20 hover:shadow-lg transition duration-200 hover:-translate-y-0.5 transform">

                + Add Order

            </a>

        </div>

        <div id="tableWrapper">

            <table
                id="ordersTable"
                class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-gray-700 text-sm">

                        <th class="px-4 py-4 text-left">ID</th>
                        <th class="px-4 py-4 text-left">Customer</th>
                        <th class="px-4 py-4 text-left">Order Date</th>
                        <th class="px-4 py-4 text-left">Total Quantity</th>
                        <th class="px-4 py-4 text-left">Total Price</th>
                        <th class="px-4 py-4 text-left">Action</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    <?php

                    $query = mysqli_query(

                        $conn,

                        "SELECT
                    h.id as head_id,
                    h.customer_name,
                    h.order_date,
                    SUM(d.quantity) as total_qty,
                    SUM(d.total) as total_price
                    FROM head h
                    LEFT JOIN details d
                    ON h.id = d.head_id AND d.is_deleted = 0
                    WHERE h.is_deleted = 0
                    GROUP BY h.id, h.customer_name, h.order_date
                    ORDER BY h.id ASC"
                    );

                    $sr = 1;
                    while ($row = mysqli_fetch_assoc($query)) {

                    ?>

                        <tr class="hover:bg-gray-50 transition" data-id="<?php echo $row['head_id']; ?>">

                            <td class="px-4 py-4 font-semibold text-gray-700" data-id="<?php echo $row['head_id']; ?>" data-head-id="<?php echo $row['head_id']; ?>">
                                <?php echo $sr++; ?>
                            </td>

                            <td class="px-4 py-4">
                                <?php echo htmlspecialchars($row['customer_name']); ?>
                            </td>

                            <td class="px-4 py-4">
                                <?php echo date("d-m-Y", strtotime($row['order_date'])); ?>
                            </td>

                            <td class="px-4 py-4">
                                <?php echo $row['total_qty']; ?>
                            </td>

                            <td class="px-4 py-4 text-green-600 font-semibold">
                                ₹<?php echo number_format($row['total_price'], 2); ?>
                            </td>

                            <td class="px-4 py-4">

                                <div class="flex items-center justify-start gap-2">

                                    <a
                                        href="order_edit.php?id=<?php echo $row['head_id']; ?>"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-xl shadow text-sm">
                                        Edit
                                    </a>

                                    <a
                                        href="order_delete.php?id=<?php echo $row['head_id']; ?>"
                                        onclick="confirmDeleteOrder(event, this.href)"
                                        class="ajax-action text-red-500 hover:text-red-700 transition">

                                        <i class="fa-solid fa-trash text-lg"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>
</div>

<script>
   
    $(document).ready(function() {
        initializeOrdersTable();

        let hasAlert = false;

        <?php if ($success == "added") { ?>
            hasAlert = true;
            Swal.fire({
                title: 'Success!',
                text: 'Order Added Successfully',
                icon: 'success',
                confirmButtonColor: '#4f46e5'
            });
        <?php } elseif ($success == "updated") { ?>
            hasAlert = true;
            Swal.fire({
                title: 'Success!',
                text: 'Order Updated Successfully',
                icon: 'success',
                confirmButtonColor: '#4f46e5'
            });
        <?php } ?>

        /* REMOVE success FROM URL */
        if (hasAlert) {
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, '', url);
        }
    });

    function loadOrders() { //This function is used to reload orders section dynamically instead of full page refresh it updates only: #ordersContent section used after:delete,AJAX update,row changes.
        let currentUrl = window.location.href; //location=page add, href= fullurl
        $.get(currentUrl, function(responseText) {
            const parser = new DOMParser(); //convert HTML text into readable HTML document.
            const doc = parser.parseFromString(responseText, 'text/html');
            const newContent = doc.getElementById('ordersContent');
            if (newContent) {
                $('#ordersContent').html(newContent.innerHTML);//.html() means replace HTML inside element .innerHTML means: All HTML inside element.
            }
            initializeOrdersTable();
        });
    }

    function initializeOrdersTable() {

        if ($.fn.DataTable.isDataTable('#ordersTable')) {

            $('#ordersTable').DataTable().destroy();

        }

        if ($('#ordersTable').parent().hasClass('overflow-x-auto')) {

            $('#ordersTable').unwrap();//Remove parent wrapper.

        }

        $('#ordersTable').DataTable({

            responsive: false,
            pageLength: 10,
            ordering: true,
            searching: true,
            destroy: true

        });

        if (!$('#ordersTable').parent().hasClass('overflow-x-auto')) {

            $('#ordersTable').wrap( //wrap() means:Add parent wrapper.Example:
        //    Before: <table>
        // After:<div class="overflow-x-auto"><table></div>
                '<div class="overflow-x-auto rounded-2xl border border-gray-200 mt-4 mb-4"></div>'
            );

        }
    }

    function confirmDeleteOrder(event, url) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get(url, function(response) {
                    if (response.trim() === "success") {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Order has been deleted successfully.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadOrders();
                    } else {
                        Swal.fire(
                            'Error!',
                            'Something went wrong during deletion.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>