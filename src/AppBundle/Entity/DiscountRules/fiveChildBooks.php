<?php
namespace AppBundle\Entity\DiscountRules;

use AppBundle\Entity\Booklist;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartBookItem; 

use AppBundle\Entity\DiscountRules\DiscountRule; 
use AppBundle\Entity\DiscountRules\DiscountDetail; 
 
class fiveChildBooks implements DiscountRule{
    public function calculateDiscount(Cart $cart){
    	
    	$bookItems = $cart->getCartBookItems();
    	$cat_name = 'children';
    	$quantity = 0;
    	$totalPrice = 0;
    	foreach ($bookItems as $key => $bookItem) {
    		if(strtolower($cat_name) === strtolower($bookItem->getCategory())){
    			$quantity += $bookItem->getQuantity();
    			$totalPrice += $bookItem->getQuantity()*$bookItem->getPrice();
    		}
    	}
    	if($quantity>=5){
    		return new DiscountDetail("More than 5 Children category books in cart",$totalPrice*0.10 );
    		
    	}else{
    		return new DiscountDetail("Sorry, Not eligible for any discount", 0.00);
    	}



    			
    	return null;

    }
}

?>