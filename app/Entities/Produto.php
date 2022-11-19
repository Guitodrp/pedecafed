<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Produto extends Entity
{
    protected $dates   = [
        'criado_em', 
        'atualizado_em',
        'deletado_em'
    ];
    
    public function recuperaMedidasEmComum(array $especificacoesPrimeiroProduto, array $especificacoesSegundoProduto) {
        
        $primeiroArrayMedidas = [];
        
        foreach($especificacoesPrimeiroProduto as $especificacao){
            if($especificacao->customizavel){
                array_push($primeiroArrayMedidas, $especificacao->medida_id);
            }
            
            
        }
        
        $segundoArrayMedidas = [];
        
        foreach($especificacoesSegundoProduto as $especificacao){
            if($especificacao->customizavel){
                array_push($segundoArrayMedidas, $especificacao->medida_id);
            }
            
            
        }
        
        return array_intersect($primeiroArrayMedidas, $segundoArrayMedidas);
        
    }
}
