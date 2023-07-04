<?php

namespace Database;

require 'vendor/autoload.php';

use Database\DatabaseConnector;
use PDO;

class UserTable {

    private DatabaseConnector $database;

    public function __construct(){
        $this->database = new DatabaseConnector();
        $this->init();
    }

    public function init() : void {
        try{
            $this->database->getDatabase()
                ->query("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY,
                                                                  name VARCHAR(255) NOT NULL UNIQUE,
                                                                  password VARCHAR(255) NOT NULL,      
                                                                  balance DECIMAL(10, 2) NOT NULL,
                                                                  UNIQUE (id));");
        } catch (PDOException $e) {
            throw new Exception("An error occurred while creating the table: " . $e->getMessage());
        }
    }

    public function store(array $data) : int{
        try{
            $query = $this->database->getDatabase()->prepare("INSERT INTO users (name, password, balance) VALUES (:name, :password, :balance)");

            $query->execute($data);
            return $this->database->getDatabase()->lastInsertId();
        }catch (PDOException $e) {
            throw new Exception("An error occurred while saving data: " . $e->getMessage());
        }

    }

    public function getByName(string $name) : array{
        try{
            $query = $this->database->getDatabase()->prepare("SELECT * FROM users WHERE name = :name");
            $query->execute(['name' => $name]);

            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("An error occurred while gets data: " . $e->getMessage());
        }
    }

    public function getById(string $id) : array{
        try {
            $query = $this->database->getDatabase()->prepare("SELECT id, name, balance FROM users WHERE id = :id");
            $query->execute(["id" => $id]);

            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("An error occurred while gets data: " . $e->getMessage());
        }
    }

    public function updateBalance(float $balance, string $id) : void {
        try {
            $query = $this->database->getDatabase()->prepare("UPDATE users SET balance = :balance WHERE id = :id");
            $query->execute(["balance" => $balance, "id" => $id]);
        } catch (PDOException $e) {
            throw new Exception("An error occurred while gets data: " . $e->getMessage());
        }
    }

}