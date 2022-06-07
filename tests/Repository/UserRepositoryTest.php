<?php

namespace Hanasa\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Domain\User;

class UserRepositoryTest extends TestCase
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  protected function setUp() :void
  {
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->sessionRepository = new SessionRepository(Database::getConnection());

    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();

    $user = new User();
    $user->id = "han";
    $user->name = "Hanasa";
    $user->pswd = "han";

    $this->userRepository->save($user);
  }

  public function testSaveSuccess()
  {
    $user = new User();
    $user->id = "23255";
    $user->name = "hanasa";
    $user->pswd = "test";

    $this->userRepository->save($user);

    $result = $this->userRepository->findById($user->id);

    self::assertEquals($user->id, $result->id);
    self::assertEquals($user->name, $result->name);
    self::assertEquals($user->pswd, $result->pswd);
  }
  
  public function testFindByIdNotFound()
  {
    $user = $this->userRepository->findById("notfound");
    self::assertNull($user);
  }
}