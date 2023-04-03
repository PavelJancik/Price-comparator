<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Shop
{
    public function getFeedUrl($shopName){
        return DB::table('shop')
            ->where('name', '=', $shopName)
            ->value('xml_feed_url');
    }

    public function getAllShops(){
        return DB::table('shop')->get();
    }

    public function getShopOptionsQB($productName){
        return DB::table('product')
            ->select('product_id', 'product.name as product_name', 'shop.name as shop_name', 'manufacturer.name as manufacturer_name',
                'price', 'url', 'img_url', 'description', 'delivery_time', 'site_url', 'shop.shop_id', 'shop.logo_url')
            ->join('shop', 'product.shop_id', '=', 'shop.shop_id')
            ->join('manufacturer', 'product.manufacturer_id', '=', 'manufacturer.manufacturer_id')
            ->where('product.name', $productName);
    }

    public function getSingleShopOption($productName, $shopName){
        return DB::table('product')
            ->select('product_id', 'product.name as product_name', 'shop.name as shop_name', 'price', 'url', 'img_url', 'description')
            ->join('shop', 'product.shop_id', '=', 'shop.shop_id')
            ->where('product.name', $productName)
            ->where('shop.name', $shopName)
            ->get();

    }

}
