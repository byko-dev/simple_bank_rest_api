<?php

require_once 'Autoloader.php';

spl_autoload_register(Autoloader::loadClass("services/UserService"));

class UserController {

    private UserService $userService;

    public function __construct(){
        $this->userService = new UserService();
    }

    public function createAccount() : string {
        $data = json_decode(file_get_contents('php://input'), true);

        return $this->userService->register($data);
    }

    public function loginAttempt() : string {
        $data = json_decode(file_get_contents('php://input'), true);

        return $this->userService->authorize($data);;
    }

    public function getAccountBalance() : string {
        return $this->userService->accountBalance(getallheaders());
    }

}