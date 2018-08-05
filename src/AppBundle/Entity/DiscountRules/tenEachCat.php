<?php
namespace AppBundle\Entity\DiscountRules;

use AppBundle\Entity\Booklist;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartBookItem; 

use AppBundle\Entity\DiscountRules\DiscountRule; 
use AppBundle\Entity\DiscountRules\DiscountDetail; 
 
class tenEachCat implements DiscountRule{
    public function calculateDiscount(Cart $cart){
    	
    	$bookItems = $cart->getCartBookItems();
        $data = array();
    	$totalPrice = 0;
    	
        foreach($bookItems as $key => $bookItem){
            if(isset($data[$bookItem->getCategory()]) && !empty($data[$bookItem->getCategory()])){
                $data[$bookItem->getCategory()]=$data[$bookItem->getCategory()]+$bookItem->getQuantity();
            }else{
                $data[$bookItem->getCategory()]=$bookItem->getQuantity();
            }
        }
       
        foreach($data as $key => $quantity){
            
            if($quantity >= 10){
            return new DiscountDetail("More than 10 books from each category",$cart->getTotalPrice()*0.05 );
            
            }else{
            return new DiscountDetail("Sorry, Not eligible for any discount", 0.00);
        }

        }
        
    			
    	return new DiscountDetail("More than 10 books from each category", 1.00);

    }
}

?>