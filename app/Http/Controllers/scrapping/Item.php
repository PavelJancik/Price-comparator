<?php


class Item
{
    public  $name;
    public  $price;
    public  $link;
    public $skladem;

    public function __construct( $name,  $price,  $link, $skladem)
    {
        $this->price = $price;
        $this->name = $name;
        $this->link = $link;
        $this->skladem = $skladem;
    }

    function getAll(){
        return array(
            "name" => $this->name,
            "price" => $this->price,
            "link" => $this->link,
            "skladem" => $this->skladem,
        );
    }

}