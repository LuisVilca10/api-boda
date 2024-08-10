<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{

    public function index()
    {
        $tables = Table::with(['guests' => function ($query) {
            $query->select('name', 'lastname', 'table_id');
        }])->get();

        return response()->json([
            "tables" => $tables,
        ]);
    }
}
