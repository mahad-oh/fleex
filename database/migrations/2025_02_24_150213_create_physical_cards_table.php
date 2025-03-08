<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $table = 'physical_cards';
        Schema::create($table, function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('voucher_id');
            $table->timestamp('printed_at')->nullable();
            $table->bigInteger('distributor_id')->nullable();
            $table->timestamp('distributed_at')->nullable();

            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('set null');
        });
        // Activer RLS pour la table companies
        //DB::statement("ALTER TABLE $table ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        Schema::dropIfExists('physical_cards');
    }
};