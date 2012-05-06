<div id="certificado-alterar-content">
    <header>
        <hrgroup>
            <h1>Alterar Certificado</h1>
            <h3>Preencha os dados necessários abaixo para alteração do certificado</h3>
        </hrgroup>
        <p>Não queria estar aqui?
            <?php echo anchor('/curso/certificado/' . $idCurso,
                'Clique aqui para voltar ao gerenciamento de certificados!') ?></p>
    </header>
    <div>
        <?php echo $form; ?>
    </div>
    <footer>
    </footer>
</div>