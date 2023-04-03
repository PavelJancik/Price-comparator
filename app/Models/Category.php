<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Category
{

    public function getCategoryTree($rootCategory){
        return DB::table('category')->where('name', $rootCategory)->get(); // todo momentalne se vyberou pouze kategorie pod kategorii "hudebni nastroje"
    }



    public function setCategories($category_id, $product_id){
        DB::table('product_category')->updateOrInsert([
            'product_id' => $product_id,
            'category_id' => $category_id
        ]);
    }

    public function updateCategories($item_categories){
        for ($i=0; $i<sizeof($item_categories); $i++){
            // if there is not record of this category name, create it
            if (DB::table('category')->where('name', $item_categories[$i])->doesntExist()){
                $this->createCategory($item_categories[$i], ($i>0) ? $this->getCategoryId($item_categories[$i-1]) : 1); // 2nd parameter: find parent category, otherwise choose "root"
            }
        }
    }

    public function getCategoryId($name){
        $category = DB::table('category')
            ->select('category_id')
            ->where('name', $name)
            ->get();
        return (int)$category[0]->category_id;
    }

    public function createCategory($name, $parent_category_id){
        DB::table('category')->insert([
            'name' => $name,
            'parent_category_id' => $parent_category_id
        ]);
    }

    public function getProductCategories($productName) {
        return DB::table('product')
            ->join('product_category', 'product.product_id', '=', 'product_category.product_id')
            ->join('category', 'category.category_id', '=', 'product_category.category_id')
            ->select('category.name')
            ->where('product.name', $productName)
            ->groupBy('category.name')
            ->get();
    }

    public function getChildrenCategories($parentId){
        return DB::table('category')->where('parent_category_id', $parentId)->get();
    }

    public function getCategoryByName($name){
        return DB::table('category')->where('name', $name)->get();
    }

    public function  getCategoryById($id){
        return DB::table('category')->where('category_id', $id)->get();
    }


}
