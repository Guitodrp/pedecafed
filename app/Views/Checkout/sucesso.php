<?php echo $this->extend('layout/principal_web'); ?>


<?php echo $this->section('titulo') ?> <?php echo $titulo; ?> <?php echo $this->endSection() ?>




<?php echo $this->section('estilos') ?>

<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>"/>

<style>
    @media only screen and (max-width: 767px){
        .section-title {
            font-size: 20px !important;
            margin-top: -6em !important;
        }
        
        #titulo-sucesso{
            
            margin-top: -6em !important;
        }
    }
</style>

<?php echo $this->endSection() ?>




<?php echo $this->section('conteudo') ?>

<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">
    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="product-content product-wrap clearfix product-deatil">
            <div class="row ">
                <?php if ($pedido->situacao == 0): ?>
                    <div class="col-md-12 col-xs-12">
                        <h3 class="section-title text-center"><?php echo esc($titulo); ?></h3>
                    </div>
                <?php endif; ?>

                <div id="titulo-sucesso" class='col-md-12 col-xs-12'>

                    <h4>No momento o seu pedido está com o status de  <?php echo $pedido->exibeSituacaoPedido(); ?></h4>
                </div>

                <?php if ($pedido->situacao != 3): ?>
                    <div class="col-md-12 col-xs-12">
                        <h5>Quando ocorrer uma mudança no status do seu pedido, nós notificaremos voce por e-mail.</h5>
                    </div>
                <?php endif; ?>


                <div class="col-md-12">
                    <ul class="list-group">

                        <?php foreach ($produtos as $produto): ?>

                            <li class="list-group-item">
                                <div>
                                    <h4><?php echo ellipsize($produto['nome'], 100); ?></h4>
                                    <p class="text-muted">Quantidade: <?php echo $produto['quantidade']; ?></p>
                                    <p class="text-muted">Preço: R$ <?php echo $produto['preco']; ?></p>

                            </li>

                        <?php endforeach; ?>

                        <li class="list-group-item" >
                            <span>Data do Pedido :</span>
                            <strong><?php echo $pedido->criado_em->humanize(); ?></strong>
                        </li>
                        <li class="list-group-item" >
                            <span>Total de Produtos :</span>
                            <strong><?php echo'&nbsp;R$ ' . number_format($pedido->valor_produtos, 2); ?></strong>
                        </li>
                        <li class="list-group-item" >
                            <span>Taxa de Entrega :</span>
                            <strong><?php echo'&nbsp;R$ ' . number_format($pedido->valor_entrega, 2); ?></strong>
                        </li>
                        <li class="list-group-item" >
                            <span>Total Final do Pedido :</span>
                            <strong><?php echo'&nbsp;R$ ' . number_format($pedido->valor_pedido, 2); ?></strong>
                        </li>
                        <li class="list-group-item" >
                            <span>Endereço de Entrega: </span>
                            <strong><?php echo esc($pedido->endereco_entrega); ?></strong>
                        </li>
                        <li class="list-group-item" >
                            <span>Forma de Pagamento na Entrega: </span>
                            <strong><?php echo esc($pedido->forma_pagamento); ?></strong>
                        </li>
                        <li class="list-group-item" >
                            <span>Observações do Pedido: </span>
                            <strong><?php echo esc($pedido->observacoes); ?></strong>
                        </li>


                    </ul>

                    <div class="text-center">
                        <a href="<?php echo site_url("/"); ?>" class="btn btn-sm btn-food">Voltar as Compras</a>

                    </div> 
                </div> 
            </div>

        </div>

    </div>


</div>



<?php echo $this->endSection() ?>








