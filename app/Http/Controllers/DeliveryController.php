<?php

namespace App\Http\Controllers;
use App\Models\Delivery;

class DeliveryController extends Controller
{

    // ----------------- public -----------------

    public function updateDelivery($shopItem, $product_id){
        $deliveryModel = new Delivery();
        foreach ($shopItem->DELIVERY as $delivery) {
            $deliveryModel->updateDelivery($delivery, $product_id);
        }
    }

    public function getMinDeliveryPrice($product_id) {
        return (new Delivery())->getMinDeliveryPrice($product_id);
    }


    // ----------------- private -----------------

}
