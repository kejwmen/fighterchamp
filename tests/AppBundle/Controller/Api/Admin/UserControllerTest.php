<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 5/26/18
 * Time: 8:49 PM
 */

namespace Tests\AppBundle\Controller\Api\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRequiresAuthentication()
    {
        $client = static::createClient();

        $client->request('GET','api/admin/users');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}
