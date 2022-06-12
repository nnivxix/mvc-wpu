<?php

namespace Hanasa\MVC\App {
  function header(string $value)
  {
    echo $value;
  }
}

namespace Hanasa\MVC\Service {
  function setcookie(string $name, string $value)
  {
    echo "$name: $value";
  }
}

namespace Hanasa\MVC\Controller {

  use Hanasa\MVC\Config\Database;
    use Hanasa\MVC\Domain\Session;
    use Hanasa\MVC\Domain\User;
  use Hanasa\MVC\Repository\SessionRepository;
  use Hanasa\MVC\Repository\UserRepository;
    use Hanasa\MVC\Service\SessionService;
    use PHPUnit\Framework\TestCase;

  class UserControllerTest extends TestCase
  {

    private UserController $userController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
      $this->userController = new UserController();

      $this->sessionRepository = new SessionRepository(Database::getConnection());
      $this->sessionRepository->deleteAll();

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
      $this->expectOutputRegex("[HANASA-SESSION: ]");
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

    public function testLogout()
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

      //kemudian panggil untuk logout
      $this->userController->logout();

      $this->expectOutputRegex("[Location: /]");
      $this->expectOutputRegex("[HANASA-SESSION: ]");
    }

    public function testUpdateProfile()
    {
      // buat terlebih dahulu
      $user = new User();
      $user->id = "han";
      $user->name = "han";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      // kemudian buatkan sesinya
      $session = new Session();
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      // buat cookie-nya
      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $this->userController->updateProfile();

      $this->expectOutputRegex("[Profile]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Name]");
    }

    public function testPostUpdateProfileSuccess()
    {
      // buat terlebih dahulu
      $user = new User();
      $user->id = "han";
      $user->name = "han";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      // kemudian buatkan sesinya
      $session = new Session();
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      // buat cookie-nya
      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['name'] = 'Heyyo';
      $this->userController->postUpdateProfile();

      $this->expectOutputRegex("[Location: /]");

      $result = $this->userRepository->findById("han");
      self::assertEquals("Heyyo", $result->name);
    }

    public function testUpdateProfileValidationError()
    {
      // buat terlebih dahulu
      $user = new User();
      $user->id = "han";
      $user->name = "han";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      // kemudian buatkan sesinya
      $session = new Session();
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      // buat cookie-nya
      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['name'] = '';
      $this->userController->postUpdateProfile();

      $this->expectOutputRegex("[Prfoile]");
      $this->expectOutputRegex("[Update Profile]");
      $this->expectOutputRegex("[id, name can not blank]");


    }

    public function testUpdatePassword()
    {
      // buat terlebih dahulu
      $user = new User();
      $user->id = "han";
      $user->name = "han";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      // kemudian buatkan sesinya
      $session = new Session();
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      // buat cookie-nya
      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $this->userController->updatePassword();

      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[$user->name]");
    }

    public function testPostUpdatePasswordSuccess()
    {
      // buat terlebih dahulu
      $user = new User();
      $user->id = "han";
      $user->name = "han";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      // kemudian buatkan sesinya
      $session = new Session();
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      // buat cookie-nya
      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['oldPassword'] = 'han';
      $_POST['newPassword'] = '123';
      $this->userController->postUpdatePassword();

      // kalau benar berarti masuk ke home (/)
      $this->expectOutputRegex("[Location: /]");
      $result = $this->userRepository->findById($user->id);
      self::assertTrue(password_verify("123", $result->pswd));
    }

    public function testUpdatePasswordValidate()
    {
      // buat terlebih dahulu
      $user = new User();
      $user->id = "han";
      $user->name = "han";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      // kemudian buatkan sesinya
      $session = new Session();
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      // buat cookie-nya
      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['oldPassword'] = '';
      $_POST['newPassword'] = '';
      $this->userController->postUpdatePassword();

      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[$user->name]");
      $this->expectOutputRegex("[id, old password and new password can not blank]");

    }

    public function testPostUpdatePasswordWrongPassword()
    {
      // buat terlebih dahulu
      $user = new User();
      $user->id = "han";
      $user->name = "han";
      $user->pswd = password_hash("han", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      // kemudian buatkan sesinya
      $session = new Session();
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      // buat cookie-nya
      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['oldPassword'] = 'yty';
      $_POST['newPassword'] = '123';
      $this->userController->postUpdatePassword();

      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[$user->name]");
      $this->expectOutputRegex("[Old password is wrong]");
    }
  }
}
