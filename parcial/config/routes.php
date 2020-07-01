<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsersController;
use App\Middleware\BeforeMiddleware;
use App\Middleware\ValidateMiddleware;
use App\Middleware\UserBefore;
use App\Middleware\MateriasPosMiddleware;

use App\Middleware\LoginBefore;
use App\Middleware\MateriasPostMiddleware;
use App\Controllers\MateriasController;
use App\Middleware\MateriasGetMiddleware;

use function PHPSTORM_META\argumentsSet;

return function ($app) {

    $app->post('/usuario', UsersController::class . ':add')
    ->add(ValidateMiddleware::class)
    ->add(UserBefore::class);
    
    $app->post('/login', UsersController::class . ':login')
    ->add(ValidateMiddleware::class)
    ->add(LoginBefore::class);  
   
    $app->group('/materias', function (RouteCollectorProxy $group) {
        
        $group->post('[/]', MateriasController::class . ':add')->add(MateriasPostMiddleware::class)->add(ValidateMiddleware::class);
        $group->get('/{id}',MateriasController::class . ':get')->add(MateriasGetMiddleware::class);
        $group->get('[/]',MateriasController::class . ':get')->add(MateriasGetMiddleware::class);
        
    
    })->add(BeforeMiddleware::class);

 

};