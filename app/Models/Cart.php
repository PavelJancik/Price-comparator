<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Cart
{
    public function createNewList(){
        return DB::table('shopping_list')->insertGetId([
            'name' => 'Můj nákupní seznam',
            'creation_date' => date("Y-m-d")
        ]);
    }

    public function addProduct($cart_id, $product_id) {
        if (DB::table('shopping_list_product')
            ->where('shopping_list_id', $cart_id)
            ->where( 'product_id', $product_id)
            ->doesntExist()){
            DB::table('shopping_list_product')->insert([
                'shopping_list_id' => $cart_id,
                'product_id' => $product_id
            ]);
        }
    }

    public function removeProduct($cart_id, $product_id){
        DB::table('shopping_list_product')
            ->where('shopping_list_id', $cart_id)
            ->where( 'product_id', $product_id)
            ->delete();
    }

    public function getListById($cart_id){
        return DB::table('shopping_list')
            ->select('product.name as name', 'img_url', 'description')
            ->join('shopping_list_product', 'shopping_list_product.shopping_list_id', '=', 'shopping_list.shopping_list_id')
            ->join('product', 'shopping_list_product.product_id', '=', 'product.product_id')
            ->where('shopping_list.shopping_list_id', $cart_id)
            ->get();
    }
}
