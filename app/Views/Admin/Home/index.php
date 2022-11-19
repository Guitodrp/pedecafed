<?php echo $this->extend('Admin/layout/principal'); ?>


<!-- Aqui colocamos complemento de titulo na view atual -->
<?php echo $this->section('titulo') ?> <?php echo $titulo; ?> <?php echo $this->endSection() ?>




<?php echo $this->section('estilos') ?>
<!-- Aqui Enviamos para o template principal os estilos -->


<?php echo $this->endSection() ?>




<?php echo $this->section('conteudo') ?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body dashboard-tabs p-0">
                <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                        <div class="d-flex flex-wrap justify-content-xl-between">
                            <div class="d-none d-xl-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-currency-usd mr-3 icon-lg text-primary"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Pedidos Entregues
                                        <span class='badge badge-pill badge-primary'><?php echo $valorPedidosEntregues->total; ?></span>
                                    </small>
                                    <h5 class="mr-2 mb-0">R$&nbsp;<?php echo number_format($valorPedidosEntregues->valor_pedido, 2); ?></h5>
                                </div>
                            </div>
                            <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Pedidos Cancelados
                                        <span class='badge badge-pill badge-danger'><?php echo $valorPedidosCancelados->total; ?></span>
                                    </small>
                                    <h5 class="mr-2 mb-0">R$&nbsp;<?php echo number_format($valorPedidosCancelados->valor_pedido, 2); ?></h5>
                                </div>
                            </div>
                            <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-account-multiple mr-3 icon-lg text-success"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Clientes Ativos</small>
                                    <h5 class="mr-2 mb-0"><?php echo $totalClientesAtivos; ?></h5>
                                </div>
                            </div>
                            <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-motorbike mr-3 icon-lg text-warning"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Entregadores Ativos</small>
                                    <h5 class="mr-2 mb-0"><?php echo $totalEntregadoresAtivos; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <?php $expedienteHoje = expedienteHoje(); ?>

                <?php if ($expedienteHoje->situacao == false) : ?>


                    <h4 class='text-info'><i class="mdi mdi-calendar-alert"></i>&nbsp;Hoje é <?php echo esc($expedienteHoje->dia_descricao); ?> e estamos fechados.Portanto não há novos pedidos!</h4>

                <?php else : ?>

                    <div id="atualiza">
                        <?php if (!isset($novosPedidos)) : ?>

                            <h4 class='text-info'>Não há novos pedidos no momento!  <?php echo date('d/m/Y H:i:s ') ?></h4>

                        <?php else: ?>

                            <h6><i class="mdi mdi-shopping"></i>&nbsp;Novos Pedidos Realizados</h6>
                            <hr class="border-primary">

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr> 
                                            <th>Código do Pedido</th>
                                            <th>Data do Pedido</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php foreach ($novosPedidos as $pedido): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo site_url("admin/pedidos/show/$pedido->codigo"); ?>"><?php echo $pedido->codigo; ?></a>
                                                </td>
                                                <td><?php echo $pedido->criado_em->humanize(); ?></td>
                                                <td>R$&nbsp;<?php echo esc(number_format($pedido->valor_pedido, 2)); ?></td>

                                            </tr>
                                        <?php endforeach; ?>   

                                    </tbody>
                                </table>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>




            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Produtos + Vendidos</p>
                <ul class="list-arrow">

                    <?php if (!isset($produtosMaisVendidos)): ?>
                        <p class="text-info card-title">Não há dados para exibir no momento!</p>
                    <?php else : ?>

                        <?php foreach ($produtosMaisVendidos as $produto): ?>
                            <li class="mb-2">
                                <?php echo word_limiter($produto->produto, 5); ?>
                                <span class="badge badge-pill badge-primary float-right"><?php echo esc($produto->quantidade) ?></span>
                            </li>


                        <?php endforeach; ?>

                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Clientes + Pediram</p>
                <ul class="list-arrow">

                    <?php if (!isset($clientesMaisCompraram)): ?>
                        <p class="text-info card-title">Não há dados para exibir no momento!</p>
                    <?php else : ?>

                        <?php foreach ($clientesMaisCompraram as $cliente): ?>
                            <li class="mb-2">
                                <?php echo esc($cliente->nome); ?>
                                <span class="badge badge-pill badge-primary float-right"><?php echo esc($cliente->pedidos) ?></span>
                            </li>


                        <?php endforeach; ?>

                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    
</div>



<?php echo $this->endSection() ?>




<?php echo $this->section('scripts') ?>

<script>

    setInterval("atualiza();", 60000); //2 min em milisegundos

    function atualiza() {
        $("#atualiza").load('<?php echo site_url('admin/home'); ?>' + ' #atualiza');
    }

</script>

<?php echo $this->endSection() ?>