<?php

namespace AppBundle\Command;

use pimax\FbBotApp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class setGetStartButton extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('set:chat:get_started_button')
            ->setDescription('Set get started payload')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $getStartedButtonPayload = $this->getContainer()->getParameter('get_started_payload');
        $fbAccessToken = $this->getContainer()->gemotParameter('facebook_app_access_token');
        $bot = new FbBotApp($fbAccessToken);
        try{
            $bot->setGetStartedButton($getStartedButtonPayload);
            $output->writeln("<comment>[INFO]</comment> Getting started button payload: ${getStartedButtonPayload}");
        } catch (\Exception $e) {
            $output->writeln(sprintf('<comment>[ERROR]</comment> <error>%s</error>', $e->getMessage()));
        }
    }
}