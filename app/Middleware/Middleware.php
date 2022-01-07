<?php

namespace Hanasa\MVC\Middleware;

interface Middleware
{
  function before() :void;
}