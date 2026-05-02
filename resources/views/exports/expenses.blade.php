<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background:#007bff;color:white;">
            <th>#</th>
            <th>Date</th>
            <th>Note</th>
            <th>Total Amount (৳)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $key => $expense)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}</td>
            <td>{{ $expense->note }}</td>
            <td>{{ number_format($expense->total_amount, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
