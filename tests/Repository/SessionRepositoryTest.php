<?php

namespace Hanasa\MVC\Repository;

use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Domain\Session;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase
{
  private SessionRepository $sessionRepository;

  protected function setUp(): void
  {
    $this->sessionRepository = new SessionRepository(Database::getConnection());

    // menghapus untuk menghidari duplikasi code
    $this->sessionRepository->deleteAll();
  }

  public function testSaveSuccess()
  {
    $session = new Session();
    // membuat id yang unik, ini fungsi bawaan dari PHP
    $session->id = uniqid(); 
    $session->userId = "han";

    $this->sessionRepository->save($session);

    $result = $this->sessionRepository->findById($session->id);

    self::assertEquals($session->id, $result->id);
    self::assertEquals($session->userId, $result->userId);
  }

  public function testDeleteByIdSuccess()
  {
    $session = new Session();
    // membuat id yang unik, ini fungsi bawaan dari PHP
    $session->id = uniqid(); 
    $session->userId = "han";

    $this->sessionRepository->save($session);

    $this->sessionRepository->deleteById($session->id);

    $result = $this->sessionRepository->findById($session->id);

    // test apakah null atau tidak
    self::assertNull($result);
  }

  public function testFindByIdNotFound()
  {
    $result = $this->sessionRepository->findById("notfound");

    // test apakah null atau tidak
    self::assertNull($result);
  }
}