<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController {

    private $validacao;
    private $produtoEspecificacaoModel;
    private $produtoModel;
    private $medidaModel;
    private $acao;
    private $bairroModel;
    
    private $horaAtual;
    private $expedienteHoje;

    public function __construct() {
        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->bairroModel = new \App\Models\BairroModel();
        $this->medidaModel = new \App\Models\MedidaModel();
        
        $this->horaAtual = date('H:i');

        $this->acao = service('router')->methodName();
    }

    public function index() {
        $data = [
            'titulo' => 'Meu carrinho de compras',
        ];
        if (session()->has('carrinho') && count(session()->get('carrinho')) > 0) {
            $data['carrinho'] = json_decode(json_encode(session()->get('carrinho')), false);
        }
        return view('Carrinho/index', $data);
    }

    public function adicionar() {

        if ($this->request->getMethod() === 'post') {
            
            $this->expedienteHoje = expedienteHoje();
            
            if($this->expedienteHoje->situacao == false){
                return redirect()->back()->with('expediente', 'Hoje estamos fechados. Esperamos você aqui amanha! <3');
            }
            if($this->horaAtual > $this->expedienteHoje->fechamento || $this->horaAtual < $this->expedienteHoje->abertura){
                return redirect()->back()->with('expediente', "Nosso horário de funcionamento na ".$this->expedienteHoje->dia_descricao. " é das " .$this->expedienteHoje->abertura. " até ".$this->expedienteHoje->fechamento);
            }
            
            
            
            

            $produtoPost = $this->request->getPost('produto');

            $this->validacao->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|string'],
                'produto.especificacao_id' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]'],
                'produto.preco' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]'],
                'produto.quantidade' => ['label' => 'Quantidade', 'rules' => 'required|greater_than[0]'],
            ]);
            if (!$this->validacao->withRequest($this->request)->run()) {
                return redirect()->back()
                                ->with('errors_model', $this->validacao->getErrors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo e tente novamente')
                                ->withInput();
            }

            $especificacaoProduto = $this->produtoEspecificacaoModel
                    ->join('medidas', 'medidas.id = produtos_especificacoes.medida_id')
                    ->where('produtos_especificacoes.id', $produtoPost['especificacao_id'])
                    ->first();

            if ($especificacaoProduto == null) {

                return redirect()->back()
                                ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor entre contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-1001</strong>');
            }

            $produto = $this->produtoModel->select(['id', 'nome', 'slug', 'ativo'])->where('slug', $produtoPost['slug'])->first(); //estamos utilizando o toarray para que possamos inserir esse objeto no carrinho no formato adequado


            if ($produto == null || $produto->ativo == false) {

                return redirect()->back()
                                ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor entre contato com a nossa equipe e informe o código de erro <strong>ERRO-ADD-PROD-2002</strong>');
            }

            $produto = $produto->toArray();

            $produto['slug'] = mb_url_title($produto['slug'] . '-' . $especificacaoProduto->nome, '-', true);

            $produto['nome'] = $produto['nome'] . ' ' . $especificacaoProduto->nome;

            $preco = $especificacaoProduto->preco;

            $produto['preco'] = number_format($preco, 2);
            $produto['quantidade'] = (int) $produtoPost['quantidade'];
            $produto['tamanho'] = $especificacaoProduto->nome;

            unset($produto['ativo']); //removemos o atributo ativo que n tem utilidade

            if (session()->has('carrinho')) {
                $produtos = session()->get('carrinho');

                $produtosSlugs = array_column($produtos, 'slug'); //pega slug dos itens do carrinho

                if (in_array($produto['slug'], $produtosSlugs)) {

                    //ja tem o item nois aumenta a quantidade
                    $produtos = $this->atualizaProduto($this->acao, $produto['slug'], $produto['quantidade'], $produtos); // chama a funcao pra increment a qtd do produto se tive no carrinho

                    session()->set('carrinho', $produtos); //sobrescreve atualizando produtos com os alterados
                } else {

                    session()->push('carrinho', [$produto]);

                    //n tem no carro, bota ai
                }
            } else {
                //n existe item no carrinho
                $produtos[] = $produto;

                session()->set('carrinho', $produtos);
            }

            return redirect()->to(site_url("carrinho"))->with('sucesso', 'Produto adicionado ao carrinho!');
        } else {

            return redirect()->back();
        }
    }

    public function atualizar() {

        if ($this->request->getMethod() === 'post') {

            $produtoPost = $this->request->getPost('produto');

            $this->validacao->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|string'],
                'produto.quantidade' => ['label' => 'Quantidade', 'rules' => 'required|greater_than[0]'],
            ]);
            if (!$this->validacao->withRequest($this->request)->run()) {
                return redirect()->back()
                                ->with('errors_model', $this->validacao->getErrors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo e tente novamente')
                                ->withInput();
            }

            $produtos = session()->get('carrinho');

            $produtosSlugs = array_column($produtos, 'slug'); //pega slug dos itens do carrinho

            if (!in_array($produtoPost['slug'], $produtosSlugs)) {
                return redirect()->back()
                                ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor entre contato com a nossa equipe e informe o código de erro <strong>ERRO-ATT-PROD-7007</strong>');
            } else {

                /* produto validado att a quantidade no carrinho */

                $produtos = $this->atualizaProduto($this->acao, $produtoPost['slug'], $produtoPost['quantidade'], $produtos);
                session()->set('carrinho', $produtos);

                return redirect()->back()->with('sucesso', 'Quantidade atualizada com sucesso!');
            }
        } else {
            return redirect()->back();
        }
    }

    public function remover() {

        if ($this->request->getMethod() === 'post') {

            $produtoPost = $this->request->getPost('produto');

            $this->validacao->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|string'],
            ]);
            if (!$this->validacao->withRequest($this->request)->run()) {
                return redirect()->back()
                                ->with('errors_model', $this->validacao->getErrors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo e tente novamente')
                                ->withInput();
            }

            $produtos = session()->get('carrinho');

            $produtosSlugs = array_column($produtos, 'slug'); //pega slug dos itens do carrinho

            if (!in_array($produtoPost['slug'], $produtosSlugs)) {
                return redirect()->back()
                                ->with('fraude', 'Não conseguimos processar a sua solicitação. Por favor entre contato com a nossa equipe e informe o código de erro <strong>ERRO-ATT-PROD-7007</strong>');
            } else {

                $produtos = $this->removeProduto($produtos, $produtoPost['slug']);

                session()->set('carrinho', $produtos); //att o carrinho com o array produtos sem o item que foi removido

                return redirect()->back()->with('sucesso', 'Produto removido com sucesso!');
            }
        } else {
            return redirect()->back();
        }
    }

    public function limpar() {
        session()->remove('carrinho');
        return redirect()->to(site_url('carrinho'));
    }

    public function consultaCep() {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $this->validacao->setRule('cep', 'CEP', 'required|exact_length[9]');

        if (!$this->validacao->withRequest($this->request)->run()) {

            $retorno['erro'] = '<span class="text-danger small">' . $this->validacao->getError() . '</span>';
            return $this->response->setJSON($retorno);
        }

        $cep = str_replace("-", "", $this->request->getGet('cep'));
        helper('consulta_cep'); //tras o helper
        $consulta = consultaCep($cep);
        

        if (isset($consulta->erro) && !isset($consulta->cep)) {
            $retorno['erro'] = '<span class="text-danger small">Informe um CEP válido!</span>';
            return $this->response->setJSON($retorno);
        }

        $bairroRetornoSlug = mb_url_title($consulta->bairro, '-', true);
        $bairro = $this->bairroModel->select('nome, valor_entrega')->where('slug', $bairroRetornoSlug)->where('ativo', true)->first();

        if ($consulta->bairro == null || $bairro == null) {
            $retorno['erro'] = '<span class="text-danger small">Não atendemos o Bairro: ' 
                    . esc($consulta->bairro)
                    . ' - ' . esc($consulta->localidade)
                    . ' - CEP -' . esc($consulta->cep)
                    . ' - ' . esc($consulta->uf)
                    . '</span>';

            return $this->response->setJSON($retorno);
        }

        $retorno['valor_entrega'] = 'RS ' . esc(number_format($bairro->valor_entrega, 2));
        
        $retorno['bairro'] = '<span class="small">Valor de entrega no Bairro: ' 
                . esc($consulta->bairro)
                . ' - ' . esc($consulta->localidade)
                . ' - CEP -' . esc($consulta->cep)
                . ' - ' . esc($consulta->uf)
                . ' - RS ' . esc(number_format($bairro->valor_entrega, 2))
                . '</span>';
        
        $carrinho = session()->get('carrinho');
        $total = 0;
        
        foreach($carrinho as $produto){
            $total += $produto['preco'] * $produto['quantidade'];
            
        }
        
        $total+= esc(number_format($bairro->valor_entrega, 2));
        
        $retorno['total'] = 'R$ '.esc(number_format($total, 2));
        
        return $this->response->setJSON($retorno);
    }
    
    private function atualizaProduto(string $acao, string $slug, int $quantidade, array $produtos) {

        $produtos = array_map(function ($linha) use ($acao, $slug, $quantidade) {

            if ($linha['slug'] == $slug) {
                if ($acao === 'adicionar') {
                    $linha['quantidade'] += $quantidade;
                }
                if ($acao === 'atualizar') {
                    $linha['quantidade'] = $quantidade;
                }
            }

            return $linha;
        }, $produtos);
        return $produtos;
    }

    private function removeProduto(array $produtos, string $slug) {
        return array_filter($produtos, function ($linha) use ($slug) {
            return $linha['slug'] != $slug;
        });
    }
    

}
