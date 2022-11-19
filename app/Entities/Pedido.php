<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Pedido extends Entity {

    protected $dates = [
        'criado_em',
        'atualizado_em',
        'deletado_em'
    ];

    public function exibeSituacaoPedido() {
        switch ($this->situacao) {
            case 0:
                echo "<i class='fa fa-thumbs-up fa fa-lg text-primary' aria-hidden='true'></i>&nbsp; Pedido realizado!";

                break;
            case 1:
                echo "<i class='fa fa-motorcycle fa fa-lg text-warning' aria-hidden='true'></i>&nbsp; Saiu para entrega!";

                break;
            case 2:
                echo "<i class='fa fa-check-square-o fa fa-lg text-success' aria-hidden='true'></i>&nbsp; Pedido entregue!";

                break;
            case 3:
                echo "<i class='fa fa-times fa fa-lg text-danger' aria-hidden='true'></i>&nbsp; Pedido cancelado!";

                break;
        }
    }

}
