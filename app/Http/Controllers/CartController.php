<?php


namespace App\Http\Controllers;

use App\Models\Cart;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class CartController extends Controller
{

    // ----------------- public ------------------

    public function createNewList(){
        $cart = new Cart();
        $id = $cart->createNewList();
        return $this->encodeList($id);
    }

    //TODO  preda se prvni nalazeny produkt
    //todo      coz teoreticky nevadi, protoze v komponente Cart se zobrazi jenom nazev
    //todo      a pro doporuceni presneho produktu se stejne bude filtrovat pomoci 'where name is x'
    public function addProduct(Request $request, $productName) {
        $productNameDecoded = urldecode($productName);
        $cart_id = $this->decodeListId($request);
        $productController = new ProductController();
        $product_id = $productController->getProductId($productNameDecoded);
        // if record doesnt exist, insert it
        $cart = new Cart();
        $cart->addProduct($cart_id, $product_id);
        return $this->encodeList($cart_id);
    }

    public function removeProduct(Request $request, $productName){
        $productNameDecoded = urldecode($productName);
        $cart_id = $this->decodeListId($request);
        $productController = new ProductController();
        $product_id = $productController->getProductId($productNameDecoded);
        $cart = new Cart();
        $cart->removeProduct($cart_id, $product_id);
        return $this->encodeList($cart_id);
    }

    public function getList(Request $request){
        $cart_id = $this->decodeListId($request);
        return $this->getListById($cart_id);
    }

    // ----------------- private -----------------

    // returns encoded JWT token
    private function encodeList($cart_id){
        $key = $_ENV['APP_KEY'];
        $payload = [
            "cart_id" => $cart_id,
            "items" => $this->getListById($cart_id)
        ];
        $algorithm = $_ENV['JWT_ALG'];
        $response['token'] = JWT::encode($payload, $key, $algorithm); // available algs: https://jwt.io/libraries
        return $response;
    }

    private function decodeListId(Request $request){
        if ($request->headers->get('Authorization')) {
            $bearerToken = $request->headers->get('Authorization');
            $token = explode(" ", $bearerToken)[1];
            $payload = JWT::decode($token, $_ENV['APP_KEY'], array($_ENV['JWT_ALG']));
            return $payload->cart_id;
        } else return null;  // todo return error code
    }

    private function getListById($cart_id){
        return (new Cart())->getListById($cart_id);
    }


}
