<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class ExpensesExport implements FromView, WithTitle
{
    use Exportable;

    protected $from;
    protected $to;


    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Excel Sheet Title
     */
    public function title(): string
    {
        return 'Expense Report';
    }

    /**
     * Return a view to render into Excel or PDF
     */
    public function view(): View
    {
        $query = Expense::with('details.product')
            ->when($this->from, fn($q) => $q->whereDate('expense_date', '>=', $this->from))
            ->when($this->to, fn($q) => $q->whereDate('expense_date', '<=', $this->to))
            ->orderBy('expense_date', 'desc');

        $expenses = $query->get();

        return view('exports.expenses', [
            'expenses' => $expenses
        ]);
    }
}
