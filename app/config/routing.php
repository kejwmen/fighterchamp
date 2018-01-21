<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    // import routes defined in a separate file
    $routes->import('legacy_routes.php')
        ->prefix('/legacy')
    ;

    // define the routes using a "fluent interface"
    $routes
        ->add('product', '/products/{id}')
        ->controller('App\Controller\ProductController::show')
        ->schemes(['https'])
        ->requirements(['id' => '\d+'])
        ->defaults(['id' => 0])

        ->add('homepage', '/')
        ->controller('App\Controller\DefaultController::index')
    ;
};