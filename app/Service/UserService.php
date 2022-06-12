<?php

namespace Hanasa\MVC\Service;

use Hanasa\MVC\Config\Database;
use Hanasa\MVC\Domain\User;
use Hanasa\MVC\Model\UserRegisterRequest;
use Hanasa\MVC\Model\UserRegisterResponse;
use Hanasa\MVC\Repository\UserRepository;
use Hanasa\MVC\Exception\ValidateException;
use Hanasa\MVC\Model\UserLoginRequest;
use Hanasa\MVC\Model\UserLoginResponse;
use Hanasa\MVC\Model\UserPasswordUpdateRequest;
use Hanasa\MVC\Model\UserPasswordUpdateResponse;
use Hanasa\MVC\Model\UserProfileUpdateRequest;
use Hanasa\MVC\Model\UserProfileUpdateResponse;

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

  public function login(UserLoginRequest $request) : UserLoginResponse
  {
    $this->validateUserLoginRequest($request);

    $user = $this->userRepository->findById($request->id);

    if ($user == null) {
      throw new ValidateException("Id or password is wrong");
    }

    if (password_verify($request->pswd, $user->pswd)) {
      $response = new UserLoginResponse();
      $response->user = $user;
      return $response;
    } else {
      throw new ValidateException("Id or password is wrong");
    }
  }

  private function validateUserLoginRequest(UserLoginRequest $request)
  {
    if ($request->id == null ||
    $request->pswd == null ||
    trim($request->id) == "" ||
    trim($request->pswd) == "") {
      throw new ValidateException("id, password can not blank");
    }
  }

  public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
  {
    $this->validateUserProfileUpdateRequest($request);

    try{
      Database::beginTransaction();

      $user = $this->userRepository->findById($request->id);
      if ($user == null){
        throw new ValidateException("User is not found");
      }

      $user->name = $request->name;
      $this->userRepository->update($user);

      Database::commitTransaction();

      $response = new UserProfileUpdateResponse();
      $response->user = $user;
      return $response;
    } catch (\Exception $exception) {
      Database::rollbackTransaction();
      throw $exception;
    }
  }

  private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request)
  {
    if ($request->id == null ||
    $request->name == null ||
    trim($request->id) == "" ||
    trim($request->name) == "") {
      throw new ValidateException("id, name can not blank");
    }
  }

  public function updatePassword(UserPasswordUpdateRequest $request ): UserPasswordUpdateResponse
  {
    $this->validateUserUpdatePassword($request);

    try {
      Database::beginTransaction();

      // cari id-nya
      $user = $this->userRepository->findById($request->id);

      // kemudian cek user-nya ada?
      if($user == null) {
        throw new ValidateException("User not found");
      }

      // kemudian cek password-nya sama?
      if (!password_verify($request->oldPassword, $user->pswd)){
        throw new ValidateException("Old password is wrong");
      }

      // masukan ke id-nya password barunya.
      $user->pswd = password_hash($request->newPassword, PASSWORD_BCRYPT);
      $this->userRepository->update($user);

      Database::commitTransaction();

      // lalu kembalikan nilainya
      $response = new UserPasswordUpdateResponse();
      $response->user = $user;
      return $response;

    } catch (\Exception $exception) {
      Database::rollbackTransaction();
      throw $exception;
    }
  }

  private function validateUserUpdatePassword(UserPasswordUpdateRequest $request)
  {
    if ($request->id == null || $request->oldPassword == null || $request->newPassword == null || trim($request->id) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == "") {
      throw new ValidateException("id, old password and new password can not blank");
    }
  }
}