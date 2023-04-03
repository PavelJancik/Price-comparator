<?php


namespace App\Http\Controllers;

class FilterController extends Controller
{
    // ----------------- public ------------------

    public function addFilters($request, $productsQB){
        $filters = null;

        if ($request->headers->get('filters')) $filters = json_decode($request->headers->get('filters'));
        if ($filters){
            // price
            $productsQB = $productsQB->whereBetween('product.price', $filters->price);
            // product name
            if ($filters->productName != "" or $filters->productName != null) $productsQB = $productsQB->where('product.name', 'like', '%' . $filters->productName . '%');
            // manufacturers
            if ($filters->manufacturers != []) $productsQB = $productsQB->whereIn('manufacturer.name', $filters->manufacturers);
            // strings
            if ($filters->strings != []) {
                $stringsQB = clone $productsQB; // clone is necessary, otherwise variable is passed by reference
                $stringsQB = $this->setStringFilter($stringsQB, $filters->strings);
                $productsQB = $this->joinSubQuery($productsQB, $stringsQB, 'strings');
            }
            // colors
            if ($filters->colors != []) {
                $colorsQB = clone $productsQB;
                $colorsQB = $colorsQB->where('parameter.name', 'Barva')->whereIn('value.value', $filters->colors);
                $productsQB = $this->joinSubQuery($productsQB, $colorsQB, 'colors');
            }
            // surface
            if ($filters->surface != []) {
                $surfaceQB = clone $productsQB;
                $surfaceQB = $surfaceQB->where('parameter.name', 'Povrchová úprava')->whereIn('value.value', $filters->surface);
                $productsQB = $this->joinSubQuery($productsQB, $surfaceQB, 'surface');
            }
            // electronics
            if ($filters->electronics != []) {
                $electronicsQB = clone $productsQB;
                $electronicsQB = $electronicsQB->where('parameter.name', 'Elektronika')->whereIn('value.value', $filters->electronics);
                $productsQB = $this->joinSubQuery($productsQB, $electronicsQB, 'electronics');
            }
            // hands
            if ($filters->hands != []) {
                $handsQB = clone $productsQB;
                $handsQB = $handsQB->where('parameter.name', 'Pravák, levák')->whereIn('value.value', $filters->hands);
                $productsQB = $this->joinSubQuery($productsQB, $handsQB, 'hands');
            }
            // types
            if ($filters->types != []) {
                $typeQB = clone $productsQB;
                $typeQB = $typeQB->where('parameter.name', 'Typ')->whereIn('value.value', $filters->types);
                $productsQB = $this->joinSubQuery($productsQB, $typeQB, 'types');
            }
            // materials
            if ((array)$filters->materials != []){
                if ($filters->materials->neck != []) {
                    $neckQB = clone $productsQB;
                    $neckQB = $neckQB->where('parameter.name', 'Materiál krku')->whereIn('value.value', $filters->materials->neck);
                    $productsQB = $this->joinSubQuery($productsQB, $neckQB, 'neckMaterial');
                }
                if ($filters->materials->body != []) {
                    $bodyQB = clone $productsQB;
                    $bodyQB = $bodyQB->where('parameter.name', 'Materiál těla')->whereIn('value.value', $filters->materials->body);
                    $productsQB = $this->joinSubQuery($productsQB, $bodyQB, 'bodyMaterial');
                }
                if ($filters->materials->fingerboard != []) {
                    $fingerboardQB = clone $productsQB;
                    $fingerboardQB = $fingerboardQB->where('parameter.name', 'Materiál hmatníku')->whereIn('value.value', $filters->materials->fingerboard);
                    $productsQB = $this->joinSubQuery($productsQB, $fingerboardQB, 'fingerboardMaterial');
                }
            }
            // size
            if ($filters->size != []) {
                $sizeQB = clone $productsQB;
                $sizeQB = $sizeQB->where('parameter.name', 'Velikost')->whereIn('value.value', $filters->size);
                $productsQB = $this->joinSubQuery($productsQB, $sizeQB, 'size');
            }

        }
        return $productsQB;
    }

    // ----------------- private -----------------

    private function joinSubQuery($mainQB, $subQB, $subQueryTitle){
        return $mainQB->joinSub($subQB, $subQueryTitle, function ($join) use ($subQueryTitle){
            $join->on('product.name', '=', $subQueryTitle . '.name');
        });
    }

    private function setStringFilter($productsQB, $selectedStrings){
        $productsQB = $productsQB->where('parameter.name', 'Počet strun');
        if (in_array('0', $selectedStrings, false)) { // if "ostatni" (0) is selected
            $invertedStringsSelection = array_values(array_diff([4, 5, 6, 7, 12], $selectedStrings));
            $productsQB = $productsQB->whereNotIn('value.value', $invertedStringsSelection);
        }
        else $productsQB = $productsQB->whereIn('value.value', $selectedStrings);
        return $productsQB;
    }

}
