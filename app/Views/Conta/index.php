<?php echo $this->extend('layout/principal_web'); ?>


<?php echo $this->section('titulo') ?> <?php echo $titulo; ?> <?php echo $this->endSection() ?>




<?php echo $this->section('estilos') ?>

<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/conta.css"); ?>"/>

<?php echo $this->endSection() ?>




<?php echo $this->section('conteudo') ?>

<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em; min-height: 300px">

    <?php echo $this->include("conta/sidebar"); ?>
    <div class="row" style="margin-top: 2em">
        <div class="col-md-12 col-xs-12">
            <h3 class="section-title pull-left"><?php echo esc($titulo); ?></h3>
        </div>
        <div class="col-md-12 ">

            <?php if (empty($pedidos)): ?>
            <h4 >Ainda não realizou nenhum pedido, <a href="<?php echo site_url('/');?>"> clique aqui para fazer seu primeiro pedido!</a>!</h4>
            <?php else: ?>




                <?php foreach ($pedidos as $key => $pedido): ?>
                    <div class="panel-group">

                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse<?php echo $key; ?>">Pedido <?php echo esc($pedido->codigo); ?> - <?php echo $pedido->exibeSituacaoPedido();?> - <?php echo esc($pedido->criado_em->humanize()); ?></a>
                                </h4>
                            </div>
                            <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse">
                                
                                <div class="panel-body">
                                    <h5>Situação do Pedido: <?php echo $pedido->exibeSituacaoPedido();?></h5>
                                    <ul class="list-group">
                                        <?php $produtos = unserialize($pedido->produtos) ;?>
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
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>


        </div>
    </div>
</div>



<?php echo $this->endSection() ?>




<?php echo $this->section('scripts') ?>

<script>

    /* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    /* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }

</script>

<?php echo $this->endSection() ?>






