<?php

class Env
{
    private const ENV_PATH = "/.env";

    public function __construct(){
        $envFile = __DIR__ . self::ENV_PATH;
        if (file_exists($envFile)) {
            $envVariables = parse_ini_file($envFile);
            foreach ($envVariables as $key => $value) {
                putenv("$key=$value");
            }
        }
    }
}