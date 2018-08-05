<?php

namespace AppBundle\Entity;
use AppBundle\Entity\DiscountRules\fiveChildBooks;
use AppBundle\Entity\DiscountRules\tenEachCat;
use AppBundle\Entity\DiscountRules\CouponCodeDiscountRule;

class Cart
{
    protected $totalPrice;
    protected $cartBookItems;
    private $validCoupons = ['save50', 'save20'];
    private $appliedCoupons = [];


    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    public function getCartBookItems()
    {
        return $this->cartBookItems;
    }

    public function setCartBookItems($cartBookItems)
    {
        $this->cartBookItems = $cartBookItems;
    }

    public function getDiscountDetails()
    {
        if(empty($this->appliedCoupons)){
            $discount_arr = array(new fiveChildBooks(), new tenEachCat());
        }else{
            $discount_arr = array( new CouponCodeDiscountRule());
        }
        
        $applied_discounts = array();
        foreach ($discount_arr as $key => $discount) {
            array_push($applied_discounts,  $discount->calculateDiscount($this));
           
        }
        return  $applied_discounts;
    }

    public function getTotalDiscount()
    {
        $discount_arr = $this->getDiscountDetails();
        $discount_value = 0;
        foreach ($discount_arr as $key => $value) {
            $discount_value += $value->value;
        }
        return $discount_value;
    }

    public function getDiscountedTotalPrice()
    {
        return $this->getTotalPrice()-$this->getTotalDiscount();
    }

    public function setCouponCode($code)
    {
        if(in_array ( $code , $this->validCoupons)){
            if (!in_array( $code,  $this->appliedCoupons)){
                $this->appliedCoupons[] = $code; 
                return true;
            }
        }
        return false;
    }

    public function getAppliedCoupons()
    {
        return $this->appliedCoupons;
    }





}