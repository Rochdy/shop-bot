<?php

namespace AppBundle\Service;

use AppBundle\Constant\BotMessageType;
use AppBundle\Constant\CustomerMessageType;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use pimax\FbBotApp;
use pimax\Messages\MessageButton;
use pimax\Messages\MessageElement;
use pimax\Messages\StructuredMessage;
use Symfony\Component\HttpFoundation\Request;

/**
 * @DI\Service("chatbot.manager")
 */
class FBMessengerManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FbBotApp
     */
    private $fbChatbot;

    /**
     * @var string
     */
    private $fbAppVerifyToken;

    /**
     * @var BotMessageFactory
     */
    private $botMessageFactory;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "fbAppAccessToken" = @DI\Inject("%facebook_app_access_token%"),
     *     "fbAppVerifyToken" = @DI\Inject("%facebook_app_verify_token%"),
     *     "botMessageFactory" = @DI\Inject("bot.message.factory")
     * })
     *
     * @param EntityManager $em
     * @param string $fbAppAccessToken
     * @param string $fbAppVerifyToken
     * @param BotMessageFactory $botMessageFactory
     */
    public function __construct(EntityManager $em , $fbAppAccessToken, $fbAppVerifyToken,BotMessageFactory $botMessageFactory)
    {
        $this->em = $em;
        $this->fbChatbot = new FbBotApp($fbAppAccessToken);
        $this->fbAppVerifyToken = $fbAppVerifyToken;
        $this->botMessageFactory = $botMessageFactory;
    }

    /**
     * Read customer message
     *
     * @param Request $request
     * @return CustomerMessage
     */
    public function getUserMessage(Request $request)
    {
        $data = json_decode($request->getContent(), true, 512, JSON_BIGINT_AS_STRING);
        $message = $data['entry'][0]['messaging'][0];
        $customerMessage = new CustomerMessage();
        $customer = $this->em->getRepository('AppBundle:Customer')->findOneOrCreate([
            'facebookRecipientId'=> $message['sender']['id']
        ]);
        $customerMessage->setSender($customer);
        if (!empty($message['message'])) {
            $customerMessage->setMessage($message['message']['text']);
            $customerMessage->setType(CustomerMessageType::TEXT);
        } else if (!empty($message['postback'])) {
            $customerMessage->setMessage($message['postback']['payload']);
            $customerMessage->setType(CustomerMessageType::PAYLOAD);
        }
        return $customerMessage;
    }

    /**
     * Reply to the customer message
     *
     * @param CustomerMessage $customerMessage
     */
    public function replyToCustomer(CustomerMessage $customerMessage)
    {
        $botMessage = $this->botMessageFactory->build($customerMessage);
        $botMessage->send($this->fbChatbot, $customerMessage);
    }
}