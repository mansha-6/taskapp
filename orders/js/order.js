// javascript run only after the complete HTML page is loaded.
$(document).ready(function () {

    calculateAll();

    // $(document).on() existing rows/newly added rows
    $(document).on('click', '.addRow', function () {
        let $lastRow = $('#orderTable tbody tr').last();
        let $newRow;

        if ($lastRow.length > 0) {
            $newRow = $lastRow.clone();
            $newRow.attr('data-detail-id', '');
            $newRow.attr('data-head-id', '');
            $newRow.find('input').each(function () {
                let $input = $(this);
                // Check is this: detail_id[] ? Why? because edit rows already have DB IDs and New row must not reuse old ID.
                if ($input.attr('name') === 'detail_id[]') {
                    // makes it blank.
                    $input.val(''); 
                } else if ($input.hasClass('total') || $input.hasClass('total_id') || $input.hasClass('sr-no')) {
                    $input.val('0.00');
                    if ($input.hasClass('total_id') || $input.hasClass('sr-no')) $input.val('1');
                } else {
                    // All normal fields:
                    // product
                    // qty
                    // price become empty.
                    $input.val('');
                }
            });
        } else {
            // If table empty then create full row manually.
            $newRow = $(`
                <tr class="border-t" data-detail-id="" data-head-id="">
                    <td class="p-4">
                        <input type="text" readonly value="1" class="w-full rounded-xl bg-gray-100 border border-gray-200 px-3 py-2 sr-no">
                    </td>
                    <td class="p-4">
                        <input type="hidden" name="detail_id[]" value="">
                        <input type="text" name="product_name[]" required placeholder="Product name" class="w-full rounded-xl border border-gray-200 px-3 py-2">
                    </td>
                    <td class="p-4">
                        <input type="number" name="quantity[]" required min="1" placeholder="0" class="w-full rounded-xl border border-gray-200 px-3 py-2 qty">
                    </td>
                    <td class="p-4">
                        <input type="number" step="0.01" name="price[]" required min="0" placeholder="0.00" class="w-full rounded-xl border border-gray-200 px-3 py-2 price">
                    </td>
                    <td class="p-4">
                        <input type="text" name="total[]" readonly value="0.00" class="w-full rounded-xl bg-gray-100 border border-gray-200 px-3 py-2 total">
                    </td>
                    <td class="p-4">
                        <button type="button" class="removeRow w-10 h-10 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold shadow flex items-center justify-center">
                            −
                        </button>
                    </td>
                </tr>
            `);
        }

        $('#orderTable tbody').append($newRow);
        calculateAll();

    });

    
    $(document).on('click', '.removeRow', function () {
// Delete only if rows > 1 to prevent completely empty table at least one row stays.
        if ($('#orderTable tbody tr').length > 1) {
            // closest('tr') Go upward and find nearest row.
            $(this).closest('tr').remove();
            calculateAll();
        }
    });

    // keyup=typing & change=using arrows/paste/tab.
    $(document).on('keyup change', '.qty,.price', function () {
   // Current qty/price field.
        let $input = $(this);
        let val = parseFloat($input.val());
        // If current field is qty.
        if ($input.hasClass('qty')) {
            // If no. less than 1 or not a number Example: -5/abc then invalid.
            if (val < 1 || isNaN(val)) {
                if ($input.val() !== '') {
                    $input.val(1); // minimum qty should be 1
                }
            }
        } else if ($input.hasClass('price')) {
            // If no. less than 0 or not a number Example: -5/abc then invalid.
            if (val < 0 || isNaN(val)) {
                if ($input.val() !== '') {
                    $input.val(0); // minimum price should be 0
                }
            }
        }
        calculateAll();

    });

    // Automatically remove red border when user types or changes an input field
    $(document).on('input change', 'input', function () {
        $(this).removeClass('border-red-500');
    });

    // Handle form submit on order_add.php and order_edit.php
    $('form').on('submit', function (e) {
        let $form = $(this);

        // Check if already confirmed
        if ($form.data('confirmed')) {
            return true;
        }

        e.preventDefault();

        // 1. Compulsory fields validation
        let valid = true;
        let emptyFields = [];

        // Remove any previous validation error styling
        $form.find('.border-red-500').removeClass('border-red-500');

        // Check customer name and order date
        let $customerName = $('input[name="customer_name"]');
        let $orderDate = $('input[name="order_date"]');

        let customerName = $customerName.val().trim();
        let orderDate = $orderDate.val().trim();

        if (customerName === '') {
            valid = false;
            emptyFields.push('Customer Name');
            $customerName.addClass('border-red-500');
        }
        if (orderDate === '') {
            valid = false;
            emptyFields.push('Order Date');
            $orderDate.addClass('border-red-500');
        }

        // Check all product rows in the table
        $('#orderTable tbody tr').each(function (index) {
            let rowNum = index + 1;
            let $row = $(this);
            let $product = $row.find('input[name="product_name[]"]');
            let $qty = $row.find('input[name="quantity[]"]');
            let $price = $row.find('input[name="price[]"]');

            let productVal = $product.val().trim();
            let qtyVal = $qty.val().trim();
            let priceVal = $price.val().trim();

            let rowEmpty = false;
            if (productVal === '') {
                rowEmpty = true;
                $product.addClass('border-red-500');
            }
            if (qtyVal === '') {
                rowEmpty = true;
                $qty.addClass('border-red-500');
            }
            if (priceVal === '') {
                rowEmpty = true;
                $price.addClass('border-red-500');
            }

            if (rowEmpty) {
                valid = false;
                emptyFields.push('Row ' + rowNum + ' Fields');
            }
        });

        if (!valid) {
            Swal.fire({
                title: 'Validation Error',
                text: 'All fields are compulsory! Please fill out the highlighted fields.',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
            return false;
        }

        // 2. Alert message for adding or editing
        let isEdit = $form.attr('action').indexOf('update') !== -1;
        let title = isEdit ? 'Update Order?' : 'Place Order?';
        let text = isEdit ? 'Are you sure you want to update this order?' : 'Are you sure you want to create this new order?';

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $form.data('confirmed', true).submit();
            }
        });
    });

    function calculateAll() {

        let srNo = 1;

        $('#orderTable tbody tr').each(function () {

            let qty = parseFloat(
                $(this).find('.qty').val() //$(this)=current row.
            ) || 0;
            if (qty < 0) qty = 0;

            let price = parseFloat(
                $(this).find('.price').val()
            ) || 0; //If empty: use 0
            if (price < 0) price = 0;

            let total = qty * price;

            $(this)
                .find('.total') //Find total box set value.
                .val(total.toFixed(2)); //toFixed(2) keeps:2 decimal places (300.00)

            $(this)
                .find('.sr-no')
                .val(srNo++);

        });

    }

});