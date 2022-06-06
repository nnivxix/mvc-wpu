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

    public function testLogin()
    {
      $this->userController->login();

      $this->expectOutputRegex("[Login user]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Password]");
    }

    public function testLoginSuccess()
    {
      $user = new User();
      $user->id = "han";
      $user->name = "hanasa";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);

      $this->userRepository->save($user);

      $_POST['id'] = "han";
      $_POST['password'] = "han";

      $this->userController->postLogin();

      $this->expectOutputRegex("[Location: /]");
    }
    
    public function testLoginValidationError()
    {
      $_POST['id'] = '';
      $_POST['password'] = '';

      $this->userController->postLogin();

      $this->expectOutputRegex("[Login user]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[id, password can not blank]");
    }

    public function testLoginUserNotFound()
    {
      $_POST['id'] = 'notfound';
      $_POST['password'] = 'notfound';

      $this->userController->postLogin();

      $this->expectOutputRegex("[Login user]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Id or password is wrong]");
    }

    public function testLoginWrongPassword()
    {
      $user = new User();
      $user->id = "han";
      $user->name = "hanasa";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);

      $this->userRepository->save($user);

      $_POST['id'] = 'han';
      $_POST['password'] = 'salah';

      $this->userController->postLogin();

      $this->expectOutputRegex("[Login user]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Id or password is wrong]");
    }
  }
}
