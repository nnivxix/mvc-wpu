<?php

namespace Hanasa\MVC\Service;

use Hanasa\MVC\Domain\Session;
use Hanasa\MVC\Domain\User;
use Hanasa\MVC\Repository\SessionRepository;
use Hanasa\MVC\Repository\UserRepository;

class SessionService
{
  public static string $COOKIE_NAME = "HANASA-SESSION";
  private SessionRepository $sessionRepository;
  private UserRepository $userRepository;

  public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
  {
    $this->sessionRepository = $sessionRepository;
    $this->userRepository = $userRepository;

  }
  public function create(string $userId): Session
  {
    $session = new Session();
    $session->id = uniqid();
    $session->userId = $userId;

    $this->sessionRepository->save($session);

    // setelah sukses mari tambahkan cookie-nya
    // paramaetr ketiga itu waktu yang mana isinya waktu/durasi itu dalam hitungan detik
    setcookie(self::$COOKIE_NAME, $session->id, time() + (60 * 60 * 24 * 2), "/");

    return $session;
  }

  public function destroy()
  {
    $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
    $this->sessionRepository->deleteById($sessionId);

    // atur waktunya menjadi masa lampau
    setcookie(self::$COOKIE_NAME, '',1 ,"/");
  }

  public function current(): ?User
  {
    $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';

    $session = $this->sessionRepository->findById($sessionId);

    if($session == null){
      return null;
    }

    return $this->userRepository->findById($session->userId);
  }
}