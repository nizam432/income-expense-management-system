<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['expense_date','note', 'total_amount'];

    public function details()
    {
        return $this->hasMany(ExpenseDetail::class);
    }

}