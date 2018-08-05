<?php
namespace AppBundle\Entity\DiscountRules;

use AppBundle\Entity\Booklist;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartBookItem; 

class DiscountDetail {


    public $description;


    public $value;


    public function __construct(String $description, float $value){
    	$this->description = $description;
    	$this->value = $value;
    }


}
 
