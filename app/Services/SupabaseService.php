<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    protected $url;
    protected $key;

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        $this->key = config('services.supabase.key');
    }

    public function signup($email, $password, $metadata = [])
    {
        $response = Http::withHeaders([
            'apikey' => $this->key,
            'Content-Type' => 'application/json',
        ])->post("{$this->url}/auth/v1/signup", [
            'email' => $email,
            'password' => $password,
            'user_metadata' => array_merge(['role' => 'company_user'], $metadata),
        ]);

        if ($response->failed()) {
            throw new \Exception('Erreur lors de l’inscription : ' . $response->json('error_description', 'Erreur inconnue'));
        }

        return $response->json();
    }

    public function login($email, $password)
    {
        $response = Http::withHeaders([
            'apikey' => $this->key,
            'Content-Type' => 'application/json',
        ])->post("{$this->url}/auth/v1/token?grant_type=password", [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->failed()) {
            throw new \Exception('Échec de la connexion : ' . $response->json('error_description', 'Erreur inconnue'));
        }

        return $response->json();
    }

    public function logout($token)
    {
        $response = Http::withHeaders([
            'apikey' => $this->key,
            'Authorization' => "Bearer {$token}",
        ])->post("{$this->url}/auth/v1/logout");

        return $response->successful();
    }

    public function getUser($token)
    {
        $response = Http::withHeaders([
            'apikey' => $this->key,
            'Authorization' => "Bearer {$token}",
        ])->get("{$this->url}/auth/v1/user");

        if ($response->failed()) {
            throw new \Exception('Échec de la récupération de l’utilisateur');
        }

        return $response->json();
    }

    public function refreshToken($refreshToken)
    {
        $response = Http::withHeaders([
            'apikey' => $this->key,
            'Content-Type' => 'application/json',
        ])->post("{$this->url}/auth/v1/token?grant_type=refresh_token", [
            'refresh_token' => $refreshToken,
        ]);

        if ($response->failed()) {
            throw new \Exception('Échec du rafraîchissement du token');
        }

        return $response->json();
    }

    public function syncProfile($userData, $token)
    {
        $profileData = [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'role' => $userData['user_metadata']['role'] ?? 'company_user',
            'company_id' => $userData['user_metadata']['company_id'] ?? null,
        ];

        Http::withHeaders([
            'apikey' => $this->key,
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->post("{$this->url}/rest/v1/profiles", $profileData);
    }
}