<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'table_capacity',
        'capacity_actual',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
