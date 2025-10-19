<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use toubilib\api\middlewares\Cors;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ );
$dotenv->load();

$cBuilder = new ContainerBuilder();

$cBuilder->addDefinitions([
    'displayErrorDetails' => $_ENV['DISPLAY_ERROR_DETAILS'] ?? true,
]);
$cBuilder->addDefinitions(__DIR__ . '/services.php');
$cBuilder->addDefinitions(__DIR__ . '/../src/application_core/application/ports/api/api.php');

$c = $cBuilder->build();
AppFactory::setContainer($c);
$app = AppFactory::create();


$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false)
    ->getDefaultErrorHandler()
    ->forceContentType('application/json')
;

$app = (require_once __DIR__ . '/../src/api/routes.php')($app);


return $app;