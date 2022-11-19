<h3>Pedido <?php echo esc($pedido->codigo); ?>, realizado com sucesso!</h3>


<p>Olá <strong><?php echo esc($pedido->usuario->nome);?></strong>, recebemos o seu pedido <strong><?php echo esc($pedido->codigo);?></strong>!</p>
<p>Estamos acelerando do lado de cá para que o seu pedido fique pronto rapidinho. Já já ele estará saindo pra entrega.</p>
<p>Não se preocupe, quando estiver pronto avisaremos você novamente. Ok?!</p>
<p>Enquanto isso,<a href="<?php echo site_url('conta');?>"> clique aqui para acompanhar seus pedidos!</a></p>