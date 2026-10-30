<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const STATUSES = [
        'active' => 'Aktīvs',
        'inactive' => 'Neaktīvs',
    ];

    protected $fillable = [
        'name',
        'quantity',
        'description',
        'expiration_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'expiration_date' => 'date',
        ];
    }
}
