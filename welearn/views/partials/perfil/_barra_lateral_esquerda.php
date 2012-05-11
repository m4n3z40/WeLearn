<section id="perfil-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <nav>
        <h3>perfil</h3>
        <ul>
            <?php if($usuarioPerfil->getId() != $usuarioAutenticado->getId()):?>
                <?php if($saoAmigos == WeLearn_Usuarios_StatusAmizade::NAO_AMIGOS):?>
                    <li><?= anchor('','Adicionar Amigo',array('id' => 'enviar-convite')) ?></li>
                <?php elseif($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS):?>
                    <li><?= anchor('','Remover Amizade',array('id' => 'remover-amizade'))?></li>
                <?php elseif($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA):?>
                    <li><?= anchor('#','Convite Pendente', array('id' => 'exibir-convite-pendente'))?>
                <?php endif;?>
                    <li><?= anchor('usuario/mensagem/criar','Enviar Mensagem',array('id' => 'perfil-enviar-mensagem'))?></li>
            <?php endif; ?>
        </ul>
    </nav>
</section>