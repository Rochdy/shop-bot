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
 * @DI\Service("bot.default_message")
 */
class DefaultMessage extends BotMessage
{
    const THE_OPENING_SENTENCE = 'Sorry, I Can\'t Understand you. What exactly do you want?';

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
        return [
            new StructuredMessage($customerMessage->getSender()->getFacebookRecipientId(),
                StructuredMessage::TYPE_BUTTON,
                [
                    'text' => self::THE_OPENING_SENTENCE,
                    'buttons' => [
                        new MessageButton(MessageButton::TYPE_POSTBACK, BotMessageType::SHOW_PRODUCTS_TEXT, BotMessageType::SHOW_PRODUCTS_PAYLOAD),
                        new MessageButton(MessageButton::TYPE_POSTBACK, BotMessageType::SHOW_CART_TEXT, BotMessageType::SHOW_CART_PAYLOAD),
                    ]
                ]
            )
        ];
    }
}