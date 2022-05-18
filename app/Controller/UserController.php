<?php

namespace Hanasa\MVC\Controller;

use Hanasa\MVC\App\View;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Exception\ValidateException;
use Hanasa\MVC\Model\UserRegisterRequest;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Service\UserService;

class UserController
{
  private UserService $userService;
   
  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $this->userService = new UserService($userRepository);
  }

  public function register()
  {
    View::render('User/register', [
      'title' => "Register new user",
      // 'error' => "Upss"
    ]);
  }

  public function postRegister()
  {
    $request = new UserRegisterRequest();
    $request->id = $_POST['id'];
    $request->name = $_POST['name'];
    $request->pswd = $_POST['password'];
    
    try{
      $this->userService->register($request);
      View::redirect('/users/login');
    } catch(ValidateException $exception){
      View::render('User/register', [
        'title' => 'Register New User',
        'error' => $exception->getMessage()
      ]);
    }
    
  }
}