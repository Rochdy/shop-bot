<?php

namespace AppBundle\Service;

use AppBundle\Entity\Customer;
use pimax\FbBotApp;

abstract class BotMessage
{
    abstract protected function getMessageTemplate(CustomerMessage $customerMessage);

    public function send(FbBotApp $fbChatBot ,CustomerMessage $customerMessage)
    {
        foreach ($this->getMessageTemplate($customerMessage) as $message)
        {
            try{
                $fbChatBot->send($message);
            }catch (\Exception $e)
            {
            }
        }
    }
}