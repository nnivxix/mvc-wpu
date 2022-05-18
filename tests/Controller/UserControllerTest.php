<?php

namespace Hanasa\MVC\App {
  function header(string $value)
  {
    echo $value;
  }
}

namespace Hanasa\MVC\Controller {

  use Hanasa\MVC\Config\Database;
  use Hanasa\MVC\Domain\User;
  use Hanasa\MVC\Repository\UserRepository;
  use PHPUnit\Framework\TestCase;

  class UserControllerTest extends TestCase
  {

    private UserController $userController;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
      $this->userController = new UserController();

      $this->userRepository = new UserRepository(Database::getConnection());
      $this->userRepository->deleteAll();

      putenv("mode=test");
    }
    public function testRegister()
    {
      $this->userController->register();

      $this->expectOutputRegex("[Register]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[Password]");
    }

    public function testRegisterSuccess()
    {
      $_POST['id'] = '223f';
      $_POST['name'] = 'hanasa';
      $_POST['password'] = 'test';

      $this->userController->postRegister();
      $this->expectOutputRegex("[Location: /users/login]");
    }

    public function testRegisterValidationError()
    {
      $_POST['id'] = '';
      $_POST['name'] = '';
      $_POST['password'] = 'test';

      $this->userController->postRegister();

      $this->expectOutputRegex("[Register]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[id, name, password can not blank]");
    }

    public function testRegisterDuplicate()
    {
      $user = new User();
      $user->id = "223f";
      $user->name = "hanasa";
      $user->pswd = "test";

      $this->userRepository->save($user);
      $_POST['id'] = '223f';
      $_POST['name'] = 'hanasa';
      $_POST['password'] = 'test';

      $this->userController->postRegister();

      $this->expectOutputRegex("[Register]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Register new User]");
      $this->expectOutputRegex("[User Id sudah ada]");
    }
  }
}
