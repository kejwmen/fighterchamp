<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FightControllerTest extends WebTestCase
{
    public function testDisplay()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/walki');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Zawodnik', $crawler->text());
    }
}
