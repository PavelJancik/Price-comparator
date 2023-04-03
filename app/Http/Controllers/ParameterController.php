<?php


namespace App\Http\Controllers;

use App\Models\Parameter;

class ParameterController extends Controller
{

    // ----------------- public ------------------

    public function setProductParams($shopitem, $product_id){
        $parameterModel = new Parameter();
        // todo nekde se taky musi vymazat parametry, ktere uz produkt nema
        foreach ($shopitem->PARAM as $param) {
            $parameterResponse = $parameterModel->getParamByName($param->PARAM_NAME);
            // pokud parametr s takovym nazvem neexistuje, vytvor ho
            if (sizeof($parameterResponse)==0) {
                $parameter_id = $this->createParameter($param->PARAM_NAME);
            } else $parameter_id = $parameterResponse[0]->parameter_id;
            // pokud neexistuje takova hodnota, vytvor ji
            $valueResponse = $parameterModel->getValueByName($param->VAL);
            if (sizeof($valueResponse)==0) {
                $value_id = $this->createValue($param->VAL);
            } else $value_id = $valueResponse[0]->value_id;
            // pokud parametr daneho produktu neobsahuje danou hodnotu, nasetuj ji
            if (!$parameterModel->doesProductParamValExists($product_id, $parameter_id, $value_id)) {
                $this->createProductParamValConnection($product_id, $parameter_id, $value_id);
            }
        }
    }

    public function getProductParams($productName){
        $productNameDecoded = urldecode($productName);
        $productController = new ProductController();
        $product_id = $productController->getProductId($productNameDecoded);
        return (new Parameter())->getProductParams($product_id);
    }

    public function getColors(){
        return $this->getParameterValues('Barva', null);
    }

    public function getSurface($category){
        $categoryDecoded = urldecode($category);
        return $this->getParameterValues('Povrchová úprava', $categoryDecoded);
    }

    public function getTypes($category){
        $categoryDecoded = urldecode($category);
        return $this->getParameterValues('Typ', $categoryDecoded);
    }

    public function getMaterials($paramName, $category){
        $paramNameDecoded = urldecode($paramName);
        $categoryDecoded = urldecode($category);
        return $this->getParameterValues($paramNameDecoded, $categoryDecoded);
    }

    // ----------------- private ------------------

    private function createParameter($parameterName){
        return (new Parameter())->createParameter($parameterName);
    }

    private function createValue($value){
        return (new Parameter())->createValue($value);
    }

    private function createProductParamValConnection($product_id, $parameter_id, $value_id){
        (new Parameter())->createProductParamValConnection($product_id, $parameter_id, $value_id);
    }

    private function getParameterValues($parameterName, $category){
        return (new Parameter())->getParameterValues($parameterName, $category);
    }

}
