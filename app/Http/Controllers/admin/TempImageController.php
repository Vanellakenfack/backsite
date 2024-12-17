<?php
namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImageController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:png,jpg,jpeg,gif|max:2048'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors('image')
            ]);
        }
    
        $image = $request->file('image'); // Récupérer le fichier
    
        if ($image) {
            $ext = $image->getClientOriginalExtension();
            $imageName = strtotime('now') . '.' . $ext;
    
            // Sauvegarder l'image dans la base de données
            $model = new TempImage();
            $model->name = $imageName;
            $model->save();
    
            // Sauvegarder l'image en local
            $image->move(public_path('uploads/temp'), $imageName);
    
            // Créer le thumbnail
            $sourcePath = public_path('uploads/temp/' . $imageName);
            $destPath = public_path('uploads/thumb/' . $imageName);
    
            // Créer l'instance de ImageManager avec le driver
            try {
                $manager = new ImageManager('gd'); // Correction ici
                $img = $manager->make($sourcePath)->resize(300, 300); // Redimensionner l'image
                $img->save($destPath); // Sauvegarder le thumbnail
    
                return response()->json([
                    'status' => true,
                    'data' => $model,
                    'message' => 'Image uploaded successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
        }
    
        return response()->json([
            'status' => false,
            'message' => 'No image provided'
        ]);
    }
}