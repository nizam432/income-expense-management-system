@extends('adminlte::page')

@section('title', 'Add Expense')

@section('content_header')
    <h1>Add New Expense</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Create Expense</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="expense_date" class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" id="expense_date" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label for="note" class="form-label">Note</label>
                    <input type="text" name="note" id="note" class="form-control" placeholder="Optional note...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="expenseTable">
                    <thead class="table-light">
                        <tr>
                            <th width="20%">Expense Category</th>
                            <th width="25%">Product / Service</th>
                            <th width="10%">Qty</th>
                            <th width="15%">Unit</th>
                            <th width="15%">Price (৳)</th>
                            <th width="10%">Total (৳)</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="min-width:150px">
                                <select name="products[0][category_id]" class="form-control category-select" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="min-width:150px">
                                <select name="products[0][product_id]" class="form-control product-select" required>
                                    <option value="">Select Product</option>
                                </select>
                            </td>
                            <td style="min-width:100px"><input type="number" name="products[0][quantity]" class="form-control quantity" value="1" min="1"></td>
                            <td style="min-width:150px">
                                <select name="products[0][unit]" class="form-control unit-select unit">
                                    <option value="Pcs">Pcs</option>
                                    <option value="Kilogram">Kilogram (KG)</option>
                                    <option value="Gram">Gram (GM)</option>
                                    <option value="Other">Other</option>
                                </select>
                            </td>
                            <td style="min-width:100px"><input type="number" name="products[0][price]" class="form-control price" value="0" step="0.01"></td>
                            <td class="line-total text-end">0.00</td>
                            <td ><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" id="addRow" class="btn btn-success btn-sm mb-3">
                <i class="fas fa-plus"></i> Add Item
            </button>

            <div class="text-end mt-3">
                <h4><strong>Total Amount:</strong> <span id="grandTotal">0.00</span> ৳</h4>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg mt-3">
                    <i class="fas fa-save"></i> Save Expense
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>

$(document).ready(function () {

    // 🔁 Dynamic calculation on input/change
    $(document).on('input change', '.quantity, .price, .unit', function () {
        recalcTotal();
    });

    // ➕ Add new row
    $(document).on('click', '#addRow', function () {
        let newRow = $('#expenseTable tbody tr:first').clone();

        // Clear all input/select values
        newRow.find('input').val('');
        newRow.find('.line-total').text('0.00');
        newRow.find('select').prop('selectedIndex', 0);

       // $('#expenseTable tbody').append(newRow);
    });

    // 🔢 Total calculation function
    function recalcTotal() {
        let grandTotal = 0;

        $('#expenseTable tbody tr').each(function () {
            const qty = parseFloat($(this).find('.quantity').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;
            const unit = ($(this).find('.unit').val() || '').toLowerCase().trim();

            let total = 0;

            // ✅ যদি unit gram হয় তাহলে শুধু price হবে
            if (unit.includes('gram')) {
                total = price;
            } else {
                total = qty * price;
            }

            $(this).find('.line-total').text(total.toFixed(2));
            grandTotal += total;
        });

        $('#grandTotal').text(grandTotal.toFixed(2));
    }

});
</script>

<script>
$(document).ready(function(){

/*     // 🧮 Recalculate total
    function recalcTotal() {
        let grandTotal = 0;
        $('#expenseTable tbody tr').each(function(){
            const qty = parseFloat($(this).find('.quantity').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;
            const total = qty * price;
            $(this).find('.line-total').text(total.toFixed(2));
            grandTotal += total;
        });
        $('#grandTotal').text(grandTotal.toFixed(2));
    } */

    // 🔄 Load product list dynamically
    $(document).on('change', '.category-select', function() {
        let categoryId = $(this).val();
        let productDropdown = $(this).closest('tr').find('.product-select');
 
  

        // আগের product options clear করো
        productDropdown.html('<option value="">Loading...</option>');
        
        if (!categoryId) {
            productDropdown.html('<option value="">Select Product</option>');
            return;
        }
 
        $.get('{{ url('expenses/get-products') }}/' + categoryId, function(data){
            let options = '<option value="">Select Product</option>';
            
            data.forEach(p => {
                options += `<option value="${p.id}">${p.name}</option>`;
            });
            productDropdown.html(options);
        });
    });

    // ➕ Add new row
    $('#addRow').click(function(){
        let i = $('#expenseTable tbody tr').length;
        let newRow = `
        <tr>
            <td>
                <select name="products[${i}][category_id]" class="form-control category-select" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="products[${i}][product_id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                </select>
            </td>
            <td><input type="number" name="products[${i}][quantity]" class="form-control quantity" value="1" min="1"></td>
            <td>
                <select name="products[${i}][unit]" class="form-control unit">
                    <option value="Pcs">Pcs</option>
                    <option value="Kilogram">Kilogram (KG)</option>
                    <option value="Gram">Gram (GM)</option>
                    <option value="Other">Other</option>
                </select>
            </td>
            <td><input type="number" name="products[${i}][price]" class="form-control price" value="0" step="0.01"></td>
            <td class="line-total text-end">0.00</td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
        </tr>`;
        $('#expenseTable tbody').append(newRow);
    });

    // 🗑 Remove row
    $(document).on('click', '.remove-row', function(){
        $(this).closest('tr').remove();
        recalcTotal();
    });

    // 🧾 Calculate on input
    $(document).on('input', '.quantity, .price', recalcTotal);
});
</script>
@stop
