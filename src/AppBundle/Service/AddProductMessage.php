<?php

namespace AppBundle\Service;

use AppBundle\Constant\BotMessageType;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use pimax\Messages\StructuredMessage;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("bot.add_product_message")
 */
class AddProductMessage extends BotMessage
{
    const THE_OPENING_SENTENCE = 'Product has been added to the cart';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     * })
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param CustomerMessage $customerMessage
     * @return array
     * @throws \Exception
     */
    protected function getMessageTemplate(CustomerMessage $customerMessage)
    {
        $this->addProductToUserCart($customerMessage);
        return [
            new QuickReply($customerMessage->getSender()->getFacebookRecipientId(),
                self::THE_OPENING_SENTENCE,
                [
                    new QuickReplyButton(QuickReplyButton::TYPE_TEXT,  BotMessageType::SHOW_PRODUCTS_TEXT, BotMessageType::SHOW_PRODUCTS_PAYLOAD),
                    new QuickReplyButton(QuickReplyButton::TYPE_TEXT, BotMessageType::SHOW_CART_TEXT, BotMessageType::SHOW_CART_PAYLOAD),
                ]
            )
        ];
    }

    /**
     * @param CustomerMessage $customerMessage
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function addProductToUserCart(CustomerMessage $customerMessage)
    {
        /** @var Customer $customer */
        $customer = $this->em->getRepository('AppBundle:Customer')->findOneBy([
            'id' => explode("|",$customerMessage->getMessage())[1]
        ]);
        /** @var Product $product */
        $product = $this->em->getRepository('AppBundle:Product')->findOneBy([
            'id' => explode("|",$customerMessage->getMessage())[2]
        ]);
        $cartItem = $this->em->getRepository('AppBundle:Cart')->checkProductExistence($product, $customer);
        if($cartItem){
           /** Cart $item */
           $quantity = $cartItem->getQuantity();
           $cartItem->setQuantity(++$quantity);
        }else{
            $cartItem = new Cart();
            $cartItem->setCustomer($customer);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
        }
        $this->em->persist($cartItem);
        $this->em->flush($cartItem);
    }


}