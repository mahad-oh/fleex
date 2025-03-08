<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
   /* 
        DB::statement("CREATE OR REPLACE VIEW public.users AS
            SELECT 
                id,
                NULL::text AS name,
                email,
                confirmed_at AS email_verified_at,
                raw_user_meta_data->>'password' AS password,
                NULL::text AS remember_token,
                created_at,
                updated_at,
                raw_user_meta_data->>'role' AS role,
                raw_user_meta_data->>'company_id' AS company_id
            FROM auth.users;
        ");*/
       Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        // Activer RLS sur la vue (optionnel, selon vos besoins)
        //DB::statement("ALTER VIEW public.users ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        // Supprimer la vue
        //DB::statement("DROP VIEW IF EXISTS public.users;");
        Schema::dropIfExists('personal_access_tokens');
    }
};