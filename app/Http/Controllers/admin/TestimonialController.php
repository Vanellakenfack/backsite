<?php

namespace App\Http\Controllers\admin;
use App\Models\Testimonial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TestimonialController extends Controller
{

    public function index()
    {
        $testimonials=Testimonial::orderBy('created_at','DESC')->get();
        return response()->json([
            'status'=>true,
             'data'=>$testimonials
         ]);
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'testimonial'=>'required',
            
        ]);
        if($validator->fails()){
          

            return response()->json([
                'status'=>false,
                 'errors'=>$validator->errors()
             ]);
        }
       
        $testimonial=new Testimonial();
        $testimonial->testimonial =$request->testimonial ;
        $testimonial->citation=$request->citation;
        $testimonial->save();
        return response()->json([
            'status'=>true,
             'message'=>'testimonial  create successfully'
         ]);

         //save temp image here
        if($request->imageId >0){
            
            $tempImage= TempImage::find($request->imageId);
            if($tempImage!=null){
                $extArray=explode( '.',$tempImage->name);
                $ext=last($extArray);
                $fileName=strtotime('now').$testimonial->id. '.' .$ext;
    
    
                //create slarge thumnail
                $sourcePath=public_path('uploads/temp'.$imageName->name);
                $destPath =public_path('uploads/temp/thumb'.$imageName);
                $manager = new ImageManager(Driver::class);
                $image->$manager->read($destPath);
                $image = scaleDown(1200);
                $image->save($destPath);
    
                 //create small thumnail
                 $sourcePath=public_path('uploads/temp'.$imageName->name);
                 $destPath =public_path('uploads/testimonial/small'.$imageName);
                 $manager = new ImageManager(Driver::class);
                 $image->$manager->read($destPath);
                 $image = coverDown(500,600);
                 $image->save($destPath);
    
                 $testimonial->image=$fileName;
                 $testimonial->save();
    
                 
            }
        }
       
    
        return response()->json([
            'status' => true,
            'message' => 'Testimonial updated successfully'
        ]);
    }
    
    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial == null) {
            return response()->json([
                'status' => false,
                'message' => 'Testimonial not found'
            ]);
        }
    
        $testimonial->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Testimonial delete successfully'
        ]);
    
       
    }
    
       
    


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial == null) {
            return response()->json([
            'status' => false,
            'message' => 'Testimonial not found'
            ]);
        }

        return response()->json([
            'status' => true ,
            'data' => $testimonial
        ]);
    

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $testimonial = Testimonial::find($id);
    if ($testimonial == null) {
        return response()->json([
            'status' => false,
            'message' => 'Testimonial not found'
        ]);
    }

    $validator = Validator::make($request->all(), [
        'testimonial' => 'required',
       
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    // Mise à jour de l'objet testimonial existant
    $testimonial->testimonial =$request->testimonial ;
    $testimonial->citation=$request->citation;
    $testimonial->save();

    //save temp here
    if($request->imageId >0){
        $oldImage=$testimonial->image;
        $tempImage= TempImage::find($request->imageId);
        if($tempImage!=null){
            $extArray=explode( '.',$tempImage->name);
            $ext=last($extArray);
            $fileName=strtotime('now').$testimonial->id. '.' .$ext;


            //create slarge thumnail
            $sourcePath=public_path('uploads/temp'.$imageName->name);
            $destPath =public_path('uploads/temp/thumb'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image->$manager->read($destPath);
            $image = scaleDown(1200);
            $image->save($destPath);

             //create small thumnail
             $sourcePath=public_path('uploads/temp'.$imageName->name);
             $destPath =public_path('uploads/testimonials/small'.$imageName);
             $manager = new ImageManager(Driver::class);
             $image->$manager->read($destPath);
             $image = coverDown(500,600);
             $image->save($destPath);

             $testimonial->image=$fileName;
             $testimonial->save();

             if($oldImage!= ''){
                File::delete(public_path('uploads/testimonial/large/'.$oldImage));
                File::delete(public_path('uploads/testimonial/small/'.$oldImage));

             }
        }
    }
   

    return response()->json([
        'status' => true,
        'message' => 'Testimonial updated successfully'
    ]);
}

public function delete($id)
{
    $testimonial = Testimonial::find($id);
    if ($testimonial == null) {
        return response()->json([
            'status' => false,
            'message' => 'Testimonial not found'
        ]);
    }

    $testimonial->delete();

    return response()->json([
        'status' => true,
        'message' => 'Testimonial delete successfully'
    ]);

   
}
}
