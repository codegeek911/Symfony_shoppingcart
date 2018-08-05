<?php
namespace AppBundle\Entity\DiscountRules;

use AppBundle\Entity\Booklist;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartBookItem; 

use AppBundle\Entity\DiscountRules\DiscountRule; 
use AppBundle\Entity\DiscountRules\DiscountDetail; 
 
class CouponCodeDiscountRule implements DiscountRule{
    public function calculateDiscount(Cart $cart){
    	
    	$appliedCoupons = $cart->getAppliedCoupons();

        if(in_array ( 'save50' ,$appliedCoupons)){
            return new DiscountDetail("Coupon Code save50 applied",$cart->getTotalPrice()*0.15 );
        }

    	return new DiscountDetail("Sorry, Not eligible for any discount", 0.00);
        

    }
}

?>