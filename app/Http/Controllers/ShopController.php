<?php


namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{

    // ----------------- public ------------------

    public function getAllShops(){
        return (new Shop())->getAllShops();
    }

    public function getShopOptions(Request $request, $productName){
        $productName = urldecode($productName);
        $shopModel = new Shop();
        $qb = $shopModel->getShopOptionsQB($productName);
        $shopOptions = $this->orderBy($request, $qb)->get();
        $deliveryController = new DeliveryController();
        for ($i = 0; $i < sizeof($shopOptions); $i++){
            $shopOption = (array)$shopOptions[$i];
            $shopOption['min_delivery_price'] = $deliveryController->getMinDeliveryPrice($shopOptions[$i]->product_id);
            $shopOptions[$i] = (object)$shopOption;
        }
        return $shopOptions;
    }

    public function getSingleShopOption($productName, $shopName){
        $productNameDecoded = urldecode($productName);
        $shopNameDecoded = urldecode($shopName);
        $shopModel = new Shop();
        $response = $shopModel->getSingleShopOption($productNameDecoded, $shopNameDecoded);
        $deliveryController = new DeliveryController();
        $shopOption = (array)$response[0];
        $shopOption['min_delivery_price'] = $deliveryController->getMinDeliveryPrice($response[0]->product_id);
        return (object)$shopOption;
    }

    // ----------------- private -----------------

    private function orderBy($request, $qb){
        if ($request->headers->get('sort')) {
            $sort = $request->headers->get('sort');
            if ($sort == 'name') $qb = $qb->orderBy('shop.name');
            if ($sort == 'price_a') $qb = $qb->orderBy('price', 'asc');
            if ($sort == 'price_d') $qb = $qb->orderBy('price', 'desc');
        }
        return $qb;
    }

}
