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
            $table->uuid('uuid')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('voucher_id');
            $table->uuid('wallet_id');
            $table->timestamp('redeemed_at')->default(DB::raw('NOW()'));

            $table->foreign('voucher_id')->references('uuid')->on('vouchers')->onDelete('cascade');
            $table->foreign('wallet_id')->references('uuid')->on('wallets')->onDelete('cascade');
        });
        // Activer RLS pour la table companies
        //DB::statement("ALTER TABLE $table ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        Schema::dropIfExists('voucher_redemptions');
    }
};