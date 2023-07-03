<?php

require_once 'Autoloader.php';

spl_autoload_register(Autoloader::loadClass("services/TransactionService"));

class TransactionController {

    private TransactionService $transactionService;

    public function __construct() {
        $this->transactionService = new TransactionService();
    }

    public function createTransaction() : string {
        $data = json_decode(file_get_contents('php://input'), true);

        return $this->transactionService->newTransaction(getallheaders(), $data);
    }

    public function getTransactions() : string {
        return $this->transactionService->getUserTransactions(getallheaders());
    }
}