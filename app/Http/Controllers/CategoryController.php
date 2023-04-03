<?php

namespace App\Http\Controllers;

use App\Models\Category;

const ROOT_CATEGORY = 'Hudební nástroje';

class CategoryController extends Controller
{

    // ----------------- public ------------------

    public function getCategoryPath($currentCategory){
        $currentCategoryDecoded = urldecode($currentCategory);
        $categoryPath = [];
        array_push($categoryPath, $currentCategoryDecoded);
        $parentCategory = null;
        while ($currentCategoryDecoded != ROOT_CATEGORY) {
            $parentCategory = $this->getParentCategory($currentCategoryDecoded);
            array_push($categoryPath, $parentCategory);
            $currentCategoryDecoded = $parentCategory;
        }
        if (in_array('root', $categoryPath, false)) return [$currentCategoryDecoded];
        return array_reverse($categoryPath);
    }

    public function getCategoryTree(){
        $category = new Category();
        $rootRow = $category->getCategoryTree(ROOT_CATEGORY);
        $mainObject['category'] = $rootRow[0];
        $mainObject['sub_categories'] = $this->recursion($rootRow[0]->category_id);
        return $mainObject;
    }

    public function getParentCategory($childCategoryName){
        $child = $this->getCategoryByName($childCategoryName);
        if (sizeof($child)!=0) {
            $category = new Category();
            $parent = $category->getCategoryById($child[0]->parent_category_id);
            if (sizeof($parent)!=0) return $parent[0]->name;
            if ($child[0]->name == 'root') return null;
        }
        return ROOT_CATEGORY;
    }

    public function setCategories($categoryText, $product_id){
        $categories = $this->parseCategoryText($categoryText);
        foreach ($categories as $category){
            $category_id = $this->getCategoryId($category);
            $categoryModel = new Category();
            $categoryModel->setCategories($category_id, $product_id);
        }
    }

    public function updateCategories($categoryText){
        $item_categories = $this->parseCategoryText($categoryText);
        $category = new Category();
        $category->updateCategories($item_categories);
    }

    public function getDeepestProductCategory($productName){
        $productNameDecoded = urldecode($productName);
        $categories = $this->getProductCategories($productNameDecoded);
        return $this->getDeepestCategory($categories);
    }

    // ----------------- private -----------------

    private function recursion($parentId){
        $categoryModel = new Category();
        $array = $categoryModel->getChildrenCategories($parentId);
        $mainArray = [];
        for ($i=0; $i<sizeof($array); $i++){
            // wrapObject structure: {parent category}[child categories]
            $childrensArray = $this->recursion($array[$i]->category_id);
            $wrapObject['category'] = $array[$i];
            $wrapObject['sub_categories'] = $childrensArray;
            array_push($mainArray, $wrapObject);
        }
        return $mainArray;
    }

    private function getCategoryByName($name){
        return (new Category())->getCategoryByName($name);
    }

    private function parseCategoryText($categoryText){
        $item_categories = explode(" | ", (string)$categoryText); // parse $categoryText from feed
        if ($item_categories[0] == "Heureka.cz") $item_categories[0] = "root";
        return $item_categories;
    }

    private function getCategoryId($name){
        return (new Category())->getCategoryId($name);
    }

    private function getDeepestCategory($categories){
        $deepestCategoryIndex = null;
        $maxCategoryDepth = 0;
        for ($i=0; $i<sizeof($categories); $i++){
            $depth = 0;
            $current = $categories[$i]->name;
            while ($current != null) {
                $current = $this->getParentCategory($current);
                $depth++;
            }
            if ($depth > $maxCategoryDepth) {
                $maxCategoryDepth = $depth;
                $deepestCategoryIndex = $i;
            }
        }
        return $categories[$deepestCategoryIndex]->name;
    }

    private function getProductCategories($productName) {
        return (new Category())->getProductCategories($productName);
    }

}
