<?php
namespace Hanasa\MVC\Controller;

use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Domain\Session;
use Hanasa\MVC\Domain\User;
use Hanasa\MVC\Repository\SessionRepository;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Service\SessionService;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
  private HomeController $homeController;
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  protected function setUp(): void
  {
    $this->homeController = new HomeController();
    $this->sessionRepository =  new SessionRepository(Database::getConnection());
    $this->userRepository = new UserRepository(Database::getConnection());

    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll(); 
  }

  public function testGuest()
  {
    $this->homeController->index();
    $this->expectOutputRegex("[Login Management]");
  }

  public function testUserLogin()
  {
    // buat terlebih dahulu
    $user = new User();
    $user->id = "han";
    $user->name = "han";
    $user->pswd = "han";
    $this->userRepository->save($user);

    // kemudian buatkan sesinya
    $session = new Session();
    $session->id = uniqid();
    $session->userId = $user->id;
    $this->sessionRepository->save($session);

    // buat cookie-nya
    $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

    $this->homeController->index();

    $this->expectOutputRegex("[Hello $user->name]");
  }
}