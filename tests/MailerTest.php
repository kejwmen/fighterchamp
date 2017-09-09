<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 9/8/17
 * Time: 6:30 PM
 */

namespace AppBundle\Tests\Libs;


use AppBundle\Service\AppMailer;
use Swift_Mailer;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailerTest extends KernelTestCase
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function setUp()
    {
        $transport = (new Swift_SmtpTransport('smtp.zenbox.pl', 587))
            ->setUsername('fighterchamp@fighterchamp.pl')
            ->setPassword('Cortez1634')
        ;


        $this->mailer = new Swift_Mailer($transport);
    }

    public function testAppMailer()
    {

        self::bootKernel();
        $appMailer = static::$kernel->getContainer()->get('AppBundle\Service\AppMailer');

        $numberOfSuccessfullRecipients = $appMailer->sendEmail();

        $this->assertEquals(1,$numberOfSuccessfullRecipients);
    }

    public function testAppMailerINstance()
    {
        $appMailer = new AppMailer($this->mailer);

        $appMailer->sendEmail();

    }


    public function testSendEmail()
    {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject('SwiftMailer')
                ->setFrom('fighterchamp@fighterchamp.pl', 'FighterChamp')
                ->setTo('slawomir.grochowski@gmail.com')
                ->setBody("siema siema", 'text/html');

            $numberOfSuccessfulSent = $this->mailer->send($message);

        }catch(\Throwable $e){
            var_dump($e);
        }
        $this->assertEquals(1, $numberOfSuccessfulSent);

    }





}
