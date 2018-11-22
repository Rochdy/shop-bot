<?php

namespace AppBundle\Service;

use AppBundle\Constant\BotMessageType;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("bot.products_message")
 */
class ProductMessage extends BotMessage
{
    const THE_OPENING_SENTENCE = 'Our Products:';
    const EMPTY_SENTENCE = 'There\'s no products right now!';

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
        $products = $this->em->getRepository('AppBundle:Product')->findAll();
        $structuredProducts = [];
        /** @var Product $product */
        foreach ($products as $product) {
            $structuredProducts[] = $product->getTemplate($customerMessage->getSender());
        }
        if(!empty($structuredProducts))
        {
            return [
                new Message($customerMessage->getSender()->getFacebookRecipientId(),
                    self::THE_OPENING_SENTENCE
                ),
                new StructuredMessage($customerMessage->getSender()->getFacebookRecipientId(),
                    StructuredMessage::TYPE_GENERIC,
                    [
                        'elements' => $structuredProducts
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