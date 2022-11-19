<h3><?php echo $usuario->nome; ?>, agora falta pouco!</h3>


<p>Clique no link abaixo para ativar sua conta</p>

<p>

    <a href="<?php echo site_url('registrar/ativar/' . $usuario->token); ?>">Ativar minha conta</a>
</p>