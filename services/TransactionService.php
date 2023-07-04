<?php

namespace Services;

require 'vendor/autoload.php';

use Database\TransactionTable;
use Database\UserTable;

class TransactionService extends AbstractService {

    private TransactionTable $transactionTable;
    private UserTable $userTable;

    public function __construct(){
        parent::__construct();
        $this->transactionTable = new TransactionTable();
        $this->userTable = new UserTable();
    }

    public function getUserTransactions() : array {
        /* validate jwt token */
        try {
            $validationResponse = $this->authorizeAndGetPayload();

            /* get user all transactions */
            return $this->transactionTable->getByUserId($validationResponse["id"]);
        } catch (\Exception $e) {
            return $this->sendUnauthorizedResponse();
        }
    }

    public function newTransaction(array $data) : array{
        /* validate jwt token */
        try {
            $validationResponse = $this->authorizeAndGetPayload();

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
            return $this->userTable->getById($data["user_id"]);

        } catch (\Exception $e) {
            return $this->sendUnauthorizedResponse();
        }
    }

    private function updateBalance(string $user_id, float $amount) : int{
       return $this->userTable->getById($user_id)["balance"] += $amount;
    }
}