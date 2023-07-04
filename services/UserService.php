<?php

namespace Services;

require 'vendor/autoload.php';

use Database\UserTable;

class UserService extends AbstractService {

    private UserTable $userTable;

    public function __construct(){
        parent::__construct();
        $this->userTable = new UserTable();
    }

    public function register(array $data) : string|array{
        /* simple validation */
        if(empty($data["name"]) || empty($data["password"] || empty($data["balance"])))
            return $this->sendBadRequest();

        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
        $this->userTable->store($data);

        return "Your account was created successful!";
    }

    public function authorize(array $data) : string|array{
        /* simple validate data */
        if(empty($data["name"]) || empty($data["password"]))
            return $this->sendBadRequest();

        $userEntity = $this->userTable->getByName($data["name"]);

        if(empty($userEntity) || !password_verify($data["password"], $userEntity["password"]))
            return $this->sendUnauthorizedResponse();

        /* generate and return jwt */
        $jwtPayload = ["id" => $userEntity["id"], "name" => $data["name"]];
        return $this->jwt->generateToken($jwtPayload);
    }

    public function accountBalance() : array{
        /* validate jwt token */
        try {
            $validationResponse = $this->authorizeAndGetPayload();
        } catch (\Exception $e) {
            return $this->sendUnauthorizedResponse();
        }

        /* returns user balance */
        return $this->userTable->getById($validationResponse["id"]);
    }
}