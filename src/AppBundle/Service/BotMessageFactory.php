<?php

namespace AppBundle\Service;

use AppBundle\Constant\BotMessageType;
use AppBundle\Constant\CustomerMessageType;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("bot.message.factory")
 */
class BotMessageFactory
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var WelcomeMessage
     */
    protected $welcomeMessage;

    /**
     * @var ProductMessage
     */
    protected $productsMessage;

    /**
     * @var DefaultMessage
     */
    protected $defaultMessage;

    /**
     * @var CartMessage
     */
    protected $cartMessage;

    /**
     * @var AddProductMessage
     */
    protected $addProductMessage;

    /**
     * @var RemoveProductMessage
     */
    protected $removeProductMessage;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "welcomeMessage" = @DI\Inject("bot.welcome_message"),
     *     "productsMessage" = @DI\Inject("bot.products_message"),
     *     "defaultMessage" = @DI\Inject("bot.default_message"),
     *     "cartMessage" = @DI\Inject("bot.cart_message"),
     *     "addProductMessage" = @DI\Inject("bot.add_product_message"),
     *     "removeProductMessage" = @DI\Inject("bot.remove_product_message"),
     * })
     *
     * @param EntityManager $em
     * @param WelcomeMessage $welcomeMessage
     * @param ProductMessage $productsMessage
     * @param DefaultMessage $defaultMessage
     * @param CartMessage $cartMessage
     * @param AddProductMessage $addProductMessage
     * @param RemoveProductMessage $removeProductMessage
     */
    public function __construct(EntityManager $em, WelcomeMessage $welcomeMessage, ProductMessage $productsMessage, DefaultMessage $defaultMessage, CartMessage $cartMessage, AddProductMessage $addProductMessage, RemoveProductMessage $removeProductMessage)
    {
        $this->em = $em;
        $this->welcomeMessage = $welcomeMessage;
        $this->productsMessage = $productsMessage;
        $this->defaultMessage = $defaultMessage;
        $this->cartMessage = $cartMessage;
        $this->addProductMessage = $addProductMessage;
        $this->removeProductMessage = $removeProductMessage;
    }

    /**
     * @param CustomerMessage $customerMessage
     * @return CartMessage|DefaultMessage|ProductMessage|WelcomeMessage|AddProductMessage|RemoveProductMessage
     */
    public function build (CustomerMessage $customerMessage)
    {
        $message = strtolower($customerMessage->getMessage());
        if(strpos($message, BotMessageType::WELCOME_INCLUDE) !== false)
            return $this->welcomeMessage;
        elseif(strpos($message, BotMessageType::SHOW_PRODUCTS_INCLUDE) !== false)
            return $this->productsMessage;
        elseif (strpos($message, BotMessageType::SHOW_CART_INCLUDE) !== false)
            return $this->cartMessage;
        elseif (strpos($message, BotMessageType::ADD_TO_CART_INCLUDE) !== false && $customerMessage->getType() == CustomerMessageType::PAYLOAD)
            return $this->addProductMessage;
        elseif (strpos($message, BotMessageType::REMOVE_FROM_CART_INCLUDE) !== false && $customerMessage->getType() == CustomerMessageType::PAYLOAD)
            return $this->removeProductMessage;
        else
            return $this->defaultMessage;
    }
}