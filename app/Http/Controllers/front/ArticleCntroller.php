<?php

namespace App\Http\Controllers\front;

use App\Models\Article;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ArticleCntroller extends Controller
{
    public function latestarticles(Request $request){
        // retourne les derniers articles
        $particles= Article :: orderBy('created_at','DESC')
        ->where('status',1)
        ->limit($request->limit)
        ->get();

        return response()->json([
            'status' =>true,
            'data'=>$articles
        ]);
    }
    //retourne tous les articles
    public function index(){
        $articles=Article::orderBy('created_at','Desc')
        ->where('status',1)
        ->get();
        return response()->json([
            'status' =>true,
            'data'=>$articles
        ]);
    }
}
