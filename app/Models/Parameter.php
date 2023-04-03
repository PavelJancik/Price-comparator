<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Parameter
{

    public function getParamByName($name){
        return DB::table("parameter")
            ->where('name', $name)
            ->get();
    }

    public function getValueByName($name){
        return DB::table('value')
            ->where('value', $name)
            ->get();
    }

    public function doesProductParamValExists($product_id, $parameter_id, $value_id) {
        return DB::table('product_parameter_value')
            ->where('product_id', $product_id)
            ->where('parameter_id', $parameter_id)
            ->where('value_id', $value_id)
            ->exists();
    }

    public function getProductParams($product_id){
        return DB::table('product_parameter_value')
            ->join('parameter', 'parameter.parameter_id', '=', 'product_parameter_value.parameter_id')
            ->join('value', 'value.value_id', '=', 'product_parameter_value.value_id')
            ->select('parameter.name', 'value.value')
            ->where('product_id', $product_id)
            ->get();
    }


    public function createParameter($parameterName){
        return DB::table('parameter')
            ->insertGetId([
                'name' => $parameterName
            ]);
    }

    public function createValue($value){
        return DB::table('value')
            ->insertGetId([
                'value' => $value
            ]);
    }

    public function createProductParamValConnection($product_id, $parameter_id, $value_id){
        DB::table('product_parameter_value')->insert([
            'product_id' => $product_id,
            'parameter_id' => $parameter_id,
            'value_id' => $value_id
        ]);
    }

    public function getParameterValues($parameterName, $category){
        $qb = DB::table('product_parameter_value')
            ->join('parameter', 'parameter.parameter_id', '=', 'product_parameter_value.parameter_id')
            ->join('value', 'value.value_id', '=', 'product_parameter_value.value_id')
            ->select('value.value')
            ->where('parameter.name', $parameterName)
            ->groupBy('value.value');
        if ($category) $qb = $qb->join('product', 'product.product_id', '=', 'product_parameter_value.product_id')
            ->join('product_category', 'product_category.product_id', '=', 'product.product_id')
            ->join('category', 'category.category_id', '=', 'product_category.category_id')
            ->where('category.name', $category);
        return $qb->get();
    }
}
