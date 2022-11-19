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
                    <input id="query" name="query" placeholder="Pesquise por um usuario" class="form-control bg-light mb-5">
                </div>

                <a href="<?php echo site_url("admin/usuarios/criar") ?>" class="btn btn btn-success float-right mb-5">
                    <i class="mdi mdi-plus btn-icon-prepend "></i>
                    Cadastrar  
                </a>



                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>CPF</th>
                                <th>Administrador</th>
                                <th>Ativo</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><a href="<?php echo site_url("admin/usuarios/show/$usuario->id"); ?>"><?php echo $usuario->nome; ?></a></td>
                                    <td><?php echo $usuario->email; ?></td>
                                    <td><?php echo $usuario->cpf; ?></td>
                                    <td><?php echo ($usuario->is_admin ? '<label class="badge badge-pill badge-primary">Sim</label>' : '<label class="badge badge-pill badge-danger">Não</label>' ) ?></td>
                                    <td><?php echo ($usuario->ativo && $usuario->deletado_em == null ? '<label class="badge badge-pill badge-primary">Sim</label>' : '<label class="badge badge-pill badge-danger">Não</label>') ?></td>
                                    <td><?php echo ($usuario->deletado_em == null ? '<label class="badge badge-pill badge-primary">Disponivel</label>' : '<label class="badge badge-pill badge-danger">Excluido</label>') ?>
                                        <?php if ($usuario->deletado_em != null): ?>
                                            <a href="<?php echo site_url("admin/usuarios/desfazerexclusao/$usuario->id") ?>" class="badge badge-pill badge-dark ml-2">
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
            </div>
        </div>
    </div>

</div>


<?php echo $this->endSection() ?>




<?php echo $this->section('scripts') ?>

<--<!-- Aqui Enviamos para o template principal os scripts -->
<script 
    src="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.js'); ?>">
</script>

<script>

    $(function () {
        $("#query").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo site_url('admin/usuarios/procurar'); ?>",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        if (data.length < 1) { //nao foi encontrado nada
                            var data = [
                                {
                                    label: 'Usuario não encontrado',
                                    value: -1
                                }
                            ];
                        }
                        response(data); //aqui temos valor no data
                    },
                }); //fim ajax
            },
            minLenght: 1,
            select: function (event, ui) {
                if (ui.item.value == -1) {
                    $(this).val(""); //retorna valor nao selecionavel
                    return false;
                } else {
                    window.location.href = '<?php echo site_url('admin/usuarios/show/'); ?>' + ui.item.id; //retorna e concatena com o valor de ui
                }
            }
        }); //fim auto complete
    });

</script>

<?php echo $this->endSection(); ?>