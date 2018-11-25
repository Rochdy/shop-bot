<?php

namespace AppBundle\Service;

use AppBundle\Constant\BotMessageType;
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
 * @DI\Service("bot.remove_product_message")
 */
class RemoveProductMessage extends BotMessage
{
    const THE_OPENING_SENTENCE = 'Product has been removed from the cart';
    const NOT_FOUND_ITEM = 'Product not found in your cart';

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
        $removedItem = $this->removeProductFromUserCart($customerMessage);
        $reply = ($removedItem) ? self::THE_OPENING_SENTENCE : self::NOT_FOUND_ITEM;
        return [
            new QuickReply($customerMessage->getSender()->getFacebookRecipientId(),
                $reply,
                [
                    new QuickReplyButton(QuickReplyButton::TYPE_TEXT,  BotMessageType::SHOW_PRODUCTS_TEXT, BotMessageType::SHOW_PRODUCTS_PAYLOAD),
                    new QuickReplyButton(QuickReplyButton::TYPE_TEXT, BotMessageType::SHOW_CART_TEXT, BotMessageType::SHOW_CART_PAYLOAD),
                ]
            )
        ];
    }

    /**
     * @param $customerMessage
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function removeProductFromUserCart($customerMessage)
    {
        $cartItem = $this->em->getRepository('AppBundle:Cart')->findOneBy([
            'id' => explode("|",$customerMessage->getMessage())[1]
        ]);
        if($cardItem){
            $this->em->remove($cartItem);
            $this->em->flush($cartItem);
            return true;
        }
        return false;
    }
}