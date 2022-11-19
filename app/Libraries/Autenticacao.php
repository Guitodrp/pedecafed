<?php

namespace App\Libraries;
/*
 * Essa classe cuidara da parte de autenticação na nossa aplicação
 */


class Autenticacao{

    private $usuario;

    public function login(string $email, string $password) {
        $usuarioModel = new \App\Models\UsuarioModel();
        $usuario = $usuarioModel->buscaUsuarioPorEmail($email);

        /* Se não encontrar o usuario por email retorna false */
        if ($usuario === null) {
            return false;
        }
        /* Se a senha nao combinar com o password_hash retorna false */
        if (!$usuario->verificaPassword($password)) {
            return false;
        }
        /* So permitiremos o login de usuarios ativos */
        if (!$usuario->ativo) {
            return false;
        }

        /* Nesse Ponto esta tudo certo e podemos logar o usuario na aplicacao invocando o metodo abaixo */
        $this->logaUsuario($usuario);

        return true;
    }

    public function logout() {
        session()->destroy();
    }

    public function pegaUsuarioLogado() {
        /*  nao esquecer de compartilhar a instancia com services   */
        if ($this->usuario === null) {
            $this->usuario = $this->pegaUsuariodaSessao();
        }
        /*  retornamos o usuario que foi definido no inicio da classe   */
        return $this->usuario;
    }
    
    /*  so retorna true se o metodo retornar null(ou seja o usuario nao estiver logado)*/
    public function estaLogado() {
        
        return $this->pegaUsuarioLogado() !== null;
    }
    
    
    
    private function pegaUsuariodaSessao() {
        if (!session()->has('usuario_id')) {

            return null;
        }

        /* instanciamos o model usuario */
        $usuarioModel = new \App\Models\UsuarioModel();

        /*  Recupero o usuario  de acordo com a chave da sessao 'usuario_id     */
        $usuario = $usuarioModel->find(session()->get('usuario_id'));

        /* so retorno o objeto $usuario se o mesmo for encontrado e estiver ativo   */
        if ($usuario && $usuario->ativo) {
            return $usuario;
        }
    }

    private function logaUsuario(object $usuario) {
        $session = session();
        $session->regenerate();
        $session->set('usuario_id', $usuario->id);
    }

}
