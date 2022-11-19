<?php echo $this->extend('Admin/layout/principal'); ?>


<--<!-- Aqui colocamos complemento de titulo na view atual -->
<?php echo $this->section('titulo') ?> <?php echo $titulo; ?> <?php echo $this->endSection() ?>





<?php echo $this->section('conteudo') ?>

<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo $titulo; ?></h4>


                <?php if (session()->has('errors_model')): ?>
                    <ul>
                        <?php foreach (session('errors_model')as $error): ?>
                            <li class="text-danger"><?php echo $error ?></li>

                        <?php endforeach; ?>

                    </ul>
                <?php endif; ?>

                <?php echo form_open("admin/expedientes/expedientes", ['class' => 'form-row']); ?>
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr> 
                                <th>Dia</th>
                                <th>Abertura</th>
                                <th>Fechamento</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expedientes as $dia): ?>
                                <tr>
                                    <td class="form-group col-md-3">
                                        <input type="type" name="dia_descricao[]" class="form-control text-center" value="<?php echo esc($dia->dia_descricao); ?>" readonly="">
                                    </td>
                                    <td class="form-group col-md-3">
                                        <input type="time" name="abertura[]" class="form-control text-center" value="<?php echo esc($dia->abertura); ?>" required="">
                                    </td>
                                    <td class="form-group col-md-3">
                                        <input type="time" name="fechamento[]" class="form-control text-center" value="<?php echo esc($dia->fechamento); ?>" required="">
                                    </td>
                                    <td class="form-group col-md-3">
                                        <select class="form-control text-center" name="situacao[]" required="">
                                            <option value="1" <?php echo ($dia->situacao ? 'selected' : '');?>>Aberto</option>
                                            <option value="0" <?php echo (!$dia->situacao ? 'selected' : '');?>>Fechado</option>
                                        </select>
                                    </td>

                                </tr>
                            <?php endforeach; ?>   



                        </tbody>

                        

                    </table>
                            <button type="submit" class="btn btn-primary btn-sm mt-3 text-center">
                                <i class="mdi mdi-content-save-all btn-icon-prepend"></i>
                                Salvar</button>


                </div>
                <?php echo form_close(); ?>

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