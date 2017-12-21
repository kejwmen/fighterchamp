<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/20/17
 * Time: 4:57 PM
 */

namespace AppBundle\Form\EventListener;


use AppBundle\Entity\Club;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CreateClubIfDosentExist implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'preSubmitData');
    }

    public function preSubmitData(FormEvent $event)
    {
        $data = $event->getData();

        if (!$data) {
            return;
        }

        $clubId = $data['club'];


        if ($this->em->getRepository('AppBundle:Club')->find($clubId)) {
            return;
        }

        if(!$clubId) {
            return;
        }
        $clubName = $clubId;

        $club = new Club();
        $club->setName($clubName);
        $this->em->persist($club);
        $this->em->flush();

        $data['club'] = $club->getId();
        $event->setData($data);
    }
}