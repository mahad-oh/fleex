<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4|max:50',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $metadata = ['role' => 'company_admin'];
            $authData = $this->supabase->signup($request->email, $request->password, $metadata);

            //Syncronisation Supabase Auth et Breeze
            User::where('email','=',$authData['email'])->firstOr(function() use ($request,$authData){
                $newUser = new User;

                $newUser->id = $authData['id'];
                $newUser->name = $request->name;
                $newUser->email = $request->email;
                $newUser->password = Hash::make($request->password);

                $newUser->save();
            });

            return response()->json([
                'message' => 'Inscription réussie, veuillez vérifier votre email',
                'user_metadata' => $authData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            
            $authData = $this->supabase->login($request->email, $request->password);
            
            // Récupérer l’utilisateur depuis la vue public.users
            $user = User::where('email','=',$authData['user']['email'])->firstOrFail();
            // Générer un token Sanctum
            $token = $user->createToken('api-token')->plainTextToken;
            Cache::add($token,$authData['access_token']);
            return response()->json([
                'access_token' => $token, // Sanctum token
                'supabase_token' => $authData['access_token'], // Supabase JWT
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role, // Via getRoleAttribute
                    'company_id' => $user->company_id,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        $supabase_token = Cache::pull($token);
        
        $request->user()->currentAccessToken()->delete();
        $this->supabase->logout($supabase_token);

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}