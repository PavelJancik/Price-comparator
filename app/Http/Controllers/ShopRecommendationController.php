<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

const NEAR_TO_OPTIMAL_SOLUTION_FLIP_POINT = 10; // number of products where algorithm changes to the faster one (but can get solution just close to optimal one)

class ShopRecommendationController extends Controller
{

    // ----------------- public ------------------

    // get optimal solution
    public function solve(Request $request){

        $productsArray = $this->getInfo($request); // informations about each product-shop option
        if (sizeof($productsArray) == 0) return null;
        if (sizeof($productsArray) >= NEAR_TO_OPTIMAL_SOLUTION_FLIP_POINT) return $this->nearlyOptimal($productsArray);

        $sum_price_combinations = [];
        $ps_combinations = []; // product-shop combinations
        $delivery_combinations = [];
        for ($i=0; $i<sizeof($productsArray[0]); $i++){ // combinations for first product in Cart
            array_push($sum_price_combinations, $productsArray[0][$i]->price);
            array_push($ps_combinations, $i);
            array_push($delivery_combinations, [$productsArray[0][$i]->shop_name => $productsArray[0][$i]->min_delivery_price]);
        }
        for ($i=1; $i<sizeof($productsArray); $i++){ // for each other product; $i=1 couse first product in Cart does not count
            $copy_of_sum_price_combinations = $sum_price_combinations;
            $copy_of_ps_combinations = $ps_combinations;
            $copy_of_delivery_combinations = $delivery_combinations;
            $new_sum_price_combinations = [];
            $new_ps_combinations = [];
            $new_delivery_combinations = [];
            for ($j=0; $j<sizeof($productsArray[$i]); $j++) { // for each product-shop option
                // to each available combination add price of the product-shop option
                foreach ($copy_of_sum_price_combinations as $sum_price_combination) array_push($new_sum_price_combinations, $sum_price_combination += $productsArray[$i][$j]->price);
                $sum_price_combinations = $new_sum_price_combinations;
                // add index of shop to product_shop_combinations array
                foreach ($copy_of_ps_combinations as $product_shop_combination) array_push($new_ps_combinations, $product_shop_combination = $product_shop_combination . ";" .  $j);
                $ps_combinations = $new_ps_combinations;
                // delivery
                foreach ($copy_of_delivery_combinations as $sum_delivery_combination){
                    // if shop index is not included yet for this combination, add it
                    if (!array_key_exists($productsArray[$i][$j]->shop_name, $sum_delivery_combination)) $sum_delivery_combination[$productsArray[$i][$j]->shop_name] = $productsArray[$i][$j]->min_delivery_price;
                    // else if included shop-product delivery is higher, than the new one, rewrite delivery price to the lower one
                    else if ($sum_delivery_combination[$productsArray[$i][$j]->shop_name] > $productsArray[$i][$j]->min_delivery_price) $sum_delivery_combination[$productsArray[$i][$j]->shop_name] = $productsArray[$i][$j]->min_delivery_price;
                    array_push($new_delivery_combinations, $sum_delivery_combination);
                }
                $delivery_combinations = $new_delivery_combinations;
            }
        }
        // add delivery price, to each $sum_price_combination
        for ($i=0; $i<sizeof($sum_price_combinations); $i++) foreach ($delivery_combinations[$i] as $shop_delivery) $sum_price_combinations[$i] += $shop_delivery;
        // find lowest sum price combination
        $minCartPrice = PHP_INT_MAX;
        $minPriceIndex = null;
        for ($i=0; $i<sizeof($sum_price_combinations); $i++){
            if ($sum_price_combinations[$i] < $minCartPrice) {
                $minCartPrice = $sum_price_combinations[$i];
                $minPriceIndex = $i;
            }
        }
        // find optimal combinations of shop indexes
        $optimal_combination_shop_indexes = explode(";", $ps_combinations[$minPriceIndex]);
        // find final solution
        $solution = [];
        for ($i=0; $i<sizeof($optimal_combination_shop_indexes); $i++) array_push($solution, $productsArray[$i][intval($optimal_combination_shop_indexes[$i])]);
        return $solution;
    }

    // ----------------- private -----------------

    private function getInfo(Request $request){
        $cartController = new CartController();
        $shopController = new ShopController();
        $list = $cartController->getList($request);
        $productsArray = [];
        foreach ($list as $product) {
            $shopOptions = $shopController->getShopOptions($request, $product->name);
            array_push($productsArray, $shopOptions);
        }
        return $productsArray;
    }

    // get optimal solution or solution close to optimal one
    private function nearlyOptimal($productsArray){
        $solution = [];
        foreach ($productsArray as $productShopOptions){
            $minPrice = PHP_INT_MAX;
            $bestShopOption = null;
            foreach ($productShopOptions as $shopOption) {
                if ($shopOption->price < $minPrice) {
                    $minPrice = $shopOption->price;
                    $bestShopOption = $shopOption;
                }
            }
            array_push($solution, $bestShopOption);
        }
        return $solution;
    }

}


