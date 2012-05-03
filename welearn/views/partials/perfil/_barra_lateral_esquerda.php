<section id="perfil-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <nav>
        <h3>perfil</h3>
        <ul>

            <?php if(!$saoAmigos):?>
                <li><?php echo anchor('','Adicionar Amigo',array('id' => 'enviar-convite')) ?></li>
            <?php else:?>
                <lir><?php echo anchor('','Remover Amizade',array('id' => 'remover-amizade'))?></lir>
            <?php endif;?>
            <li><?php echo anchor('','Enviar Mensagem')?></li>
        </ul>
    </nav>
</section>