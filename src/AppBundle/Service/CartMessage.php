<?php

namespace AppBundle\Service;

use AppBundle\Constant\BotMessageType;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\MessageElement;
use pimax\Messages\StructuredMessage;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("bot.cart_message")
 */
class CartMessage extends BotMessage
{
    const THE_OPENING_SENTENCE = 'Your Cart:';
    const EMPTY_SENTENCE = 'Your cart is empty';

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
        $customerCart = $customerMessage->getSender()->getCart();
        $structuredCart = [];
        /** @var Cart $item */
        foreach ($customerCart as $item) {
            $structuredCart[] = $item->getTemplate();
        }

        if(!empty($structuredCart))
        {
            return [
                new Message($customerMessage->getSender()->getFacebookRecipientId(),
                    self::THE_OPENING_SENTENCE
                ),
                new StructuredMessage($customerMessage->getSender()->getFacebookRecipientId(),
                    StructuredMessage::TYPE_GENERIC,
                    [
                        'elements' => $structuredCart
                    ]
                )
            ];
        }

        return [
            new Message($customerMessage->getSender()->getFacebookRecipientId(),
                self::EMPTY_SENTENCE
            )
        ];
    }
}