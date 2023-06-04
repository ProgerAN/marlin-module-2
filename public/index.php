<?php
if( !session_id() ) {
    session_start();
}

require "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$containerBuilder = new \DI\ContainerBuilder;

$containerBuilder->addDefinitions([
    \League\Plates\Engine::class => function() {
        return new \League\Plates\Engine('../app/views');
    },

    \PDO::class => function() {
        return new PDO("mysql:dbname=marlin_dev;host=localhost", "root", "root");
    },

    \Medoo\Medoo::class => function($container) {
        return new \Medoo\Medoo([
            'pdo' => $container->get('PDO'),
            'type' => 'mysql'
        ]);
    },
    
    \Delight\Auth\Auth::class => function($container){
        return new \Delight\Auth\Auth($container->get('PDO'));
    }
]);

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'index']);
    //$r->addRoute('GET', '/user/{id:\d+}', ['App\controllers\HomeController', 'users']);

    $r->addRoute('GET', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/registration', ['App\Controllers\AuthController', 'registration']);
    $r->addRoute('GET', '/logout', ['App\Controllers\AuthController', 'logout']);
    $r->addGroup('/auth', function(FastRoute\RouteCollector $r) {
        $r->addRoute('POST', '/logger', ['App\Controllers\AuthController', 'logger']);
        $r->addRoute('POST', '/register', ['App\Controllers\AuthController', 'register']);
    });

    $r->addRoute('GET', '/dashboard', ['App\Controllers\DashboardController', 'index']);

    $r->addGroup('/user', function(FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/add', ['App\Controllers\DashboardController', 'user_add']);
        $r->addRoute('POST', '/action-add', ['App\Controllers\DashboardController', 'user_action_add']);

        $r->addRoute('GET', '/edit/{id:\d+}', ['App\Controllers\DashboardController', 'user_edit']);
        $r->addRoute('POST', '/action-edit/{id:\d+}', ['App\Controllers\DashboardController', 'user_action_edit']);

        $r->addRoute('GET', '/secure/{id:\d+}', ['App\Controllers\DashboardController', 'user_secure']);
        $r->addRoute('POST', '/action-secure/{id:\d+}', ['App\Controllers\DashboardController', 'user_action_secure']);

        $r->addRoute('GET', '/status/{id:\d+}', ['App\Controllers\DashboardController', 'user_status']);
        $r->addRoute('POST', '/action-status/{id:\d+}', ['App\Controllers\DashboardController', 'user_action_status']);

        $r->addRoute('GET', '/avatar/{id:\d+}', ['App\Controllers\DashboardController', 'user_avatar']);
        $r->addRoute('POST', '/action-avatar/{id:\d+}', ['App\Controllers\DashboardController', 'user_action_avatar']);

        $r->addRoute('GET', '/delete/{id:\d+}', ['App\Controllers\DashboardController', 'user_delete']);
    });




});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        die('404');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($handler, $vars);
        break;
}
