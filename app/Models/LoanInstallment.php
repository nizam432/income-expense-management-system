<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanInstallment extends Model
{
    protected $fillable = [
        'loan_id',
        'installment_no',
        'amount_paid',
        'paid_date',
        'payment_method',
        'note'
    ];

    public function loan() {
        return $this->belongsTo(Loan::class);
    }
}
