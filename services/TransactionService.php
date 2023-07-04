<?php

require_once 'Autoloader.php';

spl_autoload_register(Autoloader::loadClass("database/TransactionTable"));
spl_autoload_register(Autoloader::loadClass("jwt/JWT"));


/* maybe a better way is to use trait instead of abstract class, i dont know */
class TransactionService extends AbstractService {

    private TransactionTable $transactionTable;
    private UserTable $userTable;
    private JWT $jwt;

    public function __construct(){
        $this->transactionTable = new TransactionTable();
        $this->userTable = new UserTable();
        $this->jwt = new JWT();
    }

    public function getUserTransactions(array $headers) : string {
        /* validate jwt token */
        $token = $headers["Authorization"];
        if(!isset($token))
            return $this->sendUnauthorizedResponse();

        $validationResponse = $this->jwt->validateToken($token);

        if($validationResponse === NULL)
            return $this->sendUnauthorizedResponse();

        /* get user all transactions */
        return json_encode($this->transactionTable->getByUserId($validationResponse["id"]));
    }

    public function newTransaction(false|array $headers, array $data) : string{

        /* validate jwt token */
        $token = $headers["Authorization"];
        if(!isset($token))
            return $this->sendUnauthorizedResponse();

        $validationResponse = $this->jwt->validateToken($token);

        if($validationResponse === NULL)
            return $this->sendUnauthorizedResponse();

        /* simple validate requested data */
        if(empty($data["amount"] || empty($data["type"])))
            return $this->sendBadRequest();

        /* save transaction record */
        $data["user_id"] = $validationResponse["id"];
        $this->transactionTable->store($data);

        /* count new balance and save */
        $currently_balance = $this->updateBalance($data["user_id"], $data["amount"]);
        $this->userTable->updateBalance($currently_balance, $data["user_id"]);

        /* return updated user balance record */
        return json_encode($this->userTable->getById($data["user_id"]));
    }

    private function updateBalance(string $user_id, float $amount) : int{
       return $this->userTable->getById($user_id)["balance"] += $amount;
    }
}