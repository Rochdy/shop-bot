<?php

namespace AppBundle\Entity;

use AppBundle\Constant\BotMessageType;
use Doctrine\ORM\Mapping as ORM;
use pimax\Messages\MessageButton;
use pimax\Messages\MessageElement;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 * @ORM\Table(name="carts")
 * @ORM\HasLifecycleCallbacks()
 */
class Cart
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Customer", inversedBy="cart")
     */
    private $customer;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Product", inversedBy="carts")
     */
    private $product;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" = 1})
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }
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
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get message template
     *
     * @return MessageElement
     */
    public function getTemplate()
    {
        return new MessageElement(
            ucwords($this->getProduct()->getName()),
            "x{$this->getQuantity()} | Total Price: {$this->getTotalPrice()}",
            '',
            [
                new MessageButton(MessageButton::TYPE_POSTBACK,
                    BotMessageType::REMOVE_FROM_CART_TEXT,
                    BotMessageType::convertPayload(BotMessageType::REMOVE_FROM_CART_PAYLOAD, [
                        'item' => $this->getId()
                    ])
                )
            ]
        );
    }

    /**
     * Calculate total price of the cart item
     *
     * @return float|int
     */
    private function getTotalPrice()
    {
        return $this->getQuantity() * $this->getProduct()->getPrice();
    }

    public function __toString(){
        return (string) $this->getId();
    }
}
