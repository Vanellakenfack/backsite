<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthentificationController extends Controller
{
    public function authentificate(Request $request)
    {
        // Appliquer la validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Récupérer les informations d'identification
        $credentials = $request->only('email', 'password');

        // Tenter l'authentification
        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Récupérer l'utilisateur authentifié
            $token = $user->createToken('token')->plainTextToken; // Créer le token

            return response()->json([
                'status' => true,
                'token' => $token, // Retourner le token
                'id' => $user->id // Retourner l'ID de l'utilisateur
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => 'Either email or password is incorrect'
            ], 401);
        }

    }

    public function logout(Request $request)
{
    // Assurez-vous que l'utilisateur est authentifié
    $user = Auth::user();

    if ($user) {
        // Supprimer tous les tokens de l'utilisateur
        $user->tokens()->delete(); 

        return response()->json([
            'status' => true,
            'message' => 'Logout successful'
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'User not authenticated'
    ], 401);
}
}