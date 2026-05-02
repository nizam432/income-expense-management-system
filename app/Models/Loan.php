<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'provider_id','loan_title','loan_amount','interest_rate',
        'total_payable','installment_type','installment_amount',
        'start_date','end_date','status','notes'
    ];

    public function provider()
    {
        return $this->belongsTo(LoanProvider::class);
    }

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }
}
