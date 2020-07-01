<?php
use Slim\App;
use App\Middleware\BeforeMiddleware;
use App\Middleware\AfterMiddleware;
use App\Middleware\ValidateMiddleware;
use App\Middleware\UserBefore;


return function (App $app) {
    $app->addBodyParsingMiddleware();

  
   $app->add(AfterMiddleware::class);
  
    
    
};