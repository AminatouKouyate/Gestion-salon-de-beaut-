<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 'quantity', 'alert_quantity'
    ];

    public function isLow(): bool
    {
        return $this->quantity <= $this->alert_quantity;
    }
}
