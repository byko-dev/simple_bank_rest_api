<?php

namespace Database;

require 'vendor/autoload.php';

use env\Env;
use PDO;

class DatabaseConnector
{
    private PDO $database;

    public function __construct(){
        //initialization env
        new Env();

        $databaseHost = getenv('DATABASE_HOST');
        $databaseName = getenv('DATABASE_NAME');
        $databaseUser = getenv('DATABASE_USER');
        $databasePassword = getenv('DATABASE_PASSWORD');

        $this->database = new PDO("mysql:host=$databaseHost;dbname=$databaseName;charset=utf8", $databaseUser, $databasePassword);

        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getDatabase() : PDO{
        return $this->database;
    }
}