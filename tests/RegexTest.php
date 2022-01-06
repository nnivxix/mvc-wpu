<?php

namespace Hanasa\MVC;

use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase
{
  public function testRegex()
  {
    $path = "/products/12345/categories/abcd";
    $pattern = "#^/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)$#";

    $result = preg_match($pattern, $path, $var); //1

    self::assertEquals(1, $result);

    var_dump($var); // value from [$pattern, $path, $var];
    var_dump($result); //1
  }
}