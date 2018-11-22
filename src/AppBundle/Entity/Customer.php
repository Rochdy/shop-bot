<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 * @ORM\Table(name="customers")
 * @ORM\HasLifecycleCallbacks()
 */
class Customer
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
     * @var string
     *
     * @ORM\Column(name="facebook_recipient_id", type="string", length=50)
     */
    private $facebookRecipientId;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="customer", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    private $cart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cart = new ArrayCollection();
    }

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
     * @return string
     */
    public function getFacebookRecipientId()
    {
        return $this->facebookRecipientId;
    }

    /**
     * @param string $facebookRecipientId
     */
    public function setFacebookRecipientId($facebookRecipientId)
    {
        $this->facebookRecipientId = $facebookRecipientId;
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
     * @return ArrayCollection
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param ArrayCollection $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    public function __toString(){
        return (string) $this->getFacebookRecipientId();
    }
}
