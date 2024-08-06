<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'email',
        'response',
        'memory_text',
        'memory_file',
        'dish',
        'table_id',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
