<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up()
    {
        $table = 'companies';
        Schema::create($table, function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->jsonb('contact_info')->nullable();
            $table->timestamp('created_at')->default(DB::raw('NOW()'));
        });
        // Activer RLS pour la table companies
        //DB::statement("ALTER TABLE $table ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};