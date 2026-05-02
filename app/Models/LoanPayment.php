<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $fillable = [
        'loan_id','installment_id','paid_amount','paid_date',
        'payment_method','transaction_id'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function installment()
    {
        return $this->belongsTo(LoanInstallment::class);
    }
}
