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
        <div class="col-md-6 ">
            <div class="panel panel-info">
                <div class="panel-body">
                    <dl>
                        <dt>Nome Completo</dt>
                        <dd><?php echo esc($usuario->nome);?></dd>
                        <hr>
                        <dt>E-mail</dt>
                        <dd><?php echo esc($usuario->email);?></dd>
                        <hr>
                        <dt>Telefone</dt>
                        <dd><?php echo esc($usuario->telefone);?></dd>
                        <hr>
                        <dt>CPF</dt>
                        <dd><?php echo esc($usuario->cpf);?></dd>
                        <hr>
                        <dt>Cliente Desde:</dt>
                        <dd><?php echo $usuario->criado_em->humanize();?></dd>
                        <hr>
                        
                    </dl>
                </div>
                <div class="panel-footer">
                    <a href="<?php echo site_url('conta/editar');?>" class="btn btn-primary">Editar</a>
                    <a href="<?php echo site_url('conta/editarsenha');?>" class="btn btn-danger">Alterar Senha</a>
                    
                    
                </div>
            </div>

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






