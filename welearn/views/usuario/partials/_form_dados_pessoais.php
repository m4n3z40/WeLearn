<h3>Dados Pessoais</h3>
<?php echo form_open('', $extraOpenForm) ?>
    <fieldset>
        <legend>Dados Principais</legend>
        <dl>
            <dt><label for="slt-sexo">Sexo</label></dt>
                <dd><?php echo form_dropdown('sexo', $listaSexo, $sexoAtual, 'id="slt-sexo"') ?></dd>
            <dt><label for="txt-data-nascimento">Data de Nascimento</label></dt>
                <dd><input type="text" name="dataNascimento" id="txt-data-nascimento" value="<?php echo $dataNascimentoAtual ?>" /></dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>Localização</legend>
        <dl>
            <dt><label for="slt-pais">Pais</label></dt>
                <dd><?php echo form_dropdown('pais', $listaPais, $paisAtual, 'id="slt-pais"') ?></dd>
            <dt><label for="slt-estado">Estado</label></dt>
                <dd><?php echo form_dropdown('estado', $listaEstado, $estadoAtual, 'id="slt-estado"') ?></dd>
            <dt><label for="txt-cidade">Cidade</label></dt>
                <dd><input type="text" name="cidade" id="txt-cidade" value="<?php echo $cidadeAtual ?>" /></dd>
            <dt><label for="txt-endereco">Endereco</label></dt>
                <dd><input type="text" name="endereco" id="txt-endereco" value="<?php echo $enderecoAtual ?>" /></dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>Descrição Pessoal</legend>
        <dl>
            <dt><label for="txt-sobre-mim">Sobre Mim</label></dt>
                <dd><textarea cols="100" rows="10" name="descricaoPessoal" id="txt-sobre-mim"><?php echo $descricaoPessoalAtual ?></textarea></dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>Contatos</legend>
        <dl>
            <dt><label for="txt-telefone">Telefone</label></dt>
                <dd><input type="text" name="tel" id="txt-telefone" value="<?php echo $telAtual ?>" </dd>
            <dt><label for="txt-telefone-alt">Telefone Alternativo</label></dt>
                <dd><input type="text" name="telAlternativo" id="txt-telefone-alt" value="<?php echo $telAlternativoAtual ?>" /></dd>
            <dt><label>Mensageiros Instântaneos</label></dt>
                <dd>
                    <table id="tbl-lista-im">
                        <tbody>
                        <?php foreach($listaDeIM as $IM): ?>
                            <tr>
                                <td><?php echo $IM['im'] ?></td>
                                <td><?php echo $IM['imUsuario'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <button type="button" id="btn-add-im">Adicionar IM</button>
                                    <button type="button" id="btn-remove-im">Remover Último IM</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </dd>
            <dt><label>Perfis em Redes Sociais</label></dt>
                <dd>
                    <table id="tbl-lista-rs">
                        <tbody>
                        <?php foreach($listaDeRS as $RS): ?>
                            <td><?php echo $RS['rs'] ?></td>
                            <td><?php echo $RS['rsUsuario'] ?></td>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2">
                                <button type="button" id="btn-add-rs">Adicionar Rede Social</button>
                                <button type="button" id="btn-remove-rs">Remover Última Rede Social</button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </dd>
            <dt><label for="txt-homepage">Pagina Pessoal</label></dt>
                <dd><input type="text" name="homePage" id="txt-homepage" value="<?php echo $homePageAtual ?>" /></dd>
        </dl>
    </fieldset>
<?php echo form_close() ?>