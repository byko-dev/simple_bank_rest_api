<?php

namespace Controller;

require 'vendor/autoload.php';

use Services\UserService;

class UserController extends AbstractController {

    private UserService $userService;

    public function __construct(){
        $this->userService = new UserService();
    }

    public function createAccount() : string|array {
        return $this->userService->register($this->getBody());
    }

    public function loginAttempt() : string|array {
        return $this->userService->authorize($this->getBody());;
    }

    public function getAccountBalance() : array {
        return $this->userService->accountBalance();
    }
}