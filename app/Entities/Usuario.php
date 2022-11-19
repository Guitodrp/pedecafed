<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Libraries\Token;

class Usuario extends Entity
{
    
    protected $dates   = [
        'criado_em', 
        'atualizado_em',
        'deletado_em'
        
    ];
    
    public function verificaPassword(string $password) {
        return password_verify($password, $this->password_hash);
    }
    
    public function iniciaPasswordReset() {
        /*  instancio o novo objeto da classe token*/
        $token = new Token();
        /* atribuimos o objeto entitie usuario $this o atributo reset_token que contera o token gerado para que possamos acessalo na view password/reset_email*/
        $this->reset_token = $token->getValue();
        /*  atribuimos ao objeto entitie usuario $this o atributo reset_hash que contera o hash do token*/
        $this->reset_hash = $token->getHash();
        /*  atribuimos ao objeto entitie usuario $this o atributo reset_expira_em que contera a data de expiracao do token gerado*/
        $this->reset_expira_em = date('Y-m-d H:i:s', time() + 7200); // expira em 2 horas a partir da data e hora atuais
    }
    public function completaPasswordReset() {
        $this->reset_hash = null;
        $this->reset_expira_em = null;
    }
    
    public function iniciaAtivacao() {
        $token = new Token();
        
        $this->token = $token->getValue();
        
        $this->ativacao_hash = $token->getHash();
    }
    
    public function ativar() {
        $this->ativo = true;
        $this->ativacao_hash = null;
        
    }
    
}

