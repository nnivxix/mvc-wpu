<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Hanasa\MVC\Helper\Router;
use Hanasa\MVC\Controller\HomeController;

Router::add('GET', '/', HomeController::class, 'index', []);

Router::run();
