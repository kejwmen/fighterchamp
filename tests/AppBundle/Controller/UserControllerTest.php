<?php

namespace Tests\AppBundle\Controller;

use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testShowPost()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Turnieje")')->count()
        );
    }

    public function testUserFormRegister()
    {
//        $crawler = $this->client->request('GET', '/ludzie/rejestracja');
//
//
//        $form = $crawler->selectButton('submit')->form();
//
//        $form['user[email]'] = 'Lucas';
//
//        $crawler = $this->client->submit($form);
//
//
//        var_dump($crawler->extract(['_text']));
//
//        $this->assertGreaterThan(1, $crawler->filter('html:contains("Ta wartość nie jest prawidłowym adresem email.")')->count());

        $crawler = $this->client->request('GET', '/ludzie/rejestracja');

        $token = $crawler->filter('[name="foo[_token]"]')->attr("value");

        $posturl = '/user-create';

        $crawler = $this->client->request('POST', $posturl, array(
            'myform' => array(
                '_token' => $token
            )),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );



    }


//    public function testFoo()
//    {
//        $crawler = $this->client->request('GET', '/testowe');
//
//        $form = $crawler->selectButton('submit')->form();
//
//        $form['foo[name]'] = 'Lucas';
//
//        $crawler = $this->client->submit($form);
//
//
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ta wartość nie jest prawidłowym adresem email.")')->count());
//    }

}
