<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use App\Models\Project;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
   public function index(){
    $projects= Project::orderBy('created_at','DESC')->get();
    return response()->json([
        'status'=>true,
         'data'=>$projects
     ]);

   }
   public function store(Request $request){

    $validator= Validator::make($request->all(),[
        'title' => 'required',
        'slug' => 'required|unique:projects,slug',
        'short_desc' => 'required', // Ajoutez cette ligne
        'content' => 'required', // Ajoutez cette ligne
        'construction_type' => 'required', // Ajoutez cette ligne
        'location' => 'required', // Ajoutez cette ligne
        'sector' => 'required', // Ajoutez cette ligne
        'status' => 'required|boolean', // Ajustez selon vos beso
    ]);

    if($validator->fails()){
          

        return response()->json([
            'status'=>false,
             'errors'=>$validator->errors()
         ]);
    }
   $project=new Project();
       $project->title =$request->title;
       $project->short_desc = $request->short_desc;
       $project->slug =Str::slug($request->slug);
       $project->content =$request->content;
       $project->construction_type =$request->construction_type;
       $project->sector =$request->sector;
       $project->status =$request->status;
       $project->location =$request->location;
       $project->save();


       if($request->imageId >0){
            
        $tempImage= TempImage::find($request->imageId);
        if($tempImage!=null){
            $extArray=explode( '.',$tempImage->name);
            $ext=last($extArray);
            $fileName=strtotime('now').$project->id. '.' .$ext;


            //create slarge thumnail
            $sourcePath=public_path('uploads/temp'.$imageName->name);
            $destPath =public_path('uploads/temp/thumb'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image->$manager->read($destPath);
            $image = scaleDown(1200);
            $image->save($destPath);

             //create small thumnail
             $sourcePath=public_path('uploads/temp'.$imageName->name);
             $destPath =public_path('uploads/projects/small'.$imageName);
             $manager = new ImageManager(Driver::class);
             $image->$manager->read($destPath);
             $image = coverDown(500,600);
             $image->save($destPath);

             $project->image=$fileName;
             $project->save();
             

             }
            }

       return response()->json([
        'status'=>true,
         'message'=>'project create successfully'
     ]);
   }

   public function update($id,Request $request){

    $project= Project::find($id);
  
    $validator= Validator::make($request->all(),[
        'title' => 'required',
        'slug' => 'required|unique:projects,slug'.$id.',id',
        'short_desc' => 'required', // Ajoutez cette ligne
        'content' => 'required', // Ajoutez cette ligne
        'construction_type' => 'required', // Ajoutez cette ligne
        'location' => 'required', // Ajoutez cette ligne
        'sector' => 'required', // Ajoutez cette ligne
        'status' => 'required|boolean', // Ajustez selon vos beso
    ]);

   
     $project->title =$request->title;
       $project->short_desc = $request->short_desc;
       $project->slug =Str::slug($request->slug);
       $project->content =$request->content;
       $project->construction_type =$request->construction_type;
       $project->sector =$request->sector;
       $project->status =$request->status;
       $project->location =$request->location;
       $project->save();

       
       if($request->imageId >0){
        $oldImage=$project->image;
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

             $project->image=$fileName;
             $project->save();

             
        }

        if($oldImage!= ''){
            File::delete(public_path('uploads/service/large/'.$oldImage));
            File::delete(public_path('uploads/service/small/'.$oldImage));

         }
    }
   

    return response()->json([
        'status' => true,
        'message' => 'Service updated successfully'
    ]);
}

public function destroy($id)
{
    $project=Project::find($id);
    if ($project == null) {
        return response()->json([
            'status' => false,
            'message' => 'Service not found'
        ]);
    }

    $project->delete();

    return response()->json([
        'status' => true,
        'message' => 'project delete successfully'
    ]);

   
}


/**
 * Display the specified resource.
 */
public function show($id)
{
    \Log::info("Request received for project ID: " . $id); // Log l'ID reçu

    $project = Project::find($id);
    if ($project == null) {
        \Log::info("Project not found for ID: " . $id); // Log si le projet n'est pas trouvé
        return response()->json([
            'status' => false,
            'message' => 'Project not found'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => $project
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
}


