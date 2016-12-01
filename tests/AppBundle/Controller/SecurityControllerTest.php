<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 29.11.16
 * Time: 17:48
 */

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SecurityControllerTest extends WebTestCase
{
        public function testNewUserRegister()
        {
            $client = static::createClient();
            $crawler = $client->request('GET', '/rejestracja');

            $this->assertEquals(200, $client->getResponse()->getStatusCode());

            //dump($crawler);

            $form = $crawler->selectButton('Zarejestruj się')->form();

            //dump($form);

            $form['registration[name]'] = 'Mario';
            $crawler = $client->submit($form);

            $data = $form->getPhpValues();
            $expected = array('send' => 'Send', 'name' => 'Mario');
            //$this->assertEquals($expected,$data);

          //  dump($data);

        }
}
