<?php
namespace Hanasa\MVC\App {
  function header(string $value)
  {
    echo $value;
  }
}

namespace Hanasa\MVC\Middleware{

    use Hanasa\MVC\Config\Database;
    use Hanasa\MVC\Repository\SessionRepository;
    use Hanasa\MVC\Repository\UserRepository;
    use PHPUnit\Framework\TestCase;
    use Hanasa\MVC\Domain\Session;
    use Hanasa\MVC\Domain\User;
    use Hanasa\MVC\Service\SessionService;

  class MustNotLoginMiddlewareTest extends TestCase
  {
    private MustLoginMiddleware $middleware;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    
  
    public function setUp(): void
    {
      $this->middleware = new MustLoginMiddleware();
      putenv("mode=test");

      $this->userRepository = new UserRepository(Database::getConnection());
      $this->sessionRepository = new SessionRepository(Database::getConnection());

      $this->sessionRepository->deleteAll();
      $this->userRepository->deleteAll();
    }
    public function testBeforeGuest()
    {
      $this->middleware->before();
      $this->expectOutputString("");
      
    }

    public function testBeforeMember()
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

      $this->middleware->before();
      $this->expectOutputRegex("[Location: /]");
      
    }


  }
}

