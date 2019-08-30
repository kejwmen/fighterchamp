<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 5/23/18
 * Time: 6:34 PM
 */

namespace Tests;

use Shopsys\HttpSmokeTesting\Auth\BasicHttpAuth;
use Shopsys\HttpSmokeTesting\HttpSmokeTestCase;
use Shopsys\HttpSmokeTesting\RequestDataSet;
use Shopsys\HttpSmokeTesting\RouteConfig;
use Shopsys\HttpSmokeTesting\RouteConfigCustomizer;
use Shopsys\HttpSmokeTesting\RouteInfo;
use Symfony\Component\HttpFoundation\Request;

class SmokeTest extends HttpSmokeTestCase
{

    protected function setUp()
    {
        parent::setUp();

        self::markTestSkipped("temp");

        static::bootKernel([
            'environment' => 'test',
            'debug' => true,
        ]);
    }

    protected function createRequest(RequestDataSet $requestDataSet)
    {
        $token = self::$kernel->getContainer()->get('lexik_jwt_authentication.encoder')->encode(['userId' => 1]);

        $uri = $this->getRouterAdapter()->generateUri($requestDataSet);

        $request = Request::create($uri);

        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $requestDataSet->getAuth()
            ->authenticateRequest($request);

        return $request;
    }


    protected function customizeRouteConfigs(RouteConfigCustomizer $routeConfigCustomizer)
    {
        $routeConfigCustomizer
            ->customize(function (RouteConfig $config, RouteInfo $info) {

                $redirectsRoute = [
                    'fights_not_weighted_remove',
                    'setWinner',
                    'toggleFightReady',
                    'setDay',
                    'set_is_paid',
                    'set_weighted',
                    'admin_mail',
                    'news',
                    'news_new',
                    'admin_user_new',
                    'connect_facebook',
                    'connect_facebook_check',
                    'logout',
                    'user_edit_view',
                ];

                $postRoute = [
                  'user_create',
                    'api_user_update',
                    'setNullOnImage',
                    'api_image_upload',
                    'admin_user_list'
                ];

                // This function will be called on every RouteConfig provided by RouterAdapter
                if ($info->getRouteName()[0] === '_') {
                    // You can use RouteConfig to change expected behavior or skip testing particular routes
                    $config->skipRoute();
                }


//                if($info->getRouteName() === 'admin_tournament_fights'){
//                    $config->addExtraRequestDataSet('Edit product that is a main variant (ID 149).')
//                        ->setParameter('id', 1);
//                }

                if($info->getRouteName() === 'tournament_show'){
                    $config->addExtraRequestDataSet('Edit product that is a main variant (ID 149).')
                        ->setParameter('id', 1);
                }


                if(in_array($info->getRouteName(), $redirectsRoute)) $config->skipRoute();
                if(in_array($info->getRouteName(), $postRoute)) $config->skipRoute();

                if($info->getRouteName() === 'user_create_view'){
                    $config->skipRoute('IS_AUTHENTICATED_FULLY');
                }


                if (!$info->isHttpMethodAllowed('GET')) {
                    $config->skipRoute('Only routes supporting GET method are tested.');
                }


            });
    }

}