<div id="user-bar">
    <h3>WeLearn</h3>
    <a href="#" class="logoutButton button">Sair</a>
    <section id="user-info"><?php echo $nomeUsuario ?></section>
    <?php echo form_open('usuario/busca/buscar')?>
    <input type="text" name="txt-search" id="txt-search"/>
    <?php echo form_submit('enviar','procurar', 'id="btn-submit-search"')?>
    <?php echo form_close();?>
</div>
