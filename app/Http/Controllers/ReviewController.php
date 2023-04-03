<?php


namespace App\Http\Controllers;
require 'scrapping/simple_html_dom.php';

use App\Models\Review;


class ReviewController extends Controller
{

    // ----------------- public ------------------

    public function updateReviews() {
        set_time_limit(0);
        $productList = (new Review())->getProductsIdAndUrlList(1); // todo 1 - nyni se scrapuje pouze muziker
        for ($i=0; $i<sizeof($productList); $i++){
            $this->scrapReviews($productList[$i]->url, $productList[$i]->product_id);
        }
        return 'ok';
    }

    public function getReviews($productName){
        $productNameDecoded = urldecode($productName);
        return (new Review())->getReviews($productNameDecoded);
    }

    // ----------------- private ------------------

    public function scrapReviews($product_url, $product_id) {
        try {
            $url = $product_url . '#reviews';
            $html = file_get_html($url);
            $textArray = [];
            $contributorArray = [];
            $dateArray = [];
            $starsArray = [];
            // todo nebere to recenze na dalsich strankach (za .load-more) (pokud tam takovy element je)
            foreach ($html->find('.rating-product') as $ratingProduct) {
                // text
                foreach ($ratingProduct->find('.text') as $text) {
                    $reviewText = "";
                    $reviewText = $reviewText . ' ' . $text->innertext;
                    foreach ($text->find('p') as $p){
                        $reviewText = $reviewText . ' ' . $p->innertext;
                    }
                    $reviewText = $this->removeAdditionalWhiteSpaces($reviewText);
                    array_push($textArray, $reviewText);
                }
                // stars
                foreach ($ratingProduct->find('.rating-lines') as $ratingLines){
                    $class = $ratingLines->children(0)->find('div', -1)->class;
                    array_push($starsArray, intval(substr(strstr($class, 'rated-'), 6)));
                }
                // contributor
                foreach ($ratingProduct->find('.rating-profile') as $ratingProfile)
                    foreach ($ratingProfile->find('p') as $contributor)
                        array_push($contributorArray, $this->removeAdditionalWhiteSpaces($contributor->innertext));
                // date
                foreach ($ratingProduct->find('.rating-lines') as $ratingLines)
                    array_push($dateArray, $this->removeAdditionalWhiteSpaces($ratingLines->children(1)->find('div', -1)->innertext));
            }
            $this->insertIntoTable($product_id, $textArray, $contributorArray, $dateArray, $starsArray);
        } catch (\Exception $e){
            echo $e;
        }
    }

    private function removeAdditionalWhiteSpaces($str){
        $i = 0;
        while (ctype_space($str[$i]) and $i<strlen($str)) $i++;
        return substr($str, $i);
    }

    private function insertIntoTable($product_id, $text, $contributor, $date, $stars){
        if (sizeof($text) != sizeof($contributor) or sizeof($text) != sizeof($date) or sizeof($text) != sizeof($stars)) return false;
        (new Review())->insertProductReviews($product_id, $text, $contributor, $date, $stars);
    }


}
