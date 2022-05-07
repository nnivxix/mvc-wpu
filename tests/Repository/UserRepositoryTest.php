<?php

namespace Hanasa\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Domain\User;

class UserRepositoryTest extends TestCase
{
  private UserRepository $userRepository;
  protected function setUp() :void
  {
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->userRepository->deleteAll();
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