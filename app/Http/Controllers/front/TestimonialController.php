<?php

namespace App\Http\Controllers\front;

use App\Models\Testimonials;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TestimonialCntroller extends Controller
{
    public function latesttestimonials(Request $request){
        // retourne les derniers testimonials
        $particles= Testimonial:: orderBy('created_at','DESC')
        ->where('status',1)
        ->limit($request->limit)
        ->get();

        return response()->json([
            'status' =>true,
            'data'=>$testimonials
        ]);
    }
    //retourne tous les testimonials
    public function index(){
        $testimonials=Testimonial::orderBy('created_at','Desc')
        ->where('status',1)
        ->get();
        return response()->json([
            'status' =>true,
            'data'=>$testimonials
        ]);
    }
}
