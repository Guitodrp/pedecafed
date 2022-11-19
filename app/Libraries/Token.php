<?php

namespace App\Libraries;

class Token {

    private $token;

    public function __construct($token = null) { //recebe ou n um token
        if ($token === null) {
            $this->token = bin2hex(random_bytes(16)); //converte binario em hexadecimal - random gera pseudorandonbyter cripto
        }else{
            $this->token = $token;
        }
    }
    public function getValue() { //pega o valor do token
        return $this->token;
    }
    
    public function getHash() { 
        return hash_hmac('sha256', $this->token, env('CHAVE_SECRETA_ATIVACAO_CONTA')); //hmac gera um codigo
    }
}
