<?php
namespace Controller;

require 'vendor/autoload.php';

use Services\TransactionService;

class TransactionController extends AbstractController {

    private TransactionService $transactionService;

    public function __construct() {
        $this->transactionService = new TransactionService();
    }

    public function createTransaction() : array {
        return $this->transactionService->newTransaction($this->getBody());
    }

    public function getTransactions() : array {
        return $this->transactionService->getUserTransactions();
    }
}