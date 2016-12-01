<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 29.11.16
 * Time: 23:50
 */

namespace AppBundle\Tests;


use AppBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RegistrationTypeTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $userRepo = $em->getRepository('AppBundle:User');
        $userRepo->createQueryBuilder('user')
            ->delete()
            ->getQuery()
            ->execute();

        $crawler = $client->request('GET', '/rejestracja');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Zarejestruj się')->form();
        $crawler = $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp(
            '/Ta wartość nie powinna być pusta/',
        $client->getResponse()->getContent());

        $form = $crawler->selectButton('Zarejestruj się')->form();

        $form['registration[email]'] = 'user@company.com';
        $form['registration[plain_password][first]'] = 'somePassword';
        $form['registration[plain_password][second]'] = 'somePassword';
        $form['registration[male]'] = true;
        $form['registration[name]'] = 'Name';
        $form['registration[surname]'] = 'Surname';
        $form['registration[birthDay][day]'] = 1;
        $form['registration[birthDay][month]'] = 1;
        $form['registration[birthDay][year]'] = 2000;
        $form['registration[phone]'] = '844 00 00';
        $form['registration[terms]'] = true;

        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertContains(
        'Sukces! Twój profil został utworzony! Jesteś zalogowany!',
            $client->getResponse()->getContent()
            );
    }
}
