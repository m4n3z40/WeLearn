
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
            <?php if($usuarioPerfil->getId() != $usuarioAutenticado->getId()):?>
               <input type='hidden' id='id-usuario-perfil' value='<?echo $usuarioPerfil->id ?>'>
               <input type='hidden' id='nome-usuario-perfil' value='<?echo $usuarioPerfil->nome?>'>
                <?php if($saoAmigos == WeLearn_Usuarios_StatusAmizade::NAO_AMIGOS):?>
                    <li><?= anchor('','Adicionar Amigo',array('id' => 'enviar-convite')) ?></li>
                <?php elseif($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS):?>
                    <li><?= anchor('/usuario/amigos/remover/'.$usuarioPerfil->getId(),'Remover Amizade',array('id' => 'remover-amizade'))?></li>
                <?php elseif($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA):?>
                    <li><?= anchor('#','Convite Pendente', array('id' => 'exibir-convite-pendente'))?></li>
                    <input type = 'hidden' id = 'id-convite' value = '<?= $convitePendente->id?>'/>
                    <input type = 'hidden' id = 'msg-convite' value = '<?= $convitePendente->msgConvite?>'/>
                    <input type = 'hidden' id = 'id-destinatario'  value = '<?= $convitePendente->destinatario->id?>'/>
                    <input type = 'hidden' id = 'id-remetente'  value = '<?= $convitePendente->remetente->id?>'/>
                    <?php if($convitePendente->destinatario->id == $usuarioAutenticado->id): ?>
                        <input type = 'hidden' id = 'tipo-convite' value = 'recebido'/>
                    <?php else:?>
                        <input type = 'hidden' id = 'tipo-convite' value = 'enviado'/>
                    <?php endif;?>
                <?php endif;?>

                <?php if($usuarioPerfil->configuracao->privacidadeMP == WeLearn_Usuarios_PrivacidadeMP::LIVRE ||
                        ($usuarioPerfil->configuracao->privacidadeMP == WeLearn_Usuarios_PrivacidadeMP::SO_AMIGOS
                        && $saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS)
                ):?>
                    <li><?= anchor('usuario/mensagem/criar','Enviar Mensagem',array('id' => 'enviar-mensagem'))?></li>
                <?php endif;?>

            <?php endif; ?>
            <?php if(($usuarioPerfil->configuracao->privacidadePerfil == WeLearn_Usuarios_PrivacidadePerfil::PUBLICO) ||
                    ($usuarioPerfil->configuracao->privacidadePerfil == WeLearn_Usuarios_PrivacidadePerfil::PRIVADO
                    && $saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS)):?>
                <li><?= anchor('perfil/dados_pessoais/'.$usuarioPerfil->id,'Dados Pessoais')?></li>
                <li><?= anchor('perfil/dados_profissionais/'.$usuarioPerfil->id,'Dados Profissionais')?></li>
                <li><?= anchor('/perfil/listar_certificados/'.$usuarioPerfil->id,'Certificados')?></li>
            <?php endif?>
        </ul>
    </nav>
</section>