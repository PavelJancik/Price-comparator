<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Review
{
    public function getProductsIdAndUrlList($shop_id) {
        return DB::table('product')
            ->select('product_id', 'url')
            ->where('shop_id', '=', $shop_id)
            ->get();
    }

    public function getReviews($productName){
        $product_id = DB::table('product')
            ->join('shop', 'product.shop_id', '=', 'shop.shop_id')
            ->where('product.name', $productName)
            ->where('shop.name', 'Muziker')
            ->value('product.product_id');
        return DB::table('review')
            ->select('stars', 'created', 'content', 'contributor', 'review_id')
            ->where('product_id', $product_id)
            ->get();
    }

    public function insertProductReviews($product_id, $text, $contributor, $date, $stars){
        for ($i=0; $i<sizeof($text); $i++){
            if (DB::table('review')
                ->where('stars', $stars[$i])
                ->where('content', $text[$i])
                ->where('contributor', $contributor[$i])
                ->where('created', $date[$i])
                ->doesntExist())
                DB::table('review')->insert([
                    'stars' => $stars[$i],
                    'content' => $text[$i],
                    'contributor' => $contributor[$i],
                    'created' => $date[$i],
                    'product_id' => $product_id,
                ]);
        }
    }
}
