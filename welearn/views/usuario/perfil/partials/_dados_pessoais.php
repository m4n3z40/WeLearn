<div id="dados-perfil-index-content">
    <header>
        <hgroup>
            <h1>Perfil de <?=$usuarioPerfil->nome?></h1>
            <h3>Dados Pessoais de <?=$usuarioPerfil->nome?></h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if(!$dadosPessoais instanceof WeLearn_Usuarios_DadosPessoaisUsuario):?>
                <h3>O usuario <?=$usuarioPerfil->nome?> ainda não cadastrou dados pessoais</h3>
        <?php else:?>
            <?php if(($dadosPessoais->sexo == WeLearn_Usuarios_Sexo::NAO_EXIBIR && $dadosPessoais->dataNascimento==''
                && $pais == '' && $estado == '' && $dadosPessoais->cidade == ''&& $dadosPessoais->endereco ==''
                && $dadosPessoais->descricaoPessoal =='' && $dadosPessoais->tel =='' && $dadosPessoais->telAlternativo ==''
                && count($dadosPessoais->listaDeIm)==0 && count($dadosPessoais->listaDeRS)==0
                && $dadosPessoais->homepage == '')):?>

                      <h3>O usuario <?=$usuarioPerfil->nome?> ainda não cadastrou dados pessoais</h3>

                <?php else:?>
                    <?php if($dadosPessoais->sexo != WeLearn_Usuarios_Sexo::NAO_EXIBIR
                        || $dadosPessoais->dataNascimento!='' ):?>
                        <section>
                            <h4>Dados Principais</h4>
                            <dl>
                                <?if($dadosPessoais->sexo != WeLearn_Usuarios_Sexo::NAO_EXIBIR):?>
                                <dt>Sexo</dt>
                                <dd><?=WeLearn_Usuarios_Sexo::getDescricao($dadosPessoais->sexo)?></dd>
                                <?endif;?>
                                <?if($dadosPessoais->dataNascimento !=''):?>
                                <dt>Data De Nascimento</dt>
                                <dd><?=$dadosPessoais->dataNascimento?></dd>
                                <?endif;?>
                            </dl>
                        </section>
                        <?endif;?>
                    <?php if($pais != '' || $estado != '' || $dadosPessoais->cidade != ''
                        || $dadosPessoais->endereco !=''):?>
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
                                <?if($dadosPessoais->cidade != ''):?>
                                <dt>cidade</dt>
                                <dd><?=$dadosPessoais->cidade?></dd>
                                <?endif;?>
                                <?if($dadosPessoais->endereco !=''):?>
                                <dt>endereço</dt>
                                <dd><?=$dadosPessoais->endereco?></dd>
                                <?endif;?>
                            </dl>
                        </section>
                        <?endif;?>
                    <?php if($dadosPessoais->descricaoPessoal !=''):?>
                        <section>
                            <h4>Descricao Pessoal</h4>
                            <dl>
                                <?if($dadosPessoais->descricaoPessoal !=''):?>
                                <dt>Sobre Mim</dt>
                                <dd><?=$dadosPessoais->descricaoPessoal?></dd>
                                <?endif;?>
                            </dl>
                        </section>
                        <?endif;?>
                    <?php if($dadosPessoais->tel !='' || $dadosPessoais->telAlternativo !=''
                        || count($dadosPessoais->listaDeIm)>0 || count($dadosPessoais->listaDeRS)>0
                        || $dadosPessoais->homepage != ''):?>
                        <section>
                            <h4>Contatos</h4>
                            <dl>
                                <?if($dadosPessoais->tel !=''):?>
                                <dt>Telefone</dt>
                                <dd><?=$dadosPessoais->tel?></dd>
                                <?endif;?>
                            </dl>
                            <dl>
                                <?if($dadosPessoais->telAlternativo !=''):?>
                                <dt>Telefone Alternativo</dt>
                                <dd><?=$dadosPessoais->telAlternativo?></dd>
                                <?endif;?>
                            </dl>
                            <dl>
                                <?if(count($dadosPessoais->listaDeIm)>0):?>
                                <dt><label>Mensageiros Instantaneos</label></dt>
                                <dd>
                                    <table id='lista-de-im'>
                                        <?foreach ($dadosPessoais->listaDeIm as $IM):?>
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
                                <?if(count($dadosPessoais->listaDeRS)>0):?>
                                <dt><label>Perfis Em Redes Sociais</label></dt>
                                <dd>
                                    <table id='lista-de-rs'>
                                        <?foreach ($dadosPessoais->listaDeRS as $RS):?>
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
                                <?if($dadosPessoais->homepage != ''):?>
                                <dt>Pagina Pessoal</dt>
                                <dd><?=$dadosPessoais->homepage?></dd>
                                <?endif;?>
                            </dl>
                        </section>
                    <?endif;?>
                <?php endif;?>
        <?php endif;?>
    </div>
</div>