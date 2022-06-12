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