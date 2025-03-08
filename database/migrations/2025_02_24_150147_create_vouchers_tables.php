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
            
            $table->id()->primary();
            $table->string('serial_num')->unique();
            $table->text('code_encrypted')->unique()->comment('Encrypted voucher code'); // BYTEA pour le code chiffré
            $table->binary('code_hashed')->unique()->nullable(true)->comment('Hashed voucher code');
            $table->enum('type',['physical','electronic'])->default('physical')->comment('physical, electronic');
            $table->decimal('amount', 10, 2)->nullable(true);
            $table->enum('status',['active','inactive','redemeed'])->default('inactive')->comment('active, inactive, redeemed');
            $table->bigInteger('company_id')->nullable(true);
            $table->timestamp('expires_at')->nullable();
            $table->uuid('key_id')->references('pgsoduim.key(id)')->default(DB::raw('(pgsodium.create_key()).id'));
            $table->binary('nonce')->default(DB::raw('pgsodium.crypto_aead_det_noncegen()'));
            $table->timestamp('created_at')->default(DB::raw('NOW()'));

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        
            
        });
        // Activer RLS pour la table companies
        //DB::statement("ALTER TABLE $table ENABLE ROW LEVEL SECURITY;");
        
        //Activer SECURITY LABEL of 
        DB::statement("SECURITY LABEL FOR pgsodium
            ON COLUMN $table.code_encrypted
            IS 'ENCRYPT WITH KEY COLUMN key_id ASSOCIATED (id) NONCE nonce'");

    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
