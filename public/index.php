<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Hanasa\MVC\App\Router;
use Hanasa\MVC\Controller\BlogController;
use Hanasa\MVC\Controller\HomeController;

Router::add('GET', '/blog/([0-9a-zA-Z._%+-]*)/category/([0-9a-zA-Z._%+-]*)', BlogController::class, 'blog');
Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/hello', HomeController::class, 'hello');
Router::add('GET', '/world', HomeController::class, 'world');
Router::add('GET','/me',HomeController::class, 'me');

Router::run();
