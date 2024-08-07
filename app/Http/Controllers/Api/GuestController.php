<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    public function store(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'phone' => 'required|max:9',
                'email' => 'required|string|email|max:255|unique:guests',
                // 'response' => 'nullable|in:asistire,no asistire',
                // 'memory_text' => 'nullable|string',
                // 'memory_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx',
                'dish' => 'nullable|in:pollo,cerdo,res',
                // 'table_id' => 'nullable|exists:tables,id',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        // if ($request->hasFile("portada")) {
        //     $path = Storage::putFile("memories", $request->file("memory_file"));
        //     $request->request->add(["imagen" => $path]);
        // }

        // Crear invitado
        $guest = Guest::create($request->all());

        // if ($request->response) {
        //     if ($request->response == 'asistire' && $request->table_id) {
        //         $table = Table::find($request->table_id);

        //         if ($table->capacity_actual < $table->table_capacity) {
        //             $guest->table_id = $request->table_id;
        //             $guest->save();

        //             $table->capacity_actual++;
        //             $table->save();
        //         } else {
        //             return response()->json(['error' => 'La mesa está llena'], 400);
        //         }
        //     }
        // }

        return response()->json(['message' => 'Invitado registrado exitosamente', 'guest' => $guest], 201);
    }

    public function index()
    {
        return Guest::all();
    }

    public function show($id)
    {
        $guest = Guest::with('table')->findOrFail($id);
        return response()->json($guest);
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:guests,email,' . $guest->id,
            'response' => 'sometimes|required|in:asistire,no asistire,dejar recuerdo',
            'memory_text' => 'nullable|string',
            'memory_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx',
            'dish' => 'nullable|in:pollo,cerdo,res',
            'table_id' => 'nullable|exists:tables,id',
        ]);

        if ($request->hasFile('memory_file')) {
            $validated['memory_file'] = $request->file('memory_file')->store('memories');
        }

        // Verificar si el invitado ya está asignado a una mesa diferente
        if ($guest->table_id && $guest->table_id !== $validated['table_id']) {
            return response()->json(['error' => 'El invitado ya está asignado a una mesa diferente'], 400);
        }

        if (isset($validated['table_id']) && $validated['table_id'] !== $guest->table_id) {
            $newTable = Table::find($validated['table_id']);
            $currentTable = Table::find($guest->table_id);

            // Verificar si la nueva mesa está llena
            if ($newTable->current_occupancy < $newTable->seat_capacity) {
                $newTable->current_occupancy++;
                $newTable->save();

                if ($currentTable) {
                    $currentTable->current_occupancy--;
                    $currentTable->save();
                }

                $guest->table_id = $validated['table_id'];
            } else {
                return response()->json(['error' => 'La nueva mesa está llena'], 400);
            }
        }

        $guest->update($validated);

        return response()->json(['message' => 'Invitado actualizado exitosamente', 'guest' => $guest]);
    }
}
