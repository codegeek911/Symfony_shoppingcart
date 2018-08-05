<?php

namespace AppBundle\Entity;

class CartBookItem
{

    function __construct($bookID, $quantity, $name, $price, $category) {
        $this->bookID = $bookID;
        $this->quantity = $quantity;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    protected $bookID;
    protected $quantity;
    protected $name;
    protected $price;
    protected $category;

    public function getBookID()
    {
        return $this->bookID;
    }

    public function setBookID($bookID)
    {
        $this->bookID = $bookID;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity = 1)
    {
        $this->quantity = $quantity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }


}