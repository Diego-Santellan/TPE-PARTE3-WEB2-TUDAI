<?php
    
    require_once 'libs/router.php';

    require_once 'app/controllers/property.api.controller.php';

    // require_once 'app/middlewares/jwt.auth.middleware.php';
    $router = new Router();

    // $router->addMiddleware(new JWTAuthMiddleware());

    #                       endpoint                           verbo                   controller                        método
    $router->addRoute('property'      ,           'GET',     'PropertyApiController',     'getAll');
    $router->addRoute('property/:id'  ,            'GET',     'PropertyApiController',    'get'   );
    // $router->addRoute('tareas/:id'  ,            'DELETE',  'TaskApiController',   'delete');
    $router->addRoute('property'  ,                'POST',    'PropertyApiController',     'create');
    $router->addRoute('property/:id'  ,            'PUT',     'PropertyApiController',     'update');
    // $router->addRoute('tareas/:id/finalizada'  , 'PUT',     'TaskApiController',   'setFinalize');
    
    // $router->addRoute('usuarios/token'    ,            'GET',     'UserApiController',   'getToken');

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
