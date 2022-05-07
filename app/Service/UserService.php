<?php

namespace Hanasa\MVC\Service;

use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Domain\User;
use Hanasa\MVC\Model\UserRegisterRequest;
use Hanasa\MVC\Model\UserRegisterResponse;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Exception\ValidateException;

class UserService
{
  private UserRepository $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function register(UserRegisterRequest $request): UserRegisterResponse
  {
    $this->validateUserRegistrationRequest($request);

    try{
      Database::beginTransaction();

      $user = $this->userRepository->findById($request->id);
      // jika id-usernya ada atau tidak null maka
      if ($user != null) {
        throw new ValidateException("User Id sudah ada");
      }

      $user = new User();
      $user->id = $request->id;
      $user->name = $request->name;
      $user->pswd = password_hash($request->pswd, PASSWORD_BCRYPT);

      $this->userRepository->save($user);

      $response = new UserRegisterResponse();
      $response->user = $user;

      Database::commitTransaction();
      
      return $response;
    } catch(\Exception $exception){
      Database::rollbackTransaction();
      throw $exception;
    }
    
  }

  private function validateUserRegistrationRequest(UserRegisterRequest $request)
  {
    if ($request->id == null || $request->name == null || $request->pswd == null || trim($request->id) == "" || trim($request->name) == "" || trim($request->pswd) == "") {
      throw new ValidateException("id, name, password can not blank");
    }
  }
}