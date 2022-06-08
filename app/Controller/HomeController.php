<?php

namespace Hanasa\MVC\Controller;

use Hanasa\MVC\App\View;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Repository\SessionRepository;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Service\SessionService;

class HomeController
{
  private SessionService $sessionService;

  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $sessionRepository = new  SessionRepository($connection);

    $this->sessionService = new SessionService($sessionRepository, $userRepository);
  }

  function index() :void
  {
    // ambil sesi dari current()
    $user = $this->sessionService->current();

    // lalu kita cek apakah sesinya ada?
    // var_dump($user);
    if ($user == null) {
      View::render('Home/index', [
        "title" => "PHP Login Management"
      ]);
    } else {
      View::render('Home/dashboard', [
        "title" => "Dashboard",
        "user" => [
          "name" => $user->name
        ]
      ]);
    }
    

  }

}