<?php

namespace Hanasa\MVC\Service;

use PHPUnit\Framework\TestCase;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Exception\ValidateException;
use Hanasa\MVC\Model\UserRegisterRequest;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Domain\User;
use Hanasa\MVC\Model\UserLoginRequest;
use Hanasa\MVC\Model\UserPasswordUpdateRequest;
use Hanasa\MVC\Model\UserProfileUpdateRequest;
use Hanasa\MVC\Repository\SessionRepository;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class UserServiceTest extends TestCase
{
  private UserService $userService;
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  protected function setUp() :void
  {
    $connection = Database::getConnection();
    $this->userRepository = new UserRepository($connection);
    $this->userService = new UserService($this->userRepository);
    $this->sessionRepository = new SessionRepository($connection);

    $this->sessionRepository->deleteAll();
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

  public function testLoginNotFound()
  {
    $this->expectException(ValidateException::class);

    // default-nya null
    $request = new UserLoginRequest();
    $request->id = "Han";
    $request->pswd = "han"; 

    $this->userService->login($request);

  }

  public function testLoginWrongPassword()
  {
    $user = new User();
    $user->id = "eksa";
    $user->name = "eksa";
    $user->pswd = password_hash("eksa", PASSWORD_BCRYPT);

    $this->expectException(ValidateException::class);

    // default-nya null
    $request = new UserLoginRequest();
    $request->id = "Han";
    $request->pswd = "han"; 

    $this->userService->login($request);
  }

  public function testLoginSuccess()
  {
    $user = new User();
    $user->id = "eksa";
    $user->name = "eksa";
    $user->pswd = password_hash("eksa", PASSWORD_BCRYPT);

    $this->expectException(ValidateException::class);

    // default-nya null
    $request = new UserLoginRequest();
    $request->id = "eksa";
    $request->pswd = "eksa"; 

    $response = $this->userService->login($request);

    self::assertEquals($request->id, $response->user->id);
    self::assertTrue(password_verify($request->password, $response->user->password));
  }

  public function testUpdateSuccess()
  {
    $user = new User();
    $user->id = "eksa";
    $user->name = "eksa";
    $user->pswd = password_hash("eksa", PASSWORD_BCRYPT);
    $this->userRepository->save($user);

    $request = new UserProfileUpdateRequest();
    // harus sama
    $request->id = "eksa";
    $request->name = "new han";

    $this->userService->updateProfile($request);

    $result = $this->userRepository->findById($user->id);

    self::assertEquals($request->name, $result->name);
  }

  public function testUpdateValidationError()
  {
    $this->expectException(ValidateException::class);

    $request = new UserProfileUpdateRequest();
    $request->id = "";
    $request->name = "";

    $this->userService->updateProfile($request);
  }

  public function testUpdateNotFound()
  {
    $this->expectException(ValidateException::class);

    $user = new User();
    $user->id = "eksa";
    $user->name = "eksa";
    $user->pswd = password_hash("eksa", PASSWORD_BCRYPT);
    $this->userRepository->save($user);

    $request = new UserProfileUpdateRequest();
    // harus beda karena tidak ada yang akan dites.
    $request->id = "han yu";
    $request->name = "new han";

    $this->userService->updateProfile($request);
  }

  public function testUpdatePasswordSuccess()
  {
    $user = new User();
    $user->id = "eksa";
    $user->name = "eksa";
    $user->pswd = password_hash("eksa", PASSWORD_BCRYPT);
    $this->userRepository->save($user);

    $request = new UserPasswordUpdateRequest();
    $request->id = "eksa";
    $request->oldPassword = "eksa";
    $request->newPassword = "new";
    $this->userService->updatePassword($request);

    $result = $this->userRepository->findById($user->id);
    // test apakah sama antara newPassword dan dan hasilnya
    self::assertTrue(password_verify($request->newPassword, $result->pswd));
  }

  public function testUpdatePasswordValidateError()
  {
    $this->expectException(ValidateException::class);

    $request = new UserPasswordUpdateRequest();
    $request->id = "eksa";
    $request->oldPassword = "eksa";
    $request->newPassword = "new";
    $this->userService->updatePassword($request);
  }
  public function testUpdatePasswordWrongPassword()
  {
    $this->expectException(ValidateException::class);

    $user = new User();
    $user->id = "eksa";
    $user->name = "eksa";
    $user->pswd = password_hash("eksa", PASSWORD_BCRYPT);
    $this->userRepository->save($user);

    $request = new UserPasswordUpdateRequest();
    $request->id = "eksa";
    $request->oldPassword = "salah";
    $request->newPassword = "new";
    $this->userService->updatePassword($request);
  }

  public function testUpdatePasswordNotFound()
  {
    $this->expectException(ValidateException::class);


    $request = new UserPasswordUpdateRequest();
    $request->id = "eksa";
    $request->oldPassword = "eksa";
    $request->newPassword = "new";
    $this->userService->updatePassword($request);
  }
}