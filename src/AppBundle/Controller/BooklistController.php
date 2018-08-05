<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booklist;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartBookItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\StringType;


class BooklistController extends Controller
{

    /**
     * @Route("/", name="Book_list")
     */
    public function listAction()
    {
        $Booklist = $this->getDoctrine()
                ->getRepository('AppBundle:Booklist')
                ->findAll();

        $booksWithForm = [];
        foreach ($Booklist  as $key => $bookItem){

            $defaultData = array('quantity' => 1);
            $form = $this->createFormBuilder($defaultData)
            ->setAction($this->generateUrl("Book_details",['id'=>$bookItem->getid()]))
            ->add('quantity', IntegerType::class)
            ->add('Add to Cart', SubmitType::class, ['attr' => array('class' => 'btn btn-success')])
            ->getForm();

            $booksWithForm[] = ['book'=> $bookItem , 'form'=> $form->createView()];
        }

        $session = new Session();
        $sessionCart = $session->get('sessionCart');
        if ($sessionCart == NULL) {
            return $this->render('books/index-empty.html.twig', array(
            'booklist' => $booksWithForm
        ));
        }

        // replace this example code with whatever you need
        return $this->render('books/index.html.twig', array(
            'booklist' => $booksWithForm, 'sessioncart' => $sessionCart
        ));
    }


    /**
     * @Route("/Booklist/addCoupon", name="Book_add_coupon")
     */
    public function addCoupon(Request $request)
    {
        
        $form = $request->request->get('form');
        
        $session = new Session();
        $sessionCart = $session->get('sessionCart');
        $cart = $sessionCart;

        $cart->setCouponCode($form['code']);

        var_dump($form['code']);
        $session->set('sessionCart', $cart);
        $sessionCart = $cart;
        // replace this example code with whatever you need
        return $this->redirectToRoute('Book_invoice');
    }


    /**
     * @Route("/Booklist/edit", name="Book_edit")
     */
    public function editAction(Request $request)
    {
        $Booklist = $this->getDoctrine()
                ->getRepository('AppBundle:Booklist')
                ->findAll();
        $session = new Session();
        $sessionCart = $session->get('sessionCart');
        $bookTotalPrice = 0;

        $booksWithForm = [];
        $cart = $sessionCart;
        if($sessionCart != null) {
            $cartBookItems = $sessionCart->getCartBookItems();
            
            foreach ($cartBookItems  as $key => $bookItem){

                $defaultData = array('quantity' => $bookItem->getQuantity(),
                                    'bookID' => $bookItem->getbookID()   );
                $editform = $this->createFormBuilder($defaultData)
                ->setAction($this->generateUrl("Book_update"))
                ->add('quantity', IntegerType::class)
                ->add('bookID', HiddenType::class)
                ->add('Update', SubmitType::class, ['attr' => array('class' => 'btn btn-success')])
                ->getForm();


                $defaultDataDelete = array('bookID' => $bookItem->getbookID()   );
                $deleteform = $this->createFormBuilder($defaultDataDelete)
                ->setAction($this->generateUrl("Book_delete"))
                ->add('bookID', HiddenType::class)
                ->add('Delete', SubmitType::class, ['attr' => array('class' => 'btn btn-success')])
                ->getForm();

                $booksWithForm[] = ['book'=> $bookItem , 'editform'=> $editform->createView(),
                                        'deleteform'=> $deleteform->createView()];
            }
        } else {
            return $this->render('books/edit-empty.html.twig' , array(
            'booklist' => $booksWithForm
        ));
        }

        // replace this example code with whatever you need
        return $this->render('books/edit.html.twig' , array(
            'booklist' => $booksWithForm, 'sessioncart' => $sessionCart
        ));

    }


    /**
     * @Route("/Booklist/invoice", name="Book_invoice")
     */
    public function invoiceAction(Request $request)
    {
        
        $session = new Session();
        $sessionCart = $session->get('sessionCart');
        $bookTotalPrice = 0;
        $booksWithForm = [];
        $cart = $sessionCart;
        if($sessionCart != null) {
            $cartBookItems = $sessionCart->getCartBookItems();
            
            foreach ($cartBookItems  as $key => $bookItem){
                $bookTotalPrice += $bookItem->getQuantity()*$bookItem->getPrice();
                $booksWithForm[] = ['book'=> $bookItem];
            }
        } else {
            
            return $this->render('books/invoice-empty.html.twig' , array('booklist' => $booksWithForm, 
                'sessioncart' => $sessionCart
            ));
        }

        $cart->setTotalPrice($bookTotalPrice);
        $cart->setCartBookItems($cartBookItems);
        $session->set('sessionCart', $cart);
        $sessionCart = $cart;
       
        $couponformData = array('code' => '');
        $couponform = $this->createFormBuilder($couponformData)
        ->setAction($this->generateUrl("Book_add_coupon"))
        ->add('code', TextType::class)
        ->add('Update', SubmitType::class, ['attr' => array('class' => 'btn btn-success')])
        ->getForm();

        // replace this example code with whatever you need
        return $this->render('books/invoice.html.twig' , array(
            'booklist' => $booksWithForm, 'sessioncart' => $sessionCart, 'couponform'=>$couponform->createView()
        ));

    }
     /**
     * @Route("/Booklist/delete", name="Book_delete")
     */
    public function DeleteAction(Request $request)
    {
        $form = $request->request->get('form');

        $session = new Session();
        $sessionCart = $session->get('sessionCart');
        $booksWithForm = [];
        $cart = $sessionCart;
        $cartBookItems = $sessionCart->getCartBookItems();
        

        foreach ($cartBookItems  as $key => $bookItem){
            if($form['bookID'] ==  $bookItem->getBookID()){
                
                unset($cartBookItems[$key]);
            }   
        }

        $cart->setCartBookItems($cartBookItems);
        var_dump($cart);

        return $this->redirectToRoute('Book_edit');
    }

    /**
     * @Route("/Booklist/update", name="Book_update")
     */
    public function UpdateAction(Request $request)
    {
             
        $form = $request->request->get('form');

        $session = new Session();
        $sessionCart = $session->get('sessionCart');
        $booksWithForm = [];
        $cart = $sessionCart;
        $cartBookItems = $sessionCart->getCartBookItems();

        

        foreach ($cartBookItems  as $key => $bookItem){
                if($form['bookID'] ==  $bookItem->getBookID()){
                    $newQuant = $form['quantity'];
                    
                    $bookItem->setQuantity($newQuant);
                }   
            }
            var_dump($cartBookItems);
        

        // replace this example code with whatever you need
            return $this->redirectToRoute('Book_edit');


    }

    /**
     * @Route("/Booklist/details/{id}", name="Book_details")
     */
    public function detailsAction($id, Request $request)
    {
        $book = $this->getDoctrine()
                ->getRepository('AppBundle:Booklist')
                ->find($id);   
        $form = $request->request->get('form');

        $quantity = $form['quantity'];
        $session = new Session();
        $sessionCart = $session->get('sessionCart');
        $cartBookItems = array();

        $bookTotalPrice = $book->getPrice() * $quantity;
        $newTotalPrice = 0;
        $cart = $sessionCart;
        if($sessionCart != null) {
            $cartBookItems = $sessionCart->getCartBookItems();
            $currentTotalPrice = $sessionCart->getTotalPrice();
            $newTotalPrice = $currentTotalPrice + $bookTotalPrice;
            $isItemAlreadyExists = false;

            foreach($cartBookItems as $currentCartBookItem ) {
                if($currentCartBookItem->getBookID() == $book->getId()) {
                    $newQuantity = $currentCartBookItem->getQuantity() + $quantity;
                    $currentCartBookItem->setQuantity($newQuantity);
                    $isItemAlreadyExists = true;             
                }
            }
            if(!$isItemAlreadyExists) {
                $cartBookItem = new CartBookItem($book->getId(), $quantity, $book->getBookname(), $book->getPrice(), $book-> getCategory()) ;
                array_push($cartBookItems, $cartBookItem);
            }
        } else {
            $newTotalPrice = $bookTotalPrice;
            $cart = new Cart();
            $cartBookItems = array();
            $cartBookItem = new CartBookItem($book->getId(), $quantity, $book->getBookname(), $book->getPrice(), $book-> getCategory());
            array_push($cartBookItems, $cartBookItem);
        }
        $cart->setTotalPrice($newTotalPrice);
        $cart->setCartBookItems($cartBookItems);
        $session->set('sessionCart', $cart);
        $sessionCart = $cart;

        // replace this example code with whatever you need
        return $this->render('books/details.html.twig', array('book' => $book, 'quantity' => $quantity, 'sessioncart' => $sessionCart,));
    }

    /**
     * @Route("/Booklist/detailsup", name="Book_detailsup")
     */
    public function detailsupAction(Request $request)
    {
        $session = new Session();
        $sessionCart = $session->get('sessionCart');


        if ($sessionCart == NULL) {
            return $this->render('books/detailsup-empty.html.twig', array(
        ));
        }
        // replace this example code with whatever you need
        return $this->render('books/details.html.twig', array('sessioncart' => $sessionCart,));
    }
}