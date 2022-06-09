<?php

namespace Hanasa\MVC\Middleware;

use Hanasa\MVC\App\View;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Repository\SessionRepository;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Service\SessionService;

class MustLoginMiddleware implements Middleware
{
  private SessionService $sessionService;

  public function __construct()
  {
    $sessionRepository = new SessionRepository(Database::getConnection());
    $userRepository = new UserRepository(Database::getConnection());
    $this->sessionService = new SessionService($sessionRepository, $userRepository);

  }

  function before(): void
  {
    $user = $this->sessionService->current();
    if ($user == null){
      View::redirect('/users/login');
    }
  }
}