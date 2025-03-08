<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Services\SupabaseService;
use App\Http\Resources\VoucherCollection;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\VoucherService;

class VoucherController extends Controller
{
    public function index(){
        
    }

    public function activate(Request $request)
    {
        $request->validate([
            'serial_num' => 'required',
            'type' => 'required|alpha:ascii',
            'amount' => 'required|numeric',
            'company_id' => 'required|integer',
            'expiration_time' => 'integer'
        ]);
        $expration = $request->expiration_time ?? 12;

        try{
            VoucherService::activateCard(
                $request->serial_num,
                $request->type,
                $request->amount,
                $request->company_id,
                12
            );
            return response()->json(['message' => "Le voucher a bien été activé !"],200);
        } catch (\Exception $e){
            return response()->json([
                "error" => $e->getMessage()
            ],404);
        }

    }

    public function redeem(Request $request){
        $request->validate([
            'code' => 'required|integer|min:12'
        ]);
        
        try{
            $voucher_info = VoucherService::redeem($request->code);
            
            return response()->json([
                'message' => "Ce voucher ne  sera plus utilisable.",
                'voucher_info' => [$voucher_info]
            ],200);
        } catch (\Exception $e){
            return response()->json([
                "error" => "Le code voucher est invalide.",
                "message" => $e->getMessage()
            ],404);
        }
    }

    public function check(Request $request){
        $request->validate([
            'serial_num' => 'required'
        ]);
        
        try{
            $voucherIsActive = VoucherService::check($request->serial_num);

            if(!$voucherIsActive)
                throw new \Exception();

            return response()->json([
                'message' => 'Ce voucher est encore valide.'
            ]);
        }catch(\Exception $e){
            return response()->json([
                "error" => "Ce code voucher est introuvable.",
                //"message" => $e->getMessage()
            ],404);
        }
    }
}
