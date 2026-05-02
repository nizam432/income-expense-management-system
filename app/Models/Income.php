<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    /**
     * কোন কোন ফিল্ডে mass assignment (create/update) করা যাবে তা define করা হয়েছে
     */
    protected $fillable = [
        'title',
        'amount',
        'description',
        'date',
    ];
}