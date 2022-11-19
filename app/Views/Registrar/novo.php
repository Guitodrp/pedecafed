<?php echo $this->extend('layout/principal_web'); ?>


<?php echo $this->section('titulo') ?> <?php echo $titulo; ?> <?php echo $this->endSection() ?>




<?php echo $this->section('estilos') ?>

<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>"/>

<style>
    @media only screen and (max-width: 767px){
        
        #registrar{
            min-width: 100% !important;
        }
        
    }
</style>

<?php echo $this->endSection() ?>




<?php echo $this->section('conteudo') ?>

<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">
    <div id="registrar" class="product-content product-wrap clearfix product-deatil center-block" style="max-width: 40%">
        <div class="row">
            <div class="col-md-12">
                <h3><?php echo $titulo; ?></h3>

                <?php if (session()->has('errors_model')): ?>
                    <ul style="margin-left:  -1.6em !important">
                        <?php foreach (session('errors_model')as $error): ?>
                            <li class="text-danger"><?php echo $error ?></li>

                        <?php endforeach; ?>

                    </ul>
                <?php endif; ?>

                <?php echo form_open("registrar/criar"); ?>
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo old('nome'); ?>">
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo old('email'); ?>" placeholder="usuario@usuario.com">
                </div>
                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" class="cpf form-control" name="cpf" value="<?php echo old('cpf'); ?>" placeholder="___.___.___-__">
                </div>

                <div class="form-group">
                    <label>Sua Senha</label>
                    <input type="password" class=" form-control" name="password" placeholder="****">
                </div>
                <div class="form-group">
                    <label>Confirme Sua Senha</label>
                    <input type="password" class=" form-control" name="password_confirmation"placeholder="****">
                </div>

                <button type="submit" class="btn btn-block btn-food" style="margin-top: 3em;">Cria minha conta</button>
                <?php echo form_close(); ?>

            </div>
        </div>

    </div>


</div>



<?php echo $this->endSection() ?>




<?php echo $this->section('scripts') ?>


<script src="<?php echo site_url('admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
<script src="<?php echo site_url('admin/vendors/mask/app.js'); ?>"></script>


<?php echo $this->endSection() ?>






