<?php echo $this->extend('Admin/layout/principal'); ?>


<--<!-- Aqui colocamos complemento de titulo na view atual -->
<?php echo $this->section('titulo') ?> <?php echo $titulo; ?> <?php echo $this->endSection() ?>




<?php echo $this->section('estilos') ?>

<--<!-- Aqui Enviamos para o template principal os estilos -->

<link rel="stylesheet" href="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.css'); ?>"/>


<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>

<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo $titulo; ?></h4>

                <div class="ui-widget text-center">
                    <input id="query" name="query" placeholder="Pesquise por um bairro de Itabirito" class="form-control bg-light mb-5">
                </div>

                <a href="<?php echo site_url("admin/bairros/criar") ?>" class="btn btn btn-success float-right mb-5">
                    <i class="mdi mdi-plus btn-icon-prepend "></i>
                    Cadastrar  
                </a>
                <?php if (empty($bairros)): ?>

                    <p>Não há dados para exibir!</p>

                <?php else : ?>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr> 
                                    <th>Nome</th>
                                    <th>Valor de Entrega</th>
                                    <th>Criado em</th>
                                    <th>Ativo</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bairros as $bairro): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo site_url("admin/bairros/show/$bairro->id"); ?>"><?php echo $bairro->nome; ?></a>
                                        </td>
                                        <td>R$&nbsp;<?php echo esc(number_format($bairro->valor_entrega, 2)); ?></td>
                                        <td><?php echo $bairro->criado_em->humanize(); ?></td>
                                        <td><?php echo ($bairro->ativo && $bairro->deletado_em == null ? '<label class="badge badge-pill badge-primary">Sim</label>' : '<label class="badge badge-pill badge-danger">Não</label>') ?>
                                        </td>
                                        <td><?php echo ($bairro->deletado_em == null ? '<label class="badge badge-pill badge-primary">Disponivel</label>' : '<label class="badge badge-pill badge-danger">Excluido</label>') ?>
                                            <?php if ($bairro->deletado_em != null): ?>
                                                <a href="<?php echo site_url("admin/bairros/desfazerexclusao/$bairro->id") ?>" class="badge badge-pill badge-dark ml-2">
                                                    <i class="mdi mdi-undo btn-icon-prepend "></i>
                                                    Desfazer 
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>   

                            </tbody>
                        </table>

                        <div class="mt-3">

                            <?php echo $pager->links() ?>
                        </div>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>

</div>


<?php echo $this->endSection() ?>




<?php echo $this->section('scripts') ?>

<--<!-- Aqui Enviamos para o template principal os scripts -->
<script src="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.js'); ?>"></script>

<script>

    $(function () {
        $("#query").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo site_url('admin/bairros/procurar'); ?>",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        if (data.length < 1) {
                            var data = [
                                {
                                    label: 'Bairro de Itabirito não encontrada',
                                    value: -1
                                }
                            ];
                        }
                        response(data); //aqui temos valor no data
                    }
                }); //fim ajax
            },
            minLenght: 1,
            select: function (event, ui) {
                if (ui.item.value === -1) {
                    $(this).val("");
                    return false;
                } else {
                    window.location.href = '<?php echo site_url('admin/bairros/show/'); ?>' + ui.item.id;
                }
            }
        }); //fim auto complete
    });

</script>

<?php echo $this->endSection(); ?>