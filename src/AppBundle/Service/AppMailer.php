<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 19.09.16
 * Time: 11:49
 */

namespace AppBundle\Service;


class AppMailer
{

    private $mailer;
    public function __construct($_mailer) {
        $this->mailer = $_mailer;
    }

    public function sendEmail($to, $subject, $text)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('automat@fighterchamp.pl', 'FighterChamp')
            ->setTo($to)
            ->setBody($text, 'text/html')
        ;
        return $this->mailer->send($message);
            }
}