<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Ajout de l'importation de Str
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $services = Service::orderBy('created_at', 'DESC')->get();
            return response()->json([
                'status' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'title'=>'required',
            'slug'=>'required|unique:services,slug'
        ]);
        if($validator->fails()){
          

            return response()->json([
                'status'=>false,
                 'errors'=>$validator->errors()
             ]);
        }
       
        $model=new Service();
        $model->title =$request->title;
        $model->short_desc =$request->short_desc;
        $model->slug =Str::slug($request->slug);
        $model->content =$request->content;
        $model->status =$request->status ;
        $model->save();
        return response()->json([
            'status'=>true,
             'message'=>'service  create successfully'
         ]);

         //save temp image here
        if($request->imageId >0){
            
            $tempImage= TempImage::find($request->imageId);
            if($tempImage!=null){
                $extArray=explode( '.',$tempImage->name);
                $ext=last($extArray);
                $fileName=strtotime('now').$model->id. '.' .$ext;
    
    
                //create slarge thumnail
                $sourcePath=public_path('uploads/temp'.$imageName->name);
                $destPath =public_path('uploads/temp/thumb'.$imageName);
                $manager = new ImageManager(Driver::class);
                $image->$manager->read($destPath);
                $image = scaleDown(1200);
                $image->save($destPath);
    
                 //create small thumnail
                 $sourcePath=public_path('uploads/temp'.$imageName->name);
                 $destPath =public_path('uploads/service/small'.$imageName);
                 $manager = new ImageManager(Driver::class);
                 $image->$manager->read($destPath);
                 $image = coverDown(500,600);
                 $image->save($destPath);
    
                 $model->image=$fileName;
                 $model->save();
    
                 
            }
        }
       
    
        return response()->json([
            'status' => true,
            'message' => 'Service updated successfully'
        ]);
    }
    
    public function destroy($id)
    {
        $service = Service::find($id);
        if ($service == null) {
            return response()->json([
                'status' => false,
                'message' => 'Service not found'
            ]);
        }
    
        $service->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Service delete successfully'
        ]);
    
       
    }
    
       
    


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $service = Service::find($id);
        if ($service == null) {
            return response()->json([
            'status' => false,
            'message' => 'Service not found'
            ]);
        }

        return response()->json([
            'status' => true ,
            'data' => $service
        ]);
    

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $service = Service::find($id);
    if ($service == null) {
        return response()->json([
            'status' => false,
            'message' => 'Service not found'
        ]);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'slug' => 'required|unique:services,slug,' . $id . ',id',
        'status' => 'required|boolean', // Validation pour le champ status
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    // Mise à jour de l'objet service existant
    $service->title = $request->title;
    $service->short_desc = $request->short_desc;
    $service->slug = Str::slug($request->slug);
    $service->content = $request->content;
    $service->status = $request->status; // Assurez-vous que status n'est pas null
    $service->save();

    //save temp here
    if($request->imageId >0){
        $oldImage=$service->image;
        $tempImage= TempImage::find($request->imageId);
        if($tempImage!=null){
            $extArray=explode( '.',$tempImage->name);
            $ext=last($extArray);
            $fileName=strtotime('now').$service->id. '.' .$ext;


            //create slarge thumnail
            $sourcePath=public_path('uploads/temp'.$imageName->name);
            $destPath =public_path('uploads/temp/thumb'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image->$manager->read($destPath);
            $image = scaleDown(1200);
            $image->save($destPath);

             //create small thumnail
             $sourcePath=public_path('uploads/temp'.$imageName->name);
             $destPath =public_path('uploads/services/small'.$imageName);
             $manager = new ImageManager(Driver::class);
             $image->$manager->read($destPath);
             $image = coverDown(500,600);
             $image->save($destPath);

             $service->image=$fileName;
             $service->save();

             if($oldImage!= ''){
                File::delete(public_path('uploads/service/large/'.$oldImage));
                File::delete(public_path('uploads/service/small/'.$oldImage));

             }
        }
    }
   

    return response()->json([
        'status' => true,
        'message' => 'Service updated successfully'
    ]);
}

public function delete($id)
{
    $service = Service::find($id);
    if ($service == null) {
        return response()->json([
            'status' => false,
            'message' => 'Service not found'
        ]);
    }

    $service->delete();

    return response()->json([
        'status' => true,
        'message' => 'Service delete successfully'
    ]);

   
}
}