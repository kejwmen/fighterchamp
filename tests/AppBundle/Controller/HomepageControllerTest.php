<?php


namespace Tests\AppBundle\Controller;




use AppBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    public function testDisplay()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();

//        $user = new User();
//        $user->setName('Mariusz');
//        $user->setSurname('Golonka');
//        $user->setHash('duupaaa');
//        $user->setMale(true);
//        $user->setEmail('mario@o2.pl');
//
//        $em->persist($user);
//        $em->flush();
//
//        $client->request('POST', '/api/dupa');
//
//        $client->request(
//            'POST',
//            '/api/dupa',
//            [],
//            [],
//            [
//                'CONTENT_TYPE'          => 'application/json',
//                'HTTP_X-Requested-With' => 'XMLHttpRequest'
//            ],
//            json_encode(['name' => 'Mariusz Słonko'])
//        );
//
//
//        $html = $client->getResponse()->getContent();
//
//
//        var_dump($html);
//
//        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testDisplay2()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
