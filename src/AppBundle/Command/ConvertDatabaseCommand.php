<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertDatabaseCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:convert_database_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $em = $container->get('doctrine.orm.entity_manager');
        $fights = $em->getRepository('AppBundle:Fight')->findAll();

        foreach($fights as $fight){

            $signUps = $fight->getSignuptournament();

            foreach ($signUps as $signUp){
                $user = $signUp->getUser();
                $fight->addUser($user);
            }
        }
        $em->flush();
    }
}
