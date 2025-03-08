<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $table = 'voucher_redemptions';
        Schema::create($table, function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('voucher_id');
            $table->bigInteger('wallet_id');
            $table->timestamp('redeemed_at')->default(DB::raw('NOW()'));

            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
        // Activer RLS pour la table companies
        //DB::statement("ALTER TABLE $table ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        Schema::dropIfExists('voucher_redemptions');
    }
};