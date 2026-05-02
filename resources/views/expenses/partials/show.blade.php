<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Date</th>
            <td>{{ $expense->created_at->format('d M, Y') }}</td>
        </tr>
        <tr>
            <th>Note</th>
            <td>{{ $expense->note ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td>৳ {{ number_format($expense->total_amount, 2) }}</td>
        </tr>
    </table>

    <h5 class="mt-3 mb-2 fw-bold text-primary">Expense Details</h5>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Category</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $sl=1; @endphp
            @foreach($expense->details as $detail)
                <tr>
                    <td>{{ $sl++ }}</td>
                    <td>{{ $detail->category->name ?? '-' }}</td>
                    <td>{{ $detail->product->name ?? '-' }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->unit }}</td>
                    <td>{{ number_format($detail->price, 2) }}</td>
                        <td>@if($detail->unit!='Gram' )
                            {{ number_format($detail->quantity * $detail->price, 2) }}
                            @else
                            {{$detail->price}}
                            @endif
                                </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
