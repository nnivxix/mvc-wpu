<?php

namespace Hanasa\MVC\Service;

use PHPUnit\Framework\TestCase;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Exception\ValidateException;
use Hanasa\MVC\Model\UserRegisterRequest;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Domain\User;


class UserServiceTest extends TestCase
{
  private UserService $userService;
  private UserRepository $userRepository;

  protected function setUp() :void
  {
    $connection = Database::getConnection();
    $this->userRepository = new UserRepository($connection);
    $this->userService = new UserService($this->userRepository);
    
    $this->userRepository->deleteAll();
  }

  public function testRegisterSuccess()
  {
    $request = new UserRegisterRequest();
    $request->id = "hanasa1231";
    $request->name = "Hanasa";
    $request->pswd = "1asaqe2";

    $response = $this->userService->register($request);

    self::assertEquals($request->id, $response->user->id);
    self::assertEquals($request->name, $response->user->name);
    self::assertNotEquals($request->pswd, $response->user->pswd);

    self::assertTrue(password_verify($request->pswd, $response->user->pswd));
  }

  public function testRegisterFailed()
  {
    $this->expectException(ValidateException::class);

    $request = new UserRegisterRequest();
    $request->id = "";
    $request->name = "";
    $request->pswd = "";

    $this->userService->register($request);
  }

  public function testRegisterDuplicate()
  {
    $user = new User(); 
    $user->id = "hanasa1231";
    $user->name = "Hanasa";
    $user->pswd = "1asaqe2";

    $this->userRepository->save($user);

    $this->expectException(ValidateException::class);

    $request = new UserRegisterRequest();
    $request->id = "hanasa1231";
    $request->name = "Hanasa";
    $request->pswd = "1asaqe2";

    $this->userService->register($request);
  }
}