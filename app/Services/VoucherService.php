<?php

namespace App\Services;

use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class VoucherService
{
    public static function activateCard(int $serial_num,string $type,int $amount,int $company_id, int $expiration_time = 12){

        $expration = $expiration_time;

        $voucher = Voucher::where([
            ['serial_num','=',$serial_num],
            ['status','=','inactive']
        ])->whereNull('code_hashed');

        if($voucher->first() == null) 
            throw new \Exception("Le code voucher est inexistant ou déjà active."); 

        DB::select('select activate_voucher(:serial_num,:type,:amount,:company_id,:expiration_time)',[
            'serial_num' => $serial_num,
            'type' => $type,
            'amount' => $amount,
            'company_id' => $company_id,
            'expiration_time' => $expration,
        ]);
    }


    public static function redeem(string $voucher_code){
        $voucher = DB::table('decrypted_vouchers')->where('decrypted_code_encrypted',$voucher_code)->first();
        if($voucher->status != 'active')
            throw new \Exception();
        

        $res = DB::select('select verify_voucher_hmac(:code)',[
            'code' => $voucher_code,
        ])[0]->verify_voucher_hmac;
        
        if(!$res)
            throw new \Exception('Ce code voucher est invalide');


        // redeeming the voucher code (use event instead)
        $voucher_redemeed = Voucher::where('serial_num',$voucher->serial_num);
        $voucher_redemeed->status = 'redemeed';
        $voucher_redemeed->save();

        return [
            'code' => $voucher->decrypted_code_encrypted,
            'type' => $voucher->type,
            'amount' => $voucher->amount,
        ];
    }

    public static function check(string $serial_num): bool{
        if(!is_numeric($serial_num))
            throw new \Exception('Le numéro de série doit être de chiffre.');

        $voucher = DB::table('vouchers')->where('serial_num',$serial_num)->firstOrFail('status');

        return $voucher->status == 'active';
    }
}