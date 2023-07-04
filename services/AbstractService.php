<?php

namespace Services;

require 'vendor/autoload.php';

use jwt\JWT;

abstract class AbstractService {

    protected JWT $jwt;

    public function __construct() {
        $this->jwt = new JWT();
    }

    protected function sendUnauthorizedResponse() : array{
        http_response_code(401);
        return ['message' => 'Unauthorized'];
    }

    protected function sendBadRequest() : array{
        http_response_code(400);
        return ['message' => 'Bad Request'];
    }

    /**
     * @throws Exception
     */
    protected function authorizeAndGetPayload() : mixed{
        if (!isset(getallheaders()["Authorization"]))
            throw new \Exception();

        $authToken = getallheaders()["Authorization"];

        $validationResponse = $this->jwt->validateToken($authToken);

        if ($validationResponse === NULL)
            throw new \Exception();

        return $validationResponse;
    }
}