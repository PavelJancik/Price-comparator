<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Manufacturer
{

    public function getManufacturers(){
        return DB::table('manufacturer')->get();
    }

    public function getManufacturersInCategory($category){
        return DB::table('manufacturer')
            ->join('product', 'product.manufacturer_id', '=', 'manufacturer.manufacturer_id')
            ->join('product_category', 'product.product_id', '=', 'product_category.product_id')
            ->join('category', 'category.category_id', '=', 'product_category.category_id')
            ->select('manufacturer.name')
            ->where('category.name', $category)
            ->groupBy('manufacturer.name')
            ->get();
    }

    public function getManufacturerID($name){
        return (int)DB::table('manufacturer')
            ->where('name', $name)
            ->value('manufacturer_id');
    }

    public function createManufacturer($name){
        DB::table('manufacturer')->insert(['name' => $name]);
    }

}
