<?php

namespace App\Http\Controllers\front;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function latestprojects(Request $request){
        $projects= Project:: orderBy('created_at','DESC')
        ->where('status',1)
        ->get();

        return response()->json([
            'status' =>true,
            'data'=>$projects
        ]);
    }
    public function index(){
        $projects=Project::orderBy('created_at','Desc')
        ->where('status',1)
        ->get();
        return response()->json([
            'status' =>true,
            'data'=>$projects
        ]);
    }
}
