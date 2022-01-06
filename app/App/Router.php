<?php

namespace Hanasa\MVC\App;

class Router
{
  private static array $routes = [];

  public static function add(string $method, string $path, string $controller, string $function)
  {
    self::$routes[] = [
      'method' => $method,
      'path' => $path,
      'controller' => $controller,
      'function' => $function
    ];
  }

  public static function run() :void
  {
    $path = '/';
    if (isset($_SERVER['PATH_INFO'])) {
      $path = $_SERVER['PATH_INFO'];
      // var_dump($path);
    }
    $method = $_SERVER['REQUEST_METHOD'];

    foreach (self::$routes as $route) {
      $pattern = "#^" . $route['path'] . "$#";

      if (preg_match($pattern, $path, $var) && $method == $route['method']) {
        $function = $route['function'];
        $controller = new $route['controller'];
        // $controller->$function();
        
        array_shift($var);
        call_user_func_array([$controller, $function], $var);

        return;
      }
    }
    http_response_code(404);
    echo "Not found";
  }
}