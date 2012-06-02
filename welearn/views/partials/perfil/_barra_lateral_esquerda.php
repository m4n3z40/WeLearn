
<section id="perfil-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <nav>
        <header>
            <figure>
                <figcaption>
                    <?php echo anchor('/usuario/' . $usuarioPerfil->id, $usuarioPerfil->toHTML('imagem_grande')) ?>
                </figcaption>
            </figure>
        </header>
        <ul>
            <li><?= anchor('/home','Home')?></li>
            <?php if($usuarioPerfil->getId() != $usuarioAutenticado->getId()):?>
               <input type='hidden' id='id-usuario-perfil' value='<?=$usuarioPerfil->id ?>'>
                <?php if($saoAmigos == WeLearn_Usuarios_StatusAmizade::NAO_AMIGOS):?>
                    <li><?= anchor('','Adicionar Amigo',array('id' => 'enviar-convite')) ?></li>
                <?php elseif($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS):?>
                    <li><?= anchor('/usuario/amigos/remover/'.$usuarioPerfil->getId(),'Remover Amizade',array('id' => 'remover-amizade'))?></li>
                <?php elseif($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA):?>
                    <li><?= anchor('#','Convite Pendente', array('id' => 'exibir-convite-pendente'))?>
                    <input type = 'hidden' id = 'id-convite' value = '<?= $convitePendente->id?>'/>
                    <input type = 'hidden' id = 'id-destinatario'  value = '<?= $convitePendente->destinatario->id?>'/>
                    <input type = 'hidden' id = 'id-remetente'  value = '<?= $convitePendente->remetente->id?>'/>
                    <?php if($convitePendente->destinatario->id == $usuarioAutenticado->id): ?>
                    <input type = 'hidden' id = 'tipo-convite' value = 'recebido'/>
                    <?php else:?>
                    <input type = 'hidden' id = 'tipo-convite' value = 'enviado'/>
                    <?php endif;?>

                <?php endif;?>
                    <li><?= anchor('usuario/mensagem/criar','Enviar Mensagem',array('id' => 'enviar-mensagem'))?></li>
            <?php endif; ?>
            <li><?= anchor('perfil/dados_pessoais/'.$usuarioPerfil->id,'Dados Pessoais')?></li>
            <li><?= anchor('perfil/dados_profissionais/'.$usuarioPerfil->id,'Dados Profissionais')?></li>
        </ul>
    </nav>
</section>