<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Product
{
    public function getProductsQueryBuilder($category){
        return DB::table('product')
            ->join('product_category', 'product.product_id', '=', 'product_category.product_id')
            ->join('category', 'category.category_id', '=', 'product_category.category_id')
            ->join('product_parameter_value', 'product.product_id', '=', 'product_parameter_value.product_id')
            ->join('parameter', 'parameter.parameter_id', '=', 'product_parameter_value.parameter_id')
            ->join('value', 'value.value_id', '=', 'product_parameter_value.value_id')
            ->join('manufacturer', 'product.manufacturer_id', '=', 'manufacturer.manufacturer_id')
            ->select('product.name as name')
            ->where('category.name', '=', (string)$category)
            ->groupBy('product.name');
    }

    public function getProductId($productName){
        return DB::table('product')
            ->select('product_id')
            ->where('name', $productName)
            ->value('product_id');
    }

    public function createOrUpdateProduct($shopItem, $shop_id, $manufacturer_id){
        // if product record exist (same 'name' nad 'shop_id'), then update; else insert
        DB::table('product')->updateOrInsert(
            ['name' => $shopItem->PRODUCTNAME, 'shop_id' => $shop_id],
            ['price' => $shopItem->PRICE_VAT,
                'url' => $shopItem->URL,
                'img_url' => $shopItem->IMGURL,
                'description' => $shopItem->DESCRIPTION,
                'ean' => $shopItem->EAN,
                'delivery_time' => (int)$shopItem->DELIVERY_DATE,
                'manufacturer_id' => $manufacturer_id]
        );
    }

    public function getProductsByName($name){
        return DB::table('product')
            ->select('name')
            ->where('name', 'like', '%' . $name . '%')
            ->groupBy('name')
            ->orderBy('name')
            ->get();
    }

    public function getProductsLike($nameLike){
        return DB::table('product')
            ->where('name', 'like', '%' . $nameLike . '%')
            ->select('name', 'price', 'img_url')
            ->get();
    }

    public function productExists($id){
        return DB::table('product')->where('product_id', $id)->exists();
    }

    public function getProductById($id){
        return DB::table('product')
            ->where('product_id', $id)
            ->get()[0];
    }


    public function getProducAdditionalInfo($productName){
        return DB::table('product')
            ->join('manufacturer', 'product.manufacturer_id', '=', 'manufacturer.manufacturer_id')
            ->select('manufacturer.name as manufacturer_name', 'description', 'img_url')
            ->where('product.name', $productName)
            ->get();
    }

    public function getMinMaxPrice($productName){
        $min = DB::table('product')
            ->where('name', $productName)
            ->min('price');
        $max = DB::table('product')
            ->where('name', $productName)
            ->max('price');
        return [$min, $max];
    }

    public function getProductIdSpecifiedByShop($productName, $shop_id){
        return (int)DB::table('product')
            ->select('product_id')
            ->where('name', $productName)
            ->where('shop_id', $shop_id)
            ->value('product_id');
    }

}
