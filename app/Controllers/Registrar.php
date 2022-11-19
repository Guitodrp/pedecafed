<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Registrar extends BaseController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function novo() {
        $data = [
            'titulo' => 'Criar nova conta'
        ];

        return view('Registrar/novo', $data);
    }

    public function criar() {
        if ($this->request->getMethod() === 'post') {
            $usuario = new \App\Entities\Usuario($this->request->getPost());

            $this->usuarioModel->desabilitaValidacaoTelefone();

            $usuario->iniciaAtivacao();

            if ($this->usuarioModel->insert($usuario)) {
                
                $this->enviaEmailParaAtivarConta($usuario);

                return redirect()->to(site_url("registrar/ativacaoenviado"));
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->usuarioModel->errors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                                ->withInput();
            }
        } else {

            return redirect()->back();
        }
    }
    
    public function ativacaoEnviado() {
        $data = [
            'titulo' => 'E-mail de ativação da conta enviado. Verifique sua caixa de entrada!',
        ];

        return view('Registrar/ativacao_enviado', $data);
    
    }
    
    public function ativar(string $token = null) {
        if ($token === null) {
            return redirect()->to(site_url('login'))->with('atencao', 'Link inválido ou expirado');
        }
        
       $this->usuarioModel->ativarContaPeloToken($token);
       return redirect()->to(site_url('login'))->with('sucesso', 'Conta ativada com sucesso!');
    }
    
    private function enviaEmailParaAtivarConta(object $usuario) {

        $email = service('email');

        $email->setFrom('no-reply@pedecafe.com.br', 'PedeCafe');
        $email->setTo($usuario->email);

        $email->setSubject('Ativação de Conta');
        $mensagem = view('Registrar/ativacao_email', ['usuario' => $usuario]); // armazene na variavel mensagem a view reset email passando o valor de token

        $email->setMessage($mensagem);

        $email->send();
    }

    
}
