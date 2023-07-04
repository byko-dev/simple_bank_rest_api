<?php

require_once "./Router.php";
require_once "./controllers/UserController.php";
require_once "./controllers/TransactionController.php";

$router = new Router();

$userController = new Controller\UserController();

/* create user account with custom balance */
$router->addRoute("POST", "/register", [$userController, "createAccount"]);

/* user authorization request, returns jwt token */
$router->addRoute("POST", "/login", [$userController, "loginAttempt"]);

/* get user balance, required authorization header */
$router->addRoute("GET", "/account", [$userController, "getAccountBalance"]);


$transactionController = new Controller\TransactionController();

/* create transaction withdrawal or deposit on user account, returns user balance, required authorization header  */
$router->addRoute("POST", "/transaction", [$transactionController, "createTransaction"]);

/* get all user transactions, required authorization header */
$router->addRoute("GET", "transactions", [$transactionController, "getTransactions"]);

$router->handleRequest();