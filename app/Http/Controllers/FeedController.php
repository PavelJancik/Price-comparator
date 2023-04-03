<?php

namespace App\Http\Controllers;

use App\Models\Shop;

class FeedController extends Controller
{

    // ----------------- public ------------------

    // generates .xml file with random prices based on Muziker XML feed
    public function generateFeed($index){
        set_time_limit(0);
        $price_difference = 0.15;
        $shopModel = new Shop();
        $muzikerUrl = $shopModel->getFeedUrl('Muziker');
        if (file_exists((string)$muzikerUrl)) {
            $xml = simplexml_load_file((string)$muzikerUrl);
            $deliveryNames = ['GEIS', 'ZASILKOVNA', 'GLS', 'DPD', 'PPL', 'CESKA POSTA'];
            $deliveryPrices = [0, 99, 89, 39, 99, 89, 99, 130];
            foreach ($xml->SHOPITEM as $shopitem) {
                $min = (int)($shopitem->PRICE_VAT * (1 - $price_difference) / 10);
                $max = (int)($shopitem->PRICE_VAT * (1 + $price_difference) / 10);
                $shopitem->PRICE_VAT = rand($min, $max) * 10;
                $shopitem->DELIVERY->DELIVERY_ID = $deliveryNames[rand(0, sizeof($deliveryNames)-1)];
                $shopitem->DELIVERY->DELIVERY_PRICE = $deliveryPrices[rand(0, sizeof($deliveryPrices)-1)];
            }
            $xml->saveXML("C:\Users\jancp\Desktop\Bakalarska_prace\GIT\\03_ostatni\XML_feeds\generated\generated_feed_" . $index .  ".xml");
        } else {
            exit('Can not open Muziker feed');
        }
    }

    public function generateFeeds(){
        for ($i=1; $i<=3; $i++){
            $this->generateFeed($i);
        }
        return true;
    }

    public function feedUpdate(){
        $productController = new ProductController();
        $manufacturerController = new ManufacturerController();
        $shopController = new ShopController();
        $categoryController = new CategoryController();

        set_time_limit(0);
        $manufacturers = $manufacturerController->getManufacturers();
        // todo updateManufactures by slo udelat stejne jako update categories, ale takto se provadi SQL dotaz pouze pri vytvoreni noveho vyrobce a ne u kazdeho $shopitemu
        // todo je to vyzamne usetreni casu? nejvic casu asi stejne zabira parsovani xml
        $shops = $shopController->getAllShops();
        // todo funkce na promazanic starych produktu ktere jiz nejsou ve feedu

        foreach ($shops as $shop){
            if (file_exists($shop->xml_feed_url)) {
                $feed = simplexml_load_file($shop->xml_feed_url);
                $number_of_inserts = 0; // todo delete tento radek
                foreach ($feed->SHOPITEM as $shopitem) {
                    $number_of_inserts += 1; // todo delete tento radek
                    if ($number_of_inserts == 2500) break; // todo delete tento radek // 2500
                    try {
                        $manufacturers = $manufacturerController->updateManufacturers($shopitem, $manufacturers); // check if manufacturer exists, if not create manufacturer record
                        $categoryController->updateCategories($shopitem->CATEGORYTEXT);
                        $productController->createOrUpdateProduct($shopitem, $shop->shop_id);
                    } catch (\Exception $e) {
                        return "Error: " . $e;
                    }
                }
            } else {
                exit('Can not open xml feed by ' . $shop->name);
            }
        }
        return "ok";
    }

    // ----------------- private -----------------


}



