<?php

namespace Controller;

require 'vendor/autoload.php';

abstract class AbstractController {
    protected function getBody() : array{
        return json_decode(file_get_contents('php://input'), true);
    }
}