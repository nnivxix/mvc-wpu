<?php

function getDatabaseConfig(): array {
  return [
    "database" => [
      "test" => [
        "url" => "mysql:host=localhost:3306;dbname=php_login_test",
        "username" => "hanasa",
        "password" => "1"
      ]
      ],
      "prod" => [
        "url" => "mysql:host=localhost:3306;dbname=php_login",
        "username" => "hanasa",
        "password" => "1"
      ]
      ];
};