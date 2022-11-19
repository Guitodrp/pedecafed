<h3>Obaaa, o seu pedido <?php echo esc($pedido->codigo); ?>acabou de sair para entrega!</h3>


<p>Olá <strong><?php echo esc($pedido->nome); ?></strong>, o seu pedido <strong><?php echo esc($pedido->codigo); ?></strong> saiu para entrega!</p>
<p>A forma de pagamento na entrega é :<strong><?php echo esc($pedido->forma_pagamento); ?></strong></p>
<p>Verificamos aqui que o endereço de entrega é : <strong><?php echo esc($pedido->endereco_entrega); ?></strong></p>
<p>Observações do pedido : <strong><?php echo esc($pedido->observacoes); ?></strong></p>
<hr>
<p><strong><?php echo esc($pedido->entregador->nome); ?></strong> irá entregar o seu pedido!</p>
<hr>
<p>
    Aproveite para ver os seus
    <a href="<?php echo site_url('conta'); ?>">pedidos</a>!
</p>