<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Token;

class UsuarioModel extends Model {

    protected $table = 'usuarios';
    protected $returnType = 'App\Entities\Usuario';
    protected $allowedFields = ['nome', 'cpf', 'email', 'telefone', 'password', 'reset_hash', 'reset_expira_em', 'ativacao_hash']; //somente esses campos serao alterados no bd
    //Dates
    protected $useTimestamps = true; // Colunas populadas
    protected $createdField = 'criado_em'; // Nome da coluna no banco de dados
    protected $updatedField = 'atualizado_em'; // Nome da coluna no banco de dados
    protected $dateFormat = 'datetime'; // para uso com o softdelete
    protected $useSoftDeletes = true;
    protected $deletedField = 'deletado_em'; // Nome da coluna no banco de dados
    //Validacoes
    protected $validationRules = [
        'nome' => 'required|min_length[4]|max_length[120]',
        'email' => 'required|valid_email|is_unique[usuarios.email]',
        'cpf' => 'required|validaCpf|exact_length[14]|is_unique[usuarios.cpf]',
        'telefone' => 'required',
        'password' => 'required|min_length[6]',
        'password_confirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Desculpe!Esse email já está cadastrado.',
            'required' => 'Desculpe!O campo e-mail é obrigatório.',
        ],
        'cpf' => [
            'is_unique' => 'Desculpe!Esse CPF já está cadastrado.',
            'required' => 'Desculpe!O campo CPF é obrigatório.',
        ],
        'nome' => [
            'required' => 'Desculpe!O campo nome é obrigatório.',
        ],
        'telefone' => [
            'required' => 'Desculpe!O campo telefone é obrigatório.',
        ],
        'password' => [
            'required' => 'Desculpe!O campo senha é obrigatório.',
        ],
    ];
    // Eventos de callback
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data) {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);
        }
        return($data);
    }

    // uso controler usuarios no metodo procurar com o autocomplete param string $term e retun array usuarios



    public function procurar($term) {

        if ($term === null) {
            return [];
        }

        return $this->select(['id', 'nome'])
                        ->like('nome', $term)
                        ->withDeleted(true)
                        ->get()
                        ->getResult();
    }

    public function desabilitaValidacaoSenha() {
        unset($this->validationRules['password']);
        unset($this->validationRules['password_confirmation']);
    }

    public function desabilitaValidacaoTelefone() {
        unset($this->validationRules['telefone']);
    }

    public function desfazerExclusao(int $id) {
        return $this->protect(false)
                        ->where('id', $id)
                        ->set('deletado_em', null)
                        ->update();
    }

    /**
     * @uso Classe Autenticacao
     * @param string $email
     * @return objeto $usuario
     */
    public function buscaUsuarioPorEmail(string $email) {

        return $this->where('email', $email)->first();
    }

    public function buscaUsuarioParaResetarSenha(string $token) {
        $token = new Token($token);

        $tokenHash = $token->getHash();

        $usuario = $this->where('reset_hash', $tokenHash)->first();

        if ($usuario != null) {

            if ($usuario->reset_expira_em < date('Y-m-d H:i:s')) { //verifica se o token esta expirado
                $usuario = null; // se sim o usuario recebe null
            }

            return $usuario;
        }
    }

    public function ativarContaPeloToken(string $token) {
        $token = new Token($token);
        $token_hash = $token->getHash();
        $usuario = $this->where('ativacao_hash', $token_hash)->first();

        if ($usuario != null) {
            $usuario->ativar();

            $this->protect(false)->save($usuario);
        }
    }

    public function recuperaTotalClientesAtivos() {
        return $this->where('is_admin', false)
                        ->where('ativo', true)
                        ->countAllResults();
    }

}
