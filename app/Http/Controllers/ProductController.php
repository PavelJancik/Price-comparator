<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    // ----------------- public ------------------

    // todo try catch bloky
    // todo Models
    public function getProductsByCategory(Request $request, $category){
        $categoryDecoded = urldecode($category);
        $productModel = new Product();
        $productsQB = $productModel->getProductsQueryBuilder($categoryDecoded);
        $productsQB = $this->orderBy($request, $productsQB);

        $filterController = new FilterController();
        $productsQB = $filterController->addFilters($request, $productsQB);

        $products = $productsQB->paginate(10); // fetch query
        if ($products->total() == 0) return $products;

        $products = $this->addAdditionalInfo($products);
        return $products;
    }

    public function getProductId($productName){
        return (new Product())->getProductId($productName);
    }

    public function createOrUpdateProduct($shopItem, $shop_id){
        $manufacturerController = new ManufacturerController();
        $categoryController = new CategoryController();
        $parameterController = new ParameterController();
        $deliveryController = new DeliveryController();
        $productModel = new Product();
        $manufacturer_id = $manufacturerController->getManufacturerID($shopItem->MANUFACTURER);
        $productModel->createOrUpdateProduct($shopItem, $shop_id, $manufacturer_id);
        $product_id = $this->getProductIdSpecifiedByShop($shopItem->PRODUCTNAME, $shop_id);
        $categoryController->setCategories($shopItem->CATEGORYTEXT, $product_id);
        $parameterController->setProductParams($shopItem, $product_id);
        $deliveryController->updateDelivery($shopItem, $product_id);;
    }

    public function getProductsByName($name){
        $nameDecoded = urldecode($name);
        return (new Product())->getProductsByName($nameDecoded);
    }

    public function getRandomProductLike($nameLike){
        $nameLikeDecoded = urldecode($nameLike);
        $productModel = new Product();
        $response = $productModel->getProductsLike($nameLikeDecoded);
        $randomProduct = (array)$response[rand(0, sizeof($response)-1)];
        $randomProduct['min_price'] = $this->getMinMaxPrice($randomProduct['name'])[0];
        return $randomProduct;
    }

    public function getRandomProduct(){
        $productModel = new Product();
        do $random_id = rand(0, 10000);
        while(!$productModel->productExists($random_id));
        $response = $productModel->getProductById($random_id);
        $arrayResponse = (array)$response;
        $arrayResponse['min_price'] = $this->getMinMaxPrice($response->name)[0];
        return $arrayResponse;
    }

    // ----------------- private -----------------

    private function addAdditionalInfo($products){
        $numberOfProductsAtPage = $products->perPage();
        if (($products->currentPage() == $products->lastPage()) and ($products->total() % $products->perPage() != 0)) $numberOfProductsAtPage = $products->total() % $products->perPage();

        for ($i = 0; $i < $numberOfProductsAtPage; $i++){
            $product = (array)$products[$i];
            // price borders
            $minMaxPrice = $this->getMinMaxPrice($products[$i]->name);
            $product['minPrice'] = $minMaxPrice[0];
            $product['maxPrice'] = $minMaxPrice[1];
            // other info
            $productItem = (new Product())->getProducAdditionalInfo($product['name']);
            $product['manufacturer_name'] = $productItem[0]->manufacturer_name;
            $product['description'] = $productItem[0]->description;
            $product['img_url'] = $productItem[0]->img_url;
            $products[$i] = $product;
        }
        return $products;
    }

    private function getMinMaxPrice($productName){
        return (new Product())->getMinMaxPrice($productName);
    }

    public function getProductIdSpecifiedByShop($productName, $shop_id){
        return (new Product())->getProductIdSpecifiedByShop($productName, $shop_id);
    }

    private function orderBy($request, $productsQB){
        if ($request->headers->get('sort')) {
            $sort = $request->headers->get('sort');
            if ($sort == 'name') $productsQB = $productsQB->orderBy('product.name');
            if ($sort == 'price_a') $productsQB = $productsQB->orderBy('product.price', 'asc'); // todo bere v potaz prvni nalezeny produkt a ne nejmensi cenu
            if ($sort == 'price_d') $productsQB = $productsQB->orderBy('product.price', 'desc');
        }
        return $productsQB;
    }

}
