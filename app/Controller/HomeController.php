<?php

namespace Hanasa\MVC\Controller;
use Hanasa\MVC\Helper\Template;

class HomeController
{
  function index(){
    Template::render('Home/index', [
      "title" => "PHP Login Management"
    ]);
  }

}