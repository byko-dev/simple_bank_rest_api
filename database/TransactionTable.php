<?php

namespace Database;

require 'vendor/autoload.php';

use Database\DatabaseConnector;
use PDO;

class TransactionTable {

    private DatabaseConnector $database;

    public function __construct() {
        $this->database = new DatabaseConnector();
        $this->init();
    }

    private function init() : void{
        try{
            $this->database->getDatabase()->query("CREATE TABLE IF NOT EXISTS transactions (
                                                        id INT AUTO_INCREMENT PRIMARY KEY,
                                                        user_id INT,
                                                        type VARCHAR(255),
                                                        amount DECIMAL(10, 2),
                                                        timestamp TIMESTAMP,
                                                        FOREIGN KEY (user_id) REFERENCES users(id));");
        } catch (PDOException $e) {
            throw new Exception("An error occurred while creating the table: " . $e->getMessage());
        }
    }

    public function store(array $data) : void {
        try{
            $query = $this->database->getDatabase()->prepare("INSERT INTO transactions (user_id, type, amount, timestamp)
                                                                    VALUES (:user_id, :type, :amount, NOW());");
            $query->execute($data);
        } catch (PDOException $e) {
            throw new Exception("An error occurred while saving data: " . $e->getMessage());
        }
    }

    public function getByUserId(string $userId) : array{
        try{
            $query = $this->database->getDatabase()->prepare("SELECT id, type, amount, timestamp FROM transactions WHERE user_id = :user_id");
            $query->execute(["user_id" => $userId]);

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("An error occurred while gets data: " . $e->getMessage());
        }
    }
}