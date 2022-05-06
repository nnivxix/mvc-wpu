<?php
namespace Hanasa\MVC\Helper;

class Template {
  public static function render(string $view, $model)
  {
    require __DIR__ . '/../View/Header.php';
    require __DIR__ . '/../View/'. $view . '.php';
    require __DIR__ . '/../View/Footer.php';
  }  
}