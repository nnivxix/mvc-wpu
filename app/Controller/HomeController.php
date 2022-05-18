<?php

namespace Hanasa\MVC\Controller;

use Hanasa\MVC\App\View;

class HomeController
{
  function index() :void
  {
    View::render('Home/index', [
      "title" => "PHP Login Management"
    ]);
  }

}