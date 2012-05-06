<div id="certificado-criar-content">
    <header>
        <hrgroup>
            <h1>Enviar um Certificado para o Curso</h1>
            <h3>Preencha os dados necessários abaixo para o envio do certificado</h3>
        </hrgroup>
        <p>Não queria estar aqui?
            <?php echo anchor('/curso/certificado/' . $idCurso,
                'Clique aqui para voltar ao gerenciamento de certificados!') ?></p>
    </header>
    <div>
    <?php if ( $atingiuMaximo ): ?>
        <h3>O número máximo de Certificados permitidos foi atingido!
            <?php echo anchor('/curso/certificado/' . $idCurso,
                'Clique aqui para voltar!') ?></h3>
    <?php else: ?>
        <?php echo $form; ?>
    <?php endif; ?>
    </div>
    <footer>
    </footer>
</div>