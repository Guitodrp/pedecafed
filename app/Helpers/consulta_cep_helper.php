<?php

if(!function_exists('consultaCep')){
    
    function consultaCep(string $cep){
        
       
        $urlViaCep = "https://viacep.com.br/ws/{$cep}/json/";
        
        //abre a conexao
        $ch = curl_init();
        
        //defini a url de busca
        curl_setopt($ch, CURLOPT_URL, $urlViaCep);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //executa o post
        $json = curl_exec($ch);
        
        //decodifica
        $resultado = json_decode($json);
        
        //fechamos a conexao
        return $resultado;
        
        
    }
}

