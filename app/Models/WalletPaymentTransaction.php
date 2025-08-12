<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionStatus;
use App\Enums\TransactionPaymentStatus;

class WalletPaymentTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'payment_status' => TransactionPaymentStatus::class,
        'status' => TransactionStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
