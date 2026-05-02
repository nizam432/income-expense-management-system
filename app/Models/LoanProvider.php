<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProvider extends Model
{
    protected $fillable = [
        'name', 'provider_type', 'account_number'
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'provider_id');
    }
}
