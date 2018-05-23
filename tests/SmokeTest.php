<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 5/23/18
 * Time: 6:34 PM
 */

namespace Tests;


use Shopsys\HttpSmokeTesting\HttpSmokeTestCase;
use Shopsys\HttpSmokeTesting\RouteConfig;
use Shopsys\HttpSmokeTesting\RouteConfigCustomizer;
use Shopsys\HttpSmokeTesting\RouteInfo;

class SmokeTest extends HttpSmokeTestCase
{

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
                    'admin_user_list',
                    'admin_user_new',
                    'connect_facebook',
                    'connect_facebook_check',
                    'logout',
                    'user_edit_view',
                ];

                // This function will be called on every RouteConfig provided by RouterAdapter
                if ($info->getRouteName()[0] === '_') {
                    // You can use RouteConfig to change expected behavior or skip testing particular routes
                    $config->skipRoute('Route name is prefixed with "_" meaning internal route.');
                }
                if($info->getRouteParameterNames()) $config->skipRoute();
                if(in_array($info->getRouteName(), $redirectsRoute)) $config->skipRoute();


            });
    }

}