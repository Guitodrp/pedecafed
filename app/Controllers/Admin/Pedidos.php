<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Pedidos extends BaseController {

    private $pedidoModel;
    private $entregadorModel;

    public function __construct() {
        $this->pedidoModel = new \App\Models\PedidoModel();
        $this->entregadorModel = new \App\Models\EntregadorModel();
    }

    public function index() {
        $data = [
            'titulo' => 'Pedidos Realizados',
            'pedidos' => $this->pedidoModel->listaPedidos(),
            'pager' => $this->pedidoModel->pager
        ];
        return view('Admin/Pedidos/index', $data);
    }
    
    public function procurar() {

        if (!$this->request->isAJAX()) {

            exit('pagina nao encontrada');
        }

        $pedidos = $this->pedidoModel->procurar($this->request->getGet('term'));

        $retorno = [];
        foreach ($pedidos as $pedido) {
            $data['value'] = $pedido->codigo;

            $retorno[] = $data;
        }
        return $this->response->setJSON($retorno);
    }
    
    

    public function excluir($codigoPedido = null) {

        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
        
        if($pedido->situacao < 2){
            return redirect()->back()->with('info', 'Apenas pedidos <strong>entregues ou cancelados</strong> podem ser excluídos!');
        }
        if($this->request->getMethod() == 'post'){
            $this->pedidoModel->delete($pedido->id);
            return redirect()->to(site_url("admin/pedidos"))->with('sucesso', 'O pedido foi excluído com sucesso!');
        }

        $data = [
            'titulo' => "Detalhando o Pedido $pedido->codigo",
            'pedido' => $pedido,
        ];
        return view('Admin/Pedidos/excluir', $data);
    }
    public function show($codigoPedido = null) {

        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);

        $data = [
            'titulo' => "Editando o Pedido $pedido->codigo",
            'pedido' => $pedido,
        ];
        return view('Admin/Pedidos/show', $data);
    }

    public function editar($codigoPedido = null) {

        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);

        if ($pedido->situacao == 2) {
            return redirect()->back()->with('info', 'Esse pedido já foi <strong>entregue</strong> e portanto não é possivel editá-lo!');
        }
        if ($pedido->situacao == 3) {
            return redirect()->back()->with('info', 'Esse pedido já foi <strong>cancelado</strong> e portanto não é possivel editá-lo!');
        }

        $data = [
            'titulo' => "Detalhando o Pedido $pedido->codigo",
            'pedido' => $pedido,
            'entregadores' => $this->entregadorModel->select('id, nome')->where('ativo', true)->findAll(),
        ];
        return view('Admin/Pedidos/editar', $data);
    }

    public function atualizar($codigoPedido = null) {
        if ($this->request->getMethod() == 'post') {
            $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
            $pedidoPost = $this->request->getPost();

            if (!isset($pedidoPost['situacao'])) {
                return redirect()->back()->with('atencao', 'Escolha a situação do pedido!');
            }
            if ($pedidoPost['situacao'] == 1) {
                if (strlen($pedidoPost['entregador_id']) < 1) {
                    return redirect()->back()->with('atencao', 'Escolha um entregador para entregar o pedido!');
                }
            }
            if ($pedido->situacao == 0) {
                if ($pedidoPost['situacao'] == 2) {
                    return redirect()->back()->with('atencao', 'O pedido não pode ter sido entregue sem antes ter saido para entrega!');
                }
            }
            if ($pedidoPost['situacao'] != 1) {
                unset($pedidoPost['entregador_id']);
            }
            if ($pedidoPost['situacao'] == 3) {
                $pedidoPost['entregador_id'] = null;
            }

            $situacaoAnteriorPedido = $pedido->situacao;
            $pedido->fill($pedidoPost);

            if (!$pedido->hasChanged()) {
                return redirect()->back()->with('info', 'Não há alterações no pedido!');
            }


            if ($this->pedidoModel->save($pedido)) {

                if ($pedido->situacao == 1) {
                    $entregador = $this->entregadorModel->find($pedido->entregador_id);
                    $pedido->entregador = $entregador;

                    $this->enviaEmailPedidoSaiuEntrega($pedido);
                }
                if ($pedido->situacao == 2) {

                    $this->enviaEmailPedidoFoiEntregue($pedido);

                    $this->insereProdutosDoPedido($pedido);
                }
                if ($pedido->situacao == 3) {

                    $this->enviaEmailPedidoFoiCancelado($pedido);
                    if($situacaoAnteriorPedido == 1){
                        session()->setFlashdata('info','Administrador, esse pedido está em rota de entrega. Por favor entre em contato com o entregador e informe que o pedido foi cancelado');
                    }
                }




                return redirect()->to(site_url("admin/pedidos/show/$codigoPedido"))->with('sucesso', 'Pedido atualizado com sucesso!');
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->pedidoModel->errors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo');
            }
        } else {
            return redirect()->back();
        }
    }

    private function enviaEmailPedidoSaiuEntrega(object $pedido) {

        $email = service('email');

        $email->setFrom('no-reply@pedecafe.com.br', 'PedeCafe');
        $email->setTo($pedido->email);

        $email->setSubject("Seu pedido $pedido->codigo acabou de sair para a entrega!");
        $mensagem = view('Admin/Pedidos/pedido_saiu_entrega_email', ['pedido' => $pedido]); // armazene na variavel mensagem a view reset email passando o valor de token

        $email->setMessage($mensagem);

        $email->send();
    }

    private function enviaEmailPedidoFoiEntregue(object $pedido) {

        $email = service('email');

        $email->setFrom('no-reply@pedecafe.com.br', 'PedeCafe');
        $email->setTo($pedido->email);

        $email->setSubject("Seu pedido $pedido->codigo foi entregue!");
        $mensagem = view('Admin/Pedidos/pedido_foi_entregue_email', ['pedido' => $pedido]); // armazene na variavel mensagem a view reset email passando o valor de token

        $email->setMessage($mensagem);

        $email->send();
    }
    private function enviaEmailPedidoFoiCancelado(object $pedido) {

        $email = service('email');

        $email->setFrom('no-reply@pedecafe.com.br', 'PedeCafe');
        $email->setTo($pedido->email);

        $email->setSubject("Seu pedido $pedido->codigo foi entregue!");
        $mensagem = view('Admin/Pedidos/pedido_foi_cancelado_email', ['pedido' => $pedido]); // armazene na variavel mensagem a view reset email passando o valor de token

        $email->setMessage($mensagem);

        $email->send();
    }

    private function insereProdutosDoPedido(object $pedido) {
        $pedidoProdutoModel = new \App\Models\PedidoProdutoModel();
        $produtos = unserialize($pedido->produtos);
        $produtosDoPedido = [];

        foreach ($produtos as $produto) {
            array_push($produtosDoPedido, [
                'pedido_id' => $pedido->id,
                'produto' => $produto['nome'],
                'quantidade' => $produto['quantidade'],
            ]);
            $pedidoProdutoModel->insertBatch($produtosDoPedido);
        }
    }
} 