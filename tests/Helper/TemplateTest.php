<?php

namespace Hanasa\MVC\Helper;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
  public function testRender()
  {
    Template::render('Home/index', [
      "PHP Login Managenement"
    ]);
    $this->expectOutputRegex('[PHP Login Management]');
    $this->expectOutputRegex('[html]');
    $this->expectOutputRegex('[body]');
    $this->expectOutputRegex('[Login Management]');
    $this->expectOutputRegex('[Login]');
    $this->expectOutputRegex('[Register]');
  }
}