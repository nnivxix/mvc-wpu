<?php

namespace Hanasa\MVC\Repository;

use Hanasa\MVC\Domain\User;

class UserRepository
{
  private \PDO $connection;

  public function __construct(\PDO $connection)
  {
    $this->connection = $connection;
  }

  public function save(User $user) :User
  {
    $statement = $this->connection->prepare("INSERT INTO users(id, name, pswd) VALUES (?,?,?)");
    $statement->execute([
      $user->id, $user->name, $user->pswd
    ]);
    return $user;
  }
  
  public function findById(string $id): ?User
  {
    $statement = $this->connection->prepare("SELECT id, name, pswd FROM users WHERE id = ?");
    $statement->execute([$id]);

    try{
      if ($row = $statement->fetch()){
        $user = new User();
        $user->id = $row['id'];
        $user->name = $row['name'];
        $user->pswd = $row['pswd'];

        return $user;
      } else{
        return null;
      }
    } finally {
      $statement->closeCursor();
    }
  }

  public function deleteAll() :void
  {
    $this->connection->exec("DELETE FROM users");
  }
}