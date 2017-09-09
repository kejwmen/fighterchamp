<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 19.09.16
 * Time: 11:49
 */

namespace AppBundle\Service;


use Swift_Mailer;

class AppMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(Swift_Mailer $_mailer)
    {
        $this->mailer = $_mailer;
    }

    public function sendEmail(): int
    {
            $message = \Swift_Message::newInstance()
                ->setSubject('AppMailer')
                ->setFrom('fighterchamp@fighterchamp.pl', 'FighterChamp')
                ->setTo('slawomir.grochowski@gmail.com')
                ->setBody("siema siema", 'text/html');
        ;
        $numberOfSuccessfullRecipients = $this->mailer->send($message);

        return $numberOfSuccessfullRecipients;
    }
}