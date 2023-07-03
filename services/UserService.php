<?php

require_once 'Autoloader.php';

spl_autoload_register(Autoloader::loadClass("database/UserTable"));
spl_autoload_register(Autoloader::loadClass("jwt/JWT"));

class UserService extends AbstractService {

    private UserTable $userTable;
    private JWT $jwt;

    public function __construct(){
        $this->userTable = new UserTable();
        $this->jwt = new JWT();
    }

    public function register(array $data) : string{
        /* simple validation */
        if(empty($data["name"]) || empty($data["password"] || empty($data["balance"])))
            return $this->sendBadRequest();

        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
        $this->userTable->store($data);

        return "Your account was created successful!";
    }

    public function authorize(array $data) : string{
        /* simple validate data */
        if(empty($data["name"]) || empty($data["password"]))
            return $this->sendBadRequest();

        $userEntity = $this->userTable->getByName($data["name"]);

        if(empty($userEntity) || !password_verify($data["password"], $userEntity["password"]))
            return $this->sendUnauthorizedResponse();

        /* generate and return jwt */
        $jwtPayload = ["id" => $userEntity["id"], "name" => $data["name"]];
        return json_encode($this->jwt->generateToken($jwtPayload));
    }

    public function accountBalance(false|array $headers) : string{
        /* validate jwt token */
        $token = $headers["Authorization"];
        if(!isset($token))
            return $this->sendUnauthorizedResponse();

        $validationResponse = $this->jwt->validateToken($token);

        if($validationResponse === NULL)
            return $this->sendUnauthorizedResponse();

        /* returns user balance */
        return json_encode($this->userTable->getById($validationResponse["id"]));
    }
}