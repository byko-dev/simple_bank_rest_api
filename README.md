# simple_bank_rest_api

Simple rest api application developed in Vanilla PHP

#### Start application in development server
```
  php -S localhost:<PORT> 
```

#### Requests userController.php
/register
```
curl --location 'http://localhost:8080/register' \
--header 'Content-Type: application/json' \
--data '{
    "name": "username",
    "password": "password",
    "balance": 303
}'
```
/login
```
curl --location 'http://localhost:8080/login' \
--header 'Content-Type: application/json' \
--data '{
    "name": "username",
    "password": "password"
}'
```
/account
```
curl --location 'http://localhost:8080/account' \
--header 'Authorization: <JWT_TOKEN>'
```

#### Requests transactionController.php
/transactions
```
curl --location 'http://localhost:8080/transactions' \
--header 'Authorization: <JWT_TOKEN>'
```
/transaction
```
curl --location 'http://localhost:8080/transaction' \
--header 'Authorization: <JWT_TOKEN>' \
--header 'Content-Type: application/json' \
--data '{
    "type": "deposit",
    "amount": 400
}'
```
