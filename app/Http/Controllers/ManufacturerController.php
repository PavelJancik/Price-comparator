<?php


namespace App\Http\Controllers;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    // ----------------- public ------------------

    public function getManufacturers(){
        return (new Manufacturer())->getManufacturers();
    }

    public function getManufacturersInCategory($category){
        $categoryDecoded = urldecode($category);
        return (new Manufacturer())->getManufacturersInCategory($categoryDecoded);
    }

    public function getManufacturerID($name){
        $m = new Manufacturer();
        return ($m)->getManufacturerID($name);
    }

    public function updateManufacturers($shopItem, $manufacturers){
        $record = false;
        foreach ($manufacturers as $manufacturer) {
            if ($shopItem->MANUFACTURER == $manufacturer->name) $record = true;
        }
        if ($record == false) {
            $this->createManufacturer($shopItem->MANUFACTURER);
            $manufacturers = $this->getManufacturers();
        }
        return $manufacturers;
    }

    // ----------------- private -----------------

    private function createManufacturer($name){
        (new Manufacturer())->createManufacturer($name);
    }

}
