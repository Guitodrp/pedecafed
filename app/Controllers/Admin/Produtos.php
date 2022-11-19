<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Produto;

class Produtos extends BaseController {

    private $produtoModel;
    private $categoriaModel;
    private $medidaModel;
    private $produtoEspecificacaoModel;

    public function __construct() {
        $this->produtoModel = new \App\Models\ProdutoModel(); //nova instancia
        $this->categoriaModel = new \App\Models\CategoriaModel(); //nova instancia
        $this->medidaModel = new \App\Models\MedidaModel(); //nova instancia
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel(); //nova instancia
    }

    public function index() {
        $data = [
            'titulo' => 'Listando os produtos',
            'produtos' => $this->produtoModel->select('produtos.*, categorias.nome AS categoria') //seleciona na tabela produtos todos os campos, e na categoria no campo nome - renomeando para categoria
                    ->join('categorias', 'categorias.id = produtos.categoria_id') //id da categoria tem que ser igual ao id do produtos
                    ->withDeleted(true)
                    ->paginate(10),
            'especificacoes'=> $this->produtoEspecificacaoModel->join('medidas', 'medidas.id = produtos_especificacoes.medida_id')->findAll(),
            'pager' => $this->produtoModel->pager, //sempre retorna o pager ao usar o paginate
        ];
        return view('Admin/Produtos/index', $data);
    }

    public function procurar() {

        if (!$this->request->isAJAX()) {

            exit('pagina nao encontrada');
        }

        $produtos = $this->produtoModel->procurar($this->request->getGet('term'));

        $retorno = [];
        foreach ($produtos as $produto) {
            $data['id'] = $produto->id;
            $data['value'] = $produto->nome;

            $retorno[] = $data;
        }
        return $this->response->setJSON($retorno);
    }

    public function criar() {
        $produto = new Produto();

        $data = [
            'titulo' => "Criando novo produto",
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll()
        ];

        return view('Admin/Produtos/criar', $data);
    }

    public function cadastrar() {

        if ($this->request->getMethod() === 'post') {

            $produto = new Produto($this->request->getPost());

            if ($this->produtoModel->save($produto)) {
                return redirect()->to(site_url("admin/produtos/show/" . $this->produtoModel->getInsertID()))
                                ->with('sucesso', "Produto $produto->nome cadastrado com sucesso!");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->produtoModel->errors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                                ->withInput();
            }
        } else {

            return redirect()->back();
        }
    }

    public function show($id = null) {
        $produto = $this->buscaProdutoOu404($id);

        $data = [
            'titulo' => "Detalhando o produto $produto->nome ",
            'produto' => $produto,
        ];

        return view('Admin/Produtos/show', $data);
    }

    public function editar($id = null) {
        $produto = $this->buscaProdutoOu404($id);

        $data = [
            'titulo' => "Editando o produto $produto->nome ",
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll(),
        ];

        return view('Admin/Produtos/editar', $data);
    }

    public function atualizar($id = null) {

        if ($this->request->getMethod() === 'post') {

            $produto = $this->buscaProdutoOu404($id);
            $produto->fill($this->request->getPost()); // preenche com o que vem do post

            if (!$produto->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }


            if ($this->produtoModel->save($produto)) {
                return redirect()->to(site_url("admin/produtos/show/$id"))->with('sucesso', 'Produto atualizado com sucesso!');
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->produtoModel->errors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                                ->withInput();
            }
        } else {

            return redirect()->back();
        }
    }

    public function editarImagem($id = null) {
        $produto = $this->buscaProdutoOu404($id);
        if($produto->deletado_em != null){
            return redirect()->back()->with('info', 'Não é possivel editar a imagem de um produto excluído!');
        }
        $data = [
            'titulo' => "Editando a imagem do produto $produto->nome ",
            'produto' => $produto,
        ];
        return view('Admin/Produtos/editar_imagem', $data);
    }

    public function upload($id = null) {

        $produto = $this->buscaProdutoOu404($id);

        $imagem = $this->request->getFile('foto_produto');

        if (!$imagem->isValid()) {
            $codigoErro = $imagem->getError();
            if ($codigoErro == UPLOAD_ERR_NO_FILE) {
                return redirect()->back()->with('atencao', 'Nenhum arquivo foi selecionado');
            }
        }

        $tamanhoImagem = $imagem->getSizeByUnit('mb');
        if ($tamanhoImagem > 2) {
            return redirect()->back()->with('atencao', 'O arquivo selecionado é muito grande.O tamanho maximo permitido é de 2Mb');
        }

        $tipoImagem = $imagem->getMimeType();
        $tipoImagemLimpo = explode('/', $tipoImagem);
        $tiposPermitidos = [
            'jpeg', 'png', 'webp',
        ];

        if (!in_array($tipoImagemLimpo[1], $tiposPermitidos)) {

            return redirect()->back()->with('atencao', 'O arquivo não tem o formato permitido. Formatos permitidos:' . implode(', ', $tiposPermitidos));
        }

        list($largura, $altura) = getimagesize($imagem->getPathname());

        if ($largura < "400" || $altura < "400") {

            return redirect()->back()->with('atencao', 'A imagem não pode ser menor do que 400 x 400 pixels');
        }
        //------------------------------------------ ABAIXO TAMO FAZENO O STORE DA IMAGEM CARAI------------------------------------------------------

        $imagemCaminho = $imagem->store('produtos');

        $imagemCaminho = WRITEPATH . 'uploads/' . $imagemCaminho;

        Service('image')  //resize
                ->withFile($imagemCaminho)
                ->fit(400, 400, 'center')
                ->save($imagemCaminho);

        $imagemAntiga = $produto->imagem;
        $produto->imagem = $imagem->getName();
        $this->produtoModel->save($produto);

        $caminhoImagem = WRITEPATH . 'uploads/produtos/' . $imagemAntiga; //definindo o caminho da imagem sem o resize
        if (is_file($caminhoImagem)) {
            unlink($caminhoImagem);
        }
        return redirect()->to(site_url("admin/produtos/show/$produto->id"))->with('sucesso', 'Imagem alterada com sucesso!');
    }

    public function imagem(string $imagem = null) {
        if ($imagem) {
            $caminhoImagem = WRITEPATH . 'uploads/produtos/' . $imagem;
            $infoImagem = new \finfo(FILEINFO_MIME);
            $tipoImagem = $infoImagem->file($caminhoImagem);
            header("Content-Typ: $tipoImagem");
            header("Content-Length: $" . filesize($caminhoImagem));
            readfile($caminhoImagem);

            exit;
        }
    }

    public function especificacoes($id = null) {
        $produto = $this->buscaProdutoOu404($id);
        $data = [
            'titulo' => "Gerenciar as especificações do produto $produto->nome",
            'produto' => $produto,
            'medidas' => $this->medidaModel->where('ativo', true)->findAll(),
            'produtoEspecificacoes' => $this->produtoEspecificacaoModel->buscaEspecificacoesDoProduto($produto->id, 10),
            'pager' => $this->produtoEspecificacaoModel->pager,
        ];

        return view('Admin/Produtos/especificacoes', $data);
    }

    public function cadastrarEspecificacoes($id = null) {
        if ($this->request->getMethod() === 'post') {

            $produto = $this->buscaProdutoOu404($id);
            $especificacao = $this->request->getPost();
            $especificacao['produto_id'] = $produto->id;
            $especificacao['preco'] = str_replace(",", "", $especificacao['preco']);

            $especificacaoExistente = $this->produtoEspecificacaoModel
                    ->where('produto_id', $produto->id)
                    ->where('medida_id', $especificacao['medida_id'])
                    ->first();

            if ($especificacaoExistente) {
                return redirect()->back()->with('atencao', 'Essa Especificação ja existe para esse produto')->withInput();
            }

            if ($this->produtoEspecificacaoModel->save($especificacao)) {
                return redirect()->back()->with('sucesso', 'Especificação cadastrada com sucesso!');
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->produtoEspecificacaoModel->errors())
                                ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }
    
    public function excluirEspecificacao($especificacao_id = null, $produto_id=null) {
        $produto = $this->buscaProdutoOu404($produto_id);
        
        $especificacao = $this->produtoEspecificacaoModel
                            ->where('id', $especificacao_id)
                            ->where('produto_id', $produto_id)
                            ->first();
        if(!$especificacao){
            return redirect()->back()->with('atencao', 'Não encontramos a especificação!');
        }
        
        if($this->request->getMethod() === 'post'){
            $this->produtoEspecificacaoModel->delete($especificacao->id);
            
            return redirect()->to(site_url("admin/produtos/especificacoes/$produto->id"))
                            ->with('sucesso', 'A especificação foi excluida com sucesso!');
        }
        
        $data = [
            'titulo' => 'Exclusão de especificação do produto',
            'especificacao' => $especificacao,
            
        ];
        
        return view('Admin/Produtos/excluir_especificacao', $data);
    }
    
    
    public function excluir($id = null) {
        $produto = $this->buscaProdutoOu404($id);
        
        if($this->request->getMethod() === 'post'){
            
            $this->produtoModel->delete($id);
            
            
            
            return redirect()->to(site_url('admin/produtos'))->with('sucesso', 'Produto excluido com sucesso!');
        }
       
        
        $data = [
            'titulo' => "Excluindo o produto $produto->nome",
            'produto' => $produto,
            
        ];
        
        return view('Admin/Produtos/excluir', $data) ; 
    }
    
    public function desfazerExclusao($id = null) {
        $produto = $this->buscaProdutoOu404($id);

        if ($produto->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas produtos excluidos podem ser recuperados');
        }

        if ($this->produtoModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', 'Exclusão desfeita com sucesso');
        } else {
            return redirect()->back()
                            ->with('errors_model', $this->produtoModel->errors())
                            ->with('atencao', 'Dados inválidos, verifique os erros abaixo')
                            ->withInput();
        }
    }

    private function buscaProdutoOu404(int $id = null) {

        if (!$id || !$produto = $this->produtoModel->select('produtos.*, categorias.nome AS categoria')
                ->join('categorias', 'categorias.id = produtos.categoria_id')
                ->where('produtos.id', $id)
                ->withDeleted(true)
                ->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Nao encontramos o produto $id");
        }
        return $produto;
    }

}
