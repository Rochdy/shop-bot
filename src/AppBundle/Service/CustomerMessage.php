<?php

namespace AppBundle\Service;

use AppBundle\Entity\Customer;

class CustomerMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var Customer
     */
    private $sender;

    /**
     * @var integer
     */
    private $type;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return Customer
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param Customer $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}