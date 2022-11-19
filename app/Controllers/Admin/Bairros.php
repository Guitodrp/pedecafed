<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Bairro;

class Bairros extends BaseController {

    private $bairroModel;

    public function __construct() {
        $this->bairroModel = new \App\Models\BairroModel();
    }

    public function index() {
        $data = [
            'titulo' => 'Listando os bairros atendidos',
            'bairros' => $this->bairroModel
                    ->withDeleted(true)
                    ->orderBy('nome','ASC')
                    ->paginate(10),
            'pager' => $this->bairroModel->pager
        ];

        return view('Admin/Bairros/index', $data);
    }

    public function procurar() {

        if (!$this->request->isAJAX()) {

            exit('pagina nao encontrada');
        }

        $bairros = $this->bairroModel->procurar($this->request->getGet('term'));

        $retorno = [];
        foreach ($bairros as $bairro) {
            $data['id'] = $bairro->id;
            $data['value'] = $bairro->nome;

            $retorno[] = $data;
        }
        return $this->response->setJSON($retorno);
    }

    public function criar() {
        $bairro = new Bairro();

        $data = [
            'titulo' => "Cadastrando novo bairro ",
            'bairro' => $bairro,
        ];

        return view('Admin/Bairros/criar', $data);
    }

    public function show($id = null) {
        $bairro = $this->buscaBairroOu404($id);

        $data = [
            'titulo' => "Detalhando o bairro $bairro->nome ",
            'bairro' => $bairro,
        ];

        return view('Admin/Bairros/show', $data);
    }

    public function editar($id = null) {
        $bairro = $this->buscaBairroOu404($id);

        $data = [
            'titulo' => "Editando o bairro $bairro->nome ",
            'bairro' => $bairro,
        ];

        return view('Admin/Bairros/editar', $data);
    }

    public function atualizar($id = null) {

        if ($this->request->getMethod() === 'post') {

            $bairro = $this->buscaBairroOu404($id);

            if ($bairro->deletado_em != null) {
                return redirect()->back()->with('info', "O bairro $bairro->nome encontra-se excluído. Portanto não é possive edita-lá.");
            }

            $bairro->fill($this->request->getPost());

            $bairro->valor_entrega = str_replace(",", "", $bairro->valor_entrega); //altera a , em decimais somente no banco de dados

            if (!$bairro->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->bairroModel->save($bairro)) {
                return redirect()->to(site_url("admin/bairros/show/$bairro->id"))
                                ->with('sucesso', "Bairro $bairro->nome atualizada com sucesso");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->bairroModel->errors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            /* nao e post */
            return redirect()->back();
        }
    }

    public function consultaCep() {
        if (!$this->request->isAJAX()) {
            return redirect()->to(site_url());
        }

        $validacao = service('validation');
        $validacao->setRule('cep', 'CEP', 'required|exact_length[9]');

        $retorno = [];

        if (!$validacao->withRequest($this->request)->run()) {
            $retorno['erro'] = '<span class="text-danger small">' . $validacao->getError() . '</span>';

            return $this->response->setJSON($retorno);
        }
        $cep = str_replace('-', '', $this->request->getGet('cep'));

        /* Carregando o Helper consultaCep */

        helper('consulta_cep');

        $consulta = consultaCep($cep);

        if (isset($consulta->erro) && !isset($consulta->cep)) {
            $retorno['erro'] = '<span class="text-danger small">CEP inválido</span>';
        }
        
        $retorno['endereco'] = $consulta;
        return $this->response->setJSON($retorno);
    }
    
    public function cadastrar() {
        
        if ($this->request->getMethod() === 'post') {
            
            $bairro = new Bairro($this->request->getPost());
            
            $bairro->valor_entrega = str_replace(",", "", $bairro->valor_entrega);

            if ($this->bairroModel->save($bairro)) {
                return redirect()->to(site_url("admin/bairros/show/".$this->bairroModel->getInsertID()))
                                ->with('sucesso', "Bairro $bairro->nome cadastrado com sucesso");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->bairroModel->errors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            /* nao e post */
            return redirect()->back();
        }
    }
    
    public function excluir($id = null) {
        $bairro = $this->buscaBairroOu404($id);

        if ($bairro->deletado_em != null) {
            return redirect()->back()->with('info', "O bairro $bairro->nome já encontra-se excluído");
        }

      
        if ($this->request->getMethod() == 'post') {
            
            $this->bairroModel->delete($id);
            return redirect()->to(site_url('admin/bairros'))->with('sucesso', "Bairro $bairro->nome excluído com sucesso!");
        }

        $data = [
            'titulo' => "Excluindo o bairro $bairro->nome ",
            'bairro' => $bairro,
        ];

        return view('Admin/Bairros/excluir', $data);
    }

    public function desfazerExclusao($id = null) {
        
        $bairro = $this->buscaBairroOu404($id);

        if ($bairro->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas bairros excluidos podem ser recuperadas');
        }

        if ($this->bairroModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', 'Exclusão desfeita com sucesso');
        } else {
            return redirect()->back()
                            ->with('errors_model', $this->bairroModel->errors())
                            ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                            ->withInput();
        }
    }

    private function buscaBairroOu404(int $id = null) {

        if (!$id || !$bairro = $this->bairroModel->withDeleted(true)->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Nao encontramos o bairro $id");
        }
        return $bairro;
    }

}
