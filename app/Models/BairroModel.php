<?php

namespace App\Models;

use CodeIgniter\Model;

class BairroModel extends Model {

    protected $table = 'bairros';
    protected $returnType = 'App\Entities\Bairro';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'slug', 'valor_entrega', 'ativo'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[120]|is_unique[bairros.nome,id,{id}]',
        'cidade' => 'required|equals[Itabirito]',
        'valor_entrega' => 'required',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'Desculpe!O campo nome é obrigatório.',
            'is_unique' => 'Esse bairro já existe.'
        ],
        'valor_entrega' => [
            'required' => 'Desculpe!O campo valor de entrega é obrigatório.',
        ],
        'cidade' => [
            'equals' => 'Por favor cadastre apenas bairros de Itabirito - MG',
        ],
    ];
    protected $beforeInsert = ['criaSlug'];
    protected $beforeUpdate = ['criaSlug'];

    protected function criaSlug(array $data) {
        if (isset($data['data']['nome'])) { //verificar se existe e se estiver setada
            $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', TRUE); //cria uma chave chamada slug a partir do nome da categoria com traco em seaparador
        }
        return $data;
    }
    
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

    public function desfazerExclusao(int $id) {
        return $this->protect(false)
                        ->where('id', $id)
                        ->set('deletado_em', null)
                        ->update();
    }

}
