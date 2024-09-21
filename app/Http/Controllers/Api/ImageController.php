<?php
namespace App\Http\Controllers\Api;

use App\Models\Image; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ImageController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'comment' => 'nullable|string|max:255',
            ]);

            
            if ($request->file('image')) {
                $path = $request->file('image')->store('images', 'public'); 
            }

          
            $image = Image::create([
                'image_path' => '/storage/' . $path, 
                'comment' => $request->input('comment'),
            ]);

            return response()->json([
                'message' => 'Imagen y comentario subidos exitosamente.',
                'data' => $image,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'messages' => $e->validator->errors(),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Error al guardar en la base de datos',
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error inesperado',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // obtener todas las imágenes
    public function index()
    {
        $images = Image::all();

        return response()->json($images);
    }
}
