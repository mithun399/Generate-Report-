<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exports\userExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Purchase;

class dataController extends Controller
{
    function index(){
        
        $api_url="https://raw.githubusercontent.com/Bit-Code-Technologies/mockapi/main/purchase.json";
        $response=Http::get($api_url);
        $data=json_decode($response->body());
      
        foreach($data as $datas){
            $datas=(array)$datas;
          
             Purchase::updateOrCreate(
                ['name'=>$datas['name']],
               
                [
                    'name'=>$datas['name'],
                    'order_no'=>$datas['order_no'],
                    'user_phone'=>$datas['user_phone'],
                    'product_code'=>$datas['product_code'],
                    'product_name'=>$datas['product_name'],
                    'product_price'=>$datas['product_price'],
                    'purchase_quantity'=>$datas['purchase_quantity'],

                ]
            );
            
            
        }
        return("data store succefully");

    }
    function retrieveData(){
        // $purchase=Purchase::orderByRaw("CAST(purchase_quantity as UNSIGNED) DESC")->limit(2)->get();
     
        return view ('generate');
    }
    public function export() 
    {
        return Excel::download(new userExport, 'purchase.xlsx');
    }
}
