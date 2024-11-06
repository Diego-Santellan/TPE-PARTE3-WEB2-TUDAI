<?php
    // funciones de crear y validar token 
    
    function createJWT($payload) {
        // Header: tiene informacion sobre los metodos de encriptacion 
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Payload: suele contener informacion del usuario 
        $payload = json_encode($payload);

        // Base64Url: "encripta" el header y el payload recibido en base64
        $header = base64_encode($header);
        $header = str_replace(['+', '/', '='], ['-', '_', ''], $header);
        
        $payload = base64_encode($payload);
        $payload = str_replace(['+', '/', '='], ['-', '_', ''], $payload);

        // Firma : se genera en base a la informacion del header y payload (en base64) y la clave secreta
        $signature = hash_hmac('sha256', $header . "." . $payload, 'esteEsMiSecreto', true);
        $signature = base64_encode($signature);
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);

        // JWT
        $jwt = $header . "." . $payload . "." . $signature;
        return $jwt;//es un arreglo(string) 
    }

    function validateJWT($jwt) {
        $jwt = explode('.', $jwt);
        if(count($jwt) != 3) {//valida el largo 
            return null;
        }
        $header = $jwt[0];
        $payload = $jwt[1];
        $signature = $jwt[2];

        $valid_signature = hash_hmac('sha256', $header . "." . $payload, 'esteEsMiSecreto', true);
        $valid_signature = base64_encode($valid_signature);
        $valid_signature = str_replace(['+', '/', '='], ['-', '_', ''], $valid_signature);

        if($signature != $valid_signature) {
            return null;
        }

        $payload = base64_decode($payload);
        $payload = json_decode($payload);

        if($payload->exp < time()) {
            return null;
        }

        return $payload;
    }
