<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $table = 'wallets';
        Schema::create($table, function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('company_id');
            $table->string('wallet_id')->unique();
            $table->timestamp('created_at')->default(DB::raw('NOW()'));

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        // Activer RLS pour la table companies
        //DB::statement("ALTER TABLE $table ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};