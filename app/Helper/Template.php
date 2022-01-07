<?php
namespace Hanasa\MVC\Helper;

class Template {
  public static function render($view, $model)
  {
    require __DIR__ . '/../View/'. $view . '.php';
  }  
}