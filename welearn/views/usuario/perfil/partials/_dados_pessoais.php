<div id="dados-perfil-index-content">
    <header>
        <hgroup>
            <h1>Perfil de: <?=$usuarioPerfil->nome?></h1>
            <h3>Dados Pessoais de: <?=$usuarioPerfil->nome?>:</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if($possuiDadosPessoais && $usuarioPerfil->dadosPessoais->sexo != WeLearn_Usuarios_Sexo::NAO_EXIBIR):?>
            <?php if($usuarioPerfil->dadosPessoais->sexo != WeLearn_Usuarios_Sexo::NAO_EXIBIR
                || $usuarioPerfil->dadosPessoais->dataNascimento!='' ):?>
                <section>
                    <h4>Dados Principais</h4>
                    <dl>
                        <?if($usuarioPerfil->dadosPessoais->sexo != WeLearn_Usuarios_Sexo::NAO_EXIBIR):?>
                        <dt>Sexo</dt>
                        <dd><?=WeLearn_Usuarios_Sexo::getDescricao($usuarioPerfil->dadosPessoais->sexo)?></dd>
                        <?endif;?>
                        <?if($usuarioPerfil->dadosPessoais->dataNascimento !=''):?>
                        <dt>Data De Nascimento</dt>
                        <dd><?=$usuarioPerfil->dadosPessoais->dataNascimento?></dd>
                        <?endif;?>
                    </dl>
                </section>
                <?endif;?>
            <?php if($pais != '' || $estado != '' || $usuarioPerfil->dadosPessoais->cidade != ''
                || $usuarioPerfil->dadosPessoais->endereco !=''):?>
                <section>
                    <h4>Localização</h4>
                    <dl>
                        <?if($pais != ''):?>
                        <dt>Pais</dt>
                        <dd><?=$pais?></dd>
                        <?endif;?>
                        <?if($estado != ''):?>
                        <dt>estado</dt>
                        <dd><?=$estado?></dd>
                        <?endif;?>
                        <?if($usuarioPerfil->dadosPessoais->cidade != ''):?>
                        <dt>cidade</dt>
                        <dd><?=$usuarioPerfil->dadosPessoais->cidade?></dd>
                        <?endif;?>
                        <?if($usuarioPerfil->dadosPessoais->endereco !=''):?>
                        <dt>endereço</dt>
                        <dd><?=$usuarioPerfil->dadosPessoais->endereco?></dd>
                        <?endif;?>
                    </dl>
                </section>
                <?endif;?>
            <?php if($usuarioPerfil->dadosPessoais->descricaoPessoal !=''):?>
                <section>
                    <h4>Descricao Pessoal</h4>
                    <dl>
                        <?if($usuarioPerfil->dadosPessoais->descricaoPessoal !=''):?>
                        <dt>Sobre Mim</dt>
                        <dd><?=$usuarioPerfil->dadosPessoais->descricaoPessoal?></dd>
                        <?endif;?>
                    </dl>
                </section>
                <?endif;?>
            <?php if($usuarioPerfil->dadosPessoais->tel !='' || $usuarioPerfil->dadosPessoais->telAlternativo !=''
                || count($usuarioPerfil->dadosPessoais->listaDeIm)>0 || count($usuarioPerfil->dadosPessoais->listaDeRS)>0
                || $usuarioPerfil->dadosPessoais->homepage != ''):?>
                <section>
                    <h4>Contatos</h4>
                    <dl>
                        <?if($usuarioPerfil->dadosPessoais->tel !=''):?>
                        <dt>Telefone</dt>
                        <dd><?=$usuarioPerfil->dadosPessoais->tel?></dd>
                        <?endif;?>
                    </dl>
                    <dl>
                        <?if($usuarioPerfil->dadosPessoais->telAlternativo !=''):?>
                        <dt>Telefone Alternativo</dt>
                        <dd><?=$usuarioPerfil->dadosPessoais->telAlternativo?></dd>
                        <?endif;?>
                    </dl>
                    <dl>
                        <?if(count($usuarioPerfil->dadosPessoais->listaDeIm)>0):?>
                        <dt><label>Mensageiros Instantaneos</label></dt>
                        <dd>
                            <table id='lista-de-im'>
                                <?foreach ($usuarioPerfil->dadosPessoais->listaDeIm as $IM):?>
                                <tr>
                                    <td>Mensageiro Instantâneo (IM)</td>
                                    <td>Usuario no Mensageiro Instantâneo (IM)</td>
                                </tr>
                                <tr>
                                    <td><?php echo $IM->descricaoIM;?></td>
                                    <td><?php echo $IM->descricaoUsuarioIM;?></td>
                                </tr>
                                <?endforeach;?>
                            </table>
                        </dd>
                        <?endif;?>
                    </dl>
                    <dl>
                        <?if(count($usuarioPerfil->dadosPessoais->listaDeRS)>0):?>
                        <dt><label>Perfis Em Redes Sociais</label></dt>
                        <dd>
                            <table id='lista-de-rs'>
                                <?foreach ($usuarioPerfil->dadosPessoais->listaDeRS as $RS):?>
                                <tr>
                                    <td>Rede Social (RS)</td>
                                    <td>Usuario na Rede Social (RS)</td>
                                </tr>
                                <tr>
                                    <td><?php echo $RS->descricaoRS;?></td>
                                    <td><?php echo $RS->usuarioId;?></td>
                                </tr>
                                <?endforeach;?>
                            </table>
                        </dd>
                        <?endif;?>
                    </dl>
                    <dl>
                        <?if($usuarioPerfil->dadosPessoais->homepage != ''):?>
                        <dt>Pagina Pessoal</dt>
                        <dd><?=$usuarioPerfil->dadosPessoais->homepage?></dd>
                        <?endif;?>
                    </dl>
                </section>
            <?endif;?>
        <?php else:?>
            <h3>O usuario <?=$usuarioPerfil->nome?> ainda não cadastrou dados pessoais</h3>
       <?php endif;?>
    </div>
</div>