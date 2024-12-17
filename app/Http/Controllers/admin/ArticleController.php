<?php


namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Ajout de l'importation de Str
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ArticleController extends Controller
{// affiher tout les articles
    public function index()
    {
        $articles= Article::orderBy('created_at','DESC')->get();
        return response()->json([
            'status'=>true,
             'data'=>$articles
         ]);
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
            'slug'=>'required|unique:articles,slug'
        ]);
        if($validator->fails()){
          

            return response()->json([
                'status'=>false,
                 'errors'=>$validator->errors()
             ]);
        }
       
        $article=new Article();
        $article->title =$request->title;
        $article->slug =Str::slug($request->slug);
        $article->author=$request->author;
        $article->content =$request->content;
        $article->status =$request->status ;
        $article->save();
        return response()->json([
            'status'=>true,
             'message'=>'article  create successfully'
         ]);

         //save temp image here
        if($request->imageId >0){
            
            $tempImage= TempImage::find($request->imageId);
            if($tempImage!=null){
                $extArray=explode( '.',$tempImage->name);
                $ext=last($extArray);
                $fileName=strtotime('now').$article->id. '.' .$ext;
    
    
                //create slarge thumnail
                $sourcePath=public_path('uploads/temp'.$imageName->name);
                $destPath =public_path('uploads/temp/thumb'.$imageName);
                $manager = new ImageManager(Driver::class);
                $image->$manager->read($destPath);
                $image = scaleDown(1200);
                $image->save($destPath);
    
                 //create small thumnail
                 $sourcePath=public_path('uploads/temp'.$imageName->name);
                 $destPath =public_path('uploads/article/small'.$imageName);
                 $manager = new ImageManager(Driver::class);
                 $image->$manager->read($destPath);
                 $image = coverDown(500,600);
                 $image->save($destPath);
    
                 $article->image=$fileName;
                 $article->save();
    
                 
            }
        }
       
    
        return response()->json([
            'status' => true,
            'message' => 'Article updated successfully'
        ]);
    }
    
    public function destroy($id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return response()->json([
                'status' => false,
                'message' => 'Article not found'
            ]);
        }
    
        $article->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Article delete successfully'
        ]);
    
       
    }
    
       
    


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return response()->json([
            'status' => false,
            'message' => 'Article not found'
            ]);
        }

        return response()->json([
            'status' => true ,
            'data' => $article
        ]);
    

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $article = Article::find($id);
    if ($article == null) {
        return response()->json([
            'status' => false,
            'message' => 'Article not found'
        ]);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'slug' => 'required|unique:articles,slug,' . $id . ',id',
        'status' => 'required|boolean', // Validation pour le champ status
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    // Mise à jour de l'objet article existant
  
    $article->title =$request->title;
    $article->slug =Str::slug($request->slug);
    $article->author=$request->auhor;
    $article->content =$request->content;
    $article->status =$request->status ;
    $article->save();

    //save temp here
    if($request->imageId >0){
        $oldImage=$article->image;
        $tempImage= TempImage::find($request->imageId);
        if($tempImage!=null){
            $extArray=explode( '.',$tempImage->name);
            $ext=last($extArray);
            $fileName=strtotime('now').$article->id. '.' .$ext;


            //create slarge thumnail
            $sourcePath=public_path('uploads/temp'.$imageName->name);
            $destPath =public_path('uploads/temp/thumb'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image->$manager->read($destPath);
            $image = scaleDown(1200);
            $image->save($destPath);

             //create small thumnail
             $sourcePath=public_path('uploads/temp'.$imageName->name);
             $destPath =public_path('uploads/articles/small'.$imageName);
             $manager = new ImageManager(Driver::class);
             $image->$manager->read($destPath);
             $image = coverDown(500,600);
             $image->save($destPath);

             $article->image=$fileName;
             $article->save();

             if($oldImage!= ''){
                File::delete(public_path('uploads/article/large/'.$oldImage));
                File::delete(public_path('uploads/article/small/'.$oldImage));

             }
        }
    }
   

    return response()->json([
        'status' => true,
        'message' => 'Article updated successfully'
    ]);
}

public function delete($id)
{
    $article = Article::find($id);
    if ($article == null) {
        return response()->json([
            'status' => false,
            'message' => 'Article not found'
        ]);
    }

    $article->delete();

    return response()->json([
        'status' => true,
        'message' => 'Article delete successfully'
    ]);

   
}
}
