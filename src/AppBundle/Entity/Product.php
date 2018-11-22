<?php

namespace AppBundle\Entity;

use AppBundle\Constant\BotMessageType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use pimax\Messages\MessageButton;
use pimax\Messages\MessageElement;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @var Integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="product", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    private $carts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param Customer $customer
     * @return MessageElement
     */
    public function getTemplate(Customer $customer)
    {
        return new MessageElement(
            ucwords($this->getName()),
            "Price: {$this->getPrice()}",
            '',
            [
                new MessageButton(MessageButton::TYPE_POSTBACK,
                    BotMessageType::ADD_TO_CART_TEXT,
                    BotMessageType::convertPayload(BotMessageType::ADD_TO_CART_PAYLOAD, [
                        'product' => $this->getId(),
                        'customer' => $customer->getId()
                        ])
                )
            ]
        );
    }

    public function __toString(){
        return (string) $this->getName();
    }
}
