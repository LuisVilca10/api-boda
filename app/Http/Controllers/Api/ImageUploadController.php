<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    // Método para manejar las solicitudes GET y devolver las imágenes
    public function index()
    {
        $files = Storage::disk('public')->files('uploads');
        $fileUrls = array_map(function ($file) {
            return Storage::disk('public')->url($file);
        }, $files);

        return response()->json([
            'files' => $fileUrls,
        ]);
    }

    // Método para manejar las solicitudes POST y subir imágenes
    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'comment' => 'nullable|string'
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads', $filename, 'public');
                $uploadedFiles[] = $filename;
            }
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles,
        ]);
    }
}