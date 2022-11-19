<?php

namespace App\Models;

use CodeIgniter\Model;

class EntregadorModel extends Model {

    protected $table = 'entregadores';
    protected $returnType = 'App\Entities\Entregador';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'cpf', 'cnh', 'email', 'telefone', 'imagem', 'ativo', 'veiculo', 'placa', 'endereco'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    protected $validationRules = [
        'nome' => 'required|min_length[4]|max_length[120]',
        'email' => 'required|valid_email|is_unique[entregadores.email]',
        'cpf' => 'required|validaCpf|exact_length[14]|is_unique[entregadores.cpf]',
        'cnh' => 'required|exact_length[11]|is_unique[entregadores.cnh]',
        'telefone' => 'required|exact_length[15]|is_unique[entregadores.telefone]',
        'endereco' => 'required|max_length[230]',
        'veiculo' => 'required|max_length[230]',
        'placa' => 'required|min_length[7]|max_length[8]|is_unique[entregadores.placa]',
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
        'cnh' => [
            'is_unique' => 'Desculpe!Essa CNH já está cadastrado.',
            'required' => 'Desculpe!O campo CNH é obrigatório.',
        ],
        'nome' => [
            'required' => 'Desculpe!O campo nome é obrigatório.',
        ],
        'telefone' => [
            'required' => 'Desculpe!O campo telefone é obrigatório.',
            'is_unique' => 'Desculpe!Esse telefone já está cadastrado.',
        ],
        'placa' => [
            'required' => 'Desculpe!O campo placa é obrigatório.',
            'is_unique' => 'Desculpe!Essa placa já está cadastrado.',
        ],
        'password' => [
            'required' => 'Desculpe!O campo senha é obrigatório.',
        ],
        'endereco' => [
            'required' => 'Desculpe!O campo endereço é obrigatório.',
        ],
        'veiculo' => [
            'required' => 'Desculpe!O campo veiculo é obrigatório.',
        ],
    ];
    
    public function procurar($term) {

        if ($term === null) {
            return [];
        }

        return $this->select(['id', 'nome'])
                        ->like('nome',$term)
                        ->withDeleted(true)
                        ->get()
                        ->getResult();
    }


    public function desfazerExclusao(int $id) {
        return $this->protect(false)
                        ->where('id', $id)
                        ->set('deletado_em', null)
                        ->update();
    }
    
    
    public function recuperaTotalEntregadoresAtivos() {
        return $this->where('ativo', true)
                    ->countAllResults();
    }

}
