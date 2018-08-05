<?php
namespace AppBundle\Entity\DiscountRules;

use AppBundle\Entity\Booklist;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartBookItem; 

interface DiscountRule {
    public function calculateDiscount(Cart $cart);
}
 


