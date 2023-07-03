<?php

abstract class AbstractService {

    protected function sendUnauthorizedResponse() : string{
        http_response_code(401);
        return json_encode(['message' => 'Unauthorized']);
    }

    protected function sendBadRequest() : string{
        http_response_code(400);
        return json_encode(array('message' => 'Bad Request'));
    }

}