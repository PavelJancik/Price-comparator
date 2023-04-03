<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Delivery
{
    public function updateDelivery($delivery, $product_id){
        $delivery_id = null;
        $delivery_id = DB::table('delivery')
            ->where('name', $delivery->DELIVERY_ID)
            ->where('price', $delivery->DELIVERY_PRICE)
            ->value('delivery_id');
        if (!$delivery_id) {
            $delivery_id = DB::table('delivery')->insertGetId([
                'name' => $delivery->DELIVERY_ID,
                'price' => $delivery->DELIVERY_PRICE
            ]);
        }
        if (DB::table('product_delivery')
            ->where('product_id', $product_id)
            ->where('delivery_id', $delivery_id)
            ->doesntExist())
            DB::table('product_delivery')
                ->insert([
                    'product_id' => $product_id,
                    'delivery_id' => $delivery_id
                ]);
    }

    public function getMinDeliveryPrice($product_id) {
        return DB::table('product_delivery')
            ->join('delivery', 'delivery.delivery_id', '=', 'product_delivery.delivery_id')
            ->where('product_id', $product_id)
            ->min('price');
    }
}
