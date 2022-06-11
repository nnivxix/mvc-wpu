<?php

namespace Hanasa\MVC\Controller;

use Hanasa\MVC\App\View;
use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Exception\ValidateException;
use Hanasa\MVC\Model\UserLoginRequest;
use Hanasa\MVC\Model\UserProfileUpdateRequest;
use Hanasa\MVC\Model\UserRegisterRequest;
use Hanasa\MVC\Repository\SessionRepository;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Service\SessionService;
use Hanasa\MVC\Service\UserService;

class UserController
{
  private UserService $userService;
  private SessionService $sessionService;
   
  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $this->userService = new UserService($userRepository);

    $sessionRepository = new SessionRepository($connection);
    $this->sessionService = new SessionService($sessionRepository, $userRepository);
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
      $response = $this->userService->register($request);
      $this->sessionService->create($response->user->id);
      View::redirect('/users/login');
    } catch(ValidateException $exception){
      View::render('User/register', [
        'title' => 'Register New User',
        'error' => $exception->getMessage()
      ]);
    }
  }

  public function login()
  {
    View::render('User/login', [
      "title" => "Login User"
    ]);
  }

  public function postLogin()
  {
    $request = new UserLoginRequest();
    $request->id = $_POST['id'];
    $request->pswd = $_POST['password'];

    // mari kita cek
    try {
      $response = $this->userService->login($request);
      $this->sessionService->create($response->user->id);
      View::redirect('/');
    } catch (ValidateException $exception) {
      View::render('User/login', [
        'title' => 'Login user',
        'error' => $exception->getMessage()
      ]);
    }
  }

  public function logout()
  {
    $this->sessionService->destroy();
    View::redirect("/");
  }
  public function updateProfile()
  {
    $user = $this->sessionService->current();
    View::render("User/profile", [
      'title' => 'Update User Profile',
      'user' => [
        "id" => $user->id,
        "name" => $user->name
      ]
    ]);
  }

  public function postUpdateProfile()
  {
    $user = $this->sessionService->current();

    // isi form dengan datanya
    $request = new UserProfileUpdateRequest();
    $request->id = $user->id;
    $request->name = $_POST['name'];

    try {
      // kemudian update
      $this->userService->updateProfile($request);
      // jika berhasil maka redirecr ke home ('/')
      View::redirect('/');
    } catch (ValidateException $exception) {
      View::render('User/profile',[
        'title' => 'Update User Profile',
        'error' => $exception->getMessage(),
        'user' => [
          "id" => $user->id,
          "name" => $_POST['name']
        ]
      ]);
    }
  }
}