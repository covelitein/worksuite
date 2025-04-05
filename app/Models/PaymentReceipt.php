<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $table = 'payment_receipts';

    protected $fillable = [
        'receipt_num',
        'project_id',  
        'project_name',
        'amount_paid',
        'payment_method',
        'payment_date',
        'paid_by',
        'received_by',
        'notes'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by', 'name');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by', 'name');
    }

    public function generateReceiptNumber()
    {
        $prefix = 'RCPT-';
        $date = now()->format('Ymd');
        $random = Str::upper(Str::random(4));
        
        $number = $prefix . $date . '-' . $random;
        
        while (self::where('receipt_num', $number)->exists()) {
            $random = Str::upper(Str::random(4));
            $number = $prefix . $date . '-' . $random;
        }
        
        return $number;
    }
}