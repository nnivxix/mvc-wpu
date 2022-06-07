<?php

namespace Hanasa\MVC\Service;

use PHPUnit\Framework\TestCase;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Domain\Session;
use Hanasa\MVC\Domain\User;
use Hanasa\MVC\Repository\SessionRepository;
use Hanasa\MVC\Repository\UserRepository;

function setcookie(string $name, string $value){
  echo "$name: $value";
}

class SessionServiceTest extends TestCase
{
  private SessionService $sessionService;
  private SessionRepository $sessionRepository;
  private UserRepository $userRepository;

  protected function setUp() :void
  {
    $this->sessionRepository = new SessionRepository(Database::getConnection());
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();

    // kita harus membuat user terlebih dahulu
    $user = new User();
    $user->id = "han";
    $user->name = "Hanasa";
    $user->pswd = "han";

    $this->userRepository->save($user);
  }

  public function testCreate()
  {
    $session = $this->sessionService->create("han");

    $this->expectOutputRegex("[HANASA-SESSION: $session->id]");
  }

  public function testDestroy()
  {
    $session= new Session();
    $session->id = uniqid();
    $session->userId = "han";

    $this->sessionRepository->save($session);

    $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

    $this->sessionService->destroy();

    $this->expectOutputRegex("[HANASA-SESSION: ]");

    // cek apakah masih ada sesinya ini harusnya null karena sudah dihapus.
    $result = $this->sessionRepository->findById($session->id);
    self::assertNull($result);
  }

  public function testCurrent()
  {
    $session= new Session();
    $session->id = uniqid();
    $session->userId = "han";

    $this->sessionRepository->save($session);

    $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

    $user = $this->sessionService->current();

    self::assertEquals($session->userId, $user->id);
  }

}