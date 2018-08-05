<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booklist
 *
 * @ORM\Table(name="booklist")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BooklistRepository")
 */
class Booklist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="bookname", type="string", length=255)
     */
    private $bookname;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bookname
     *
     * @param string $bookname
     *
     * @return Booklist
     */
    public function setBookname($bookname)
    {
        $this->bookname = $bookname;

        return $this;
    }

    /**
     * Get bookname
     *
     * @return string
     */
    public function getBookname()
    {
        return $this->bookname;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Booklist
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Booklist
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }
}

