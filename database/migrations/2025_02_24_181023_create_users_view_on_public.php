<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Créer la vue public.users basée sur auth.users
        DB::statement("
            CREATE OR REPLACE VIEW public.users AS
            SELECT 
                id, -- UUID, différent du integer par défaut de Laravel
                NULL::text AS name, -- Pas de name dans auth.users, valeur par défaut NULL
                email,
                confirmed_at AS email_verified_at, -- Correspondance avec email_verified_at
                raw_user_meta_data->>'password' AS password, -- Si stocké dans user_metadata, sinon NULL
                NULL::text AS remember_token, -- Pas utilisé dans Supabase Auth
                created_at,
                updated_at
            FROM auth.users;
        ");

        // Activer RLS sur la vue (optionnel, selon vos besoins)
        //DB::statement("ALTER VIEW public.users ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        // Supprimer la vue
        DB::statement("DROP VIEW IF EXISTS public.users;");
    }
};