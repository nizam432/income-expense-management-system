@extends('adminlte::page')

@section('title', 'Edit Expense')

@section('content_header')
    <h1>Edit Expense</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">Update Expense</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="expense_date" class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" id="expense_date" class="form-control" 
                           value="{{ $expense->expense_date }}" required>
                </div>
                <div class="col-md-8">
                    <label for="note" class="form-label">Note</label>
                    <input type="text" name="note" id="note" class="form-control" 
                           value="{{ $expense->note }}" placeholder="Optional note...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle " id="expenseTable">
                    <thead class="table-light">
                        <tr>
                            <th width="20%" >Expense Category</th>
                            <th width="25%">Product / Service</th>
                            <th width="10%">Qty</th>
                            <th width="15%">Unit</th>
                            <th width="15%">Price (৳)</th>
                            <th width="10%">Total (৳)</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expense->details as $key => $detail)
                        <tr>
                            <td style="min-width:150px">
                                <select name="products[{{ $key }}][category_id]" class="form-control category-select" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $detail->category_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="min-width:150px">
                                <select name="products[{{ $key }}][product_id]" class="form-control product-select" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $prod)
                                        @if ($prod->category_id == $detail->category_id)
                                            <option value="{{ $prod->id }}" {{ $prod->id == $detail->product_id ? 'selected' : '' }}>
                                                {{ $prod->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td style="min-width:100px"><input type="number" name="products[{{ $key }}][quantity]" class="form-control quantity" 
                                       value="{{ $detail->quantity }}" min="1"></td>
                            <td style="min-width:100px">
                                <select name="products[{{ $key }}][unit]" class="form-control unit">
                                    <option value="Pcs" {{ $detail->unit == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="Kilogram" {{ $detail->unit == 'Kilogram' ? 'selected' : '' }}>Kilogram (KG)</option>
                                    <option value="Gram" {{ $detail->unit == 'Gram' ? 'selected' : '' }}>Gram (GM)</option>
                                    <option value="Other" {{ $detail->unit == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </td>
                            <td style="min-width:100px"><input type="number" name="products[{{ $key }}][price]" class="form-control price" 
                                       value="{{ $detail->price }}" step="0.01"></td>
                            <td class="line-total text-end">{{ number_format($detail->total, 2) }}</td>
                            <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" id="addRow" class="btn btn-success btn-sm mb-3">
                <i class="fas fa-plus"></i> Add Item
            </button>

            <div class="text-end mt-3">
                <h4><strong>Total Amount:</strong> <span id="grandTotal">{{ number_format($expense->total_amount, 2) }}</span> ৳</h4>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-warning btn-lg mt-3">
                    <i class="fas fa-save"></i> Update Expense
                </button>
            </div>
        </form>
    </div>
</div>
@stop


@section('js')
<script>
$(document).ready(function(){

    // 🔄 Load products dynamically by category
    $(document).on('change', '.category-select', function(){
        let categoryId = $(this).val();
        let productDropdown = $(this).closest('tr').find('.product-select');
        productDropdown.html('<option value="">Loading...</option>');

        if(!categoryId) {
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

    // ➕ Add new row dynamically
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

    // 🔢 Total calculation (with gram logic)
    $(document).on('input change', '.quantity, .price, .unit', function(){
        recalcTotal();
    });

    function recalcTotal() {
        let grandTotal = 0;
        $('#expenseTable tbody tr').each(function(){
            const qty = parseFloat($(this).find('.quantity').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;
            const unit = ($(this).find('.unit').val() || '').toLowerCase().trim();

            let total = 0;
            if (unit.includes('gram')) total = price;
            else total = qty * price;

            $(this).find('.line-total').text(total.toFixed(2));
            grandTotal += total;
        });
        $('#grandTotal').text(grandTotal.toFixed(2));
    }

    // Calculate once on load
    recalcTotal();
});
</script>
@stop
