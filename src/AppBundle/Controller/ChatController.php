<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends Controller
{
    /**
     * @Route("/", name="homepage", methods={"POST","PUT"})
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $chatBotManager = $this->get('chatbot.manager');
        $customerMessage = $chatBotManager->getUserMessage($request);
        $chatBotManager->replyToCustomer($customerMessage);
        return new Response('', 200);
    }
}
