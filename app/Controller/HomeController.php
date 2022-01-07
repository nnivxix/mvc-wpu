<?php

namespace Hanasa\MVC\Controller;
use Hanasa\MVC\Helper\Template;

class HomeController
{
  function index() :void
  {
    $model = [
      "title" => "Halaman Utama",
      "content" => "Selamat datang di halaman saya"
    ];
    Template::render('Home/index', $model);
  }

  function hello() :void
  {
    echo "HomeController.hello()";
  }

  function world() :void
  {
    echo "HomeController.world()";
  }
  function me() :void
  {
    echo "My name is Hanasa";
  }

  function login()
  {
    $req = [
      "username" => $_POST['username'],
      "password" => $_POST['password']
    ];

    $res = "sukses login";

    //kirimkan ke view
    echo $res;
  }
}