<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'due_date',
        'category',
        'payment_method',
        'payment_source',
        'recurrent',
        'auto_pay',
    ];

    protected $attributes = [
        'status' => Status::AWAITING_PAYMENT
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
