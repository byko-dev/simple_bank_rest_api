<?php

require_once 'Autoloader.php';

spl_autoload_register(Autoloader::loadClass("env/Env"));

/* simple JWT token (unsafe, without signature check) */
class JWT {

    private string $secretKey;

    public function __construct(){
        new Env();
        $this->secretKey = getenv('JWT_TOKEN');
    }

    public function generateToken(array $payload) : string {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));

        $dataToSign = $encodedHeader . '.' . $encodedPayload;
        $signature = $this->generateSignature($dataToSign);

        $encodedSignature = $this->base64UrlEncode($signature);

        $token = $dataToSign . '.' . $encodedSignature;

        return $token;
    }

    /* without signature check */
    public function validateToken(string $token) {
        $tokenParts = explode('.', $token);
        $encodedPayload = $tokenParts[1];

        return json_decode($this->base64UrlDecode($encodedPayload), true);;
    }

    private function generateSignature(string $data) : string {
        return hash_hmac('sha256', $data, $this->secretKey, true);
    }

    private function base64UrlEncode(string $data) : string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data) : string|false{
        return base64_decode(strtr($data, '-_', '+/'));
    }
}