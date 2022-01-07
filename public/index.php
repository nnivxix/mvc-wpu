<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Hanasa\MVC\Helper\Router;
use Hanasa\MVC\Controller\BlogController;
use Hanasa\MVC\Controller\HomeController;
use Hanasa\MVC\Middleware\AuthMiddleware;

Router::add('GET', '/blog/([0-9a-zA-Z._%+-]*)/category/([0-9a-zA-Z._%+-]*)', BlogController::class, 'blog');
Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/hello', HomeController::class, 'hello');
Router::add('GET', '/world', HomeController::class, 'world', );
Router::add('GET','/me',HomeController::class, 'me', [AuthMiddleware::class]);
Router::add('GET','/login',HomeController::class, 'login');

Router::run();
