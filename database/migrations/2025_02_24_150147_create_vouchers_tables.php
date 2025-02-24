<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $table = 'vouchers';
        Schema::create($table, function (Blueprint $table) {
            $table->uuid('uuid')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('company_id');
            $table->binary('code_encrypted'); // BYTEA pour le code chiffré
            $table->string('type')->comment('physical, electronic');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('active')->comment('active, inactive, redeemed');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->default(DB::raw('NOW()'));

            $table->foreign('company_id')->references('uuid')->on('companies')->onDelete('cascade');
        });
        // Activer RLS pour la table companies
        //DB::statement("ALTER TABLE $table ENABLE ROW LEVEL SECURITY;");
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};