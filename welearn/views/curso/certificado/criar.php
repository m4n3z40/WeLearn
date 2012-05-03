<div>
    <header>
        <hrgroup>
            <h1>Enviar um Certificado para o Curso</h1>
            <h3>Preencha os dados necessários abaixo para o envio do curso</h3>
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