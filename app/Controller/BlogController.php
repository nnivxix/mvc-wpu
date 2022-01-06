<?php

namespace Hanasa\MVC\Controller;

class BlogController
{
  function blog(string $blogId, string $category) :void
  {
    echo "<h1>Blog Id : $blogId, Category: $category</h1>";
  }
}