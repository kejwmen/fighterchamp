<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 29.11.16
 * Time: 15:27
 */

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class HomepageControllerTest extends WebTestCase
{
    public function testDisplay()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
