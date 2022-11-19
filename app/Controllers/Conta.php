<?php

namespace App\Controllers;

use App\Controllers\BaseController;



class Conta extends BaseController {

    private $usuario;
    private $usuarioModel;
    private $pedidoModel;

    public function __construct() {
        $this->usuario = service('autenticacao')->pegaUsuarioLogado();
        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->pedidoModel = new \App\Models\pedidoModel();
        
    }

    public function index() {
        $data = [
           'titulo' => 'Meus Pedidos', 
        ];
        
        $pedidos = $this->pedidoModel
                ->where('usuario_id', $this->usuario->id)
                ->orderBy('criado_em', 'DESC')
                ->findAll();
        
        if($pedidos != null){
            $data['pedidos'] = $pedidos;
        }
        
        return view('Conta/index', $data);
    }

    public function show() {
        $data = [
            'titulo' => 'Meus Dados',
            'usuario' => $this->usuario,
        ];

        return view('Conta/show', $data);
    }

    public function autenticar() {
        $data = [
            'titulo' => 'Autenticar',
            'usuario' => $this->usuario,
        ];

        return view('Conta/autenticar', $data);
    }

    public function editar() {

        if (!session()->has('pode_editar_ate')) {
            return redirect()->to(site_url('conta/autenticar'));
        }

        if (session()->get('pode_editar_ate') < time()) {
            return redirect()->to(site_url('conta/autenticar'));
        }

        $data = [
            'titulo' => 'Editar meus Dados',
            'usuario' => $this->usuario,
        ];

        return view('Conta/editar', $data);
    }

    public function processaAutenticacao() {
        if ($this->request->getMethod() === 'post') {
            if ($this->usuario->verificaPassword($this->request->getPost('password'))) {
                session()->set('pode_editar_ate', time() + 300);

                return redirect()->to(site_url('conta/editar'));
            } else {
                return redirect()->back()->with('atencao', 'Senha inválida!');
            }
        } else {
            return redirect()->back();
        }
    }

    public function atualizar() {
        if ($this->request->getMethod() === 'post') {
            $this->usuario->fill($this->request->getPost());

            if (!$this->usuario->haschanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar!');
            }
            if ($this->usuarioModel->save($this->usuario)) {
                return redirect()->to(site_url("conta/show"))
                                ->with('sucesso', "Dados atualizados com sucesso");
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
    
    public function editarsenha() {
        $data = [
            'titulo' => 'Alterar Senha',
            'usuario' => $this->usuario,
        ];
        return view('Conta/editar_senha', $data);
    }
    
    public function atualizarSenha() {
        if ($this->request->getMethod() === 'post') {
           if(!$this->usuario->verificaPassword($this->request->getPost('current_password'))){
               return redirect()->back()->with('atencao', 'Senha atual inválida!');
           }
           
           $this->usuario->fill($this->request->getPost());
           if ($this->usuarioModel->save($this->usuario)) {
                return redirect()->to(site_url("conta/show"))
                                ->with('sucesso', "Senha atualizada com sucesso");
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

}
