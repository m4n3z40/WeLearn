<div id="certificado-listar-content">
    <header>
        <hgroup>
            <h1>Gerenciamento de Certificados</h1>
            <h3>Aqui é onde você gerenciará os certificados que seus alunos
                receberão ao finalizar o curso</h3>
        </hgroup>
        <p>O curso deve ter ao menos um certificado ativo. <br>
           Até <em><?php echo $maxCertificados ?></em> certificados podem ser
           enviados, mas somente o que estiver ativo no momento
           que o aluno concluir o curso, será o que ele receberá.</p>
        <p>Deseja enviar um certificado para curso?
            <?php echo anchor('/curso/certificado/criar/' . $idCurso, 'Clique Aqui!') ?></p>
    </header>
    <div>
        <?php if ($haCertificados): ?>

        <?php else: ?>
        <h4>Nenhum certificado foi enviado a este curso até o momento.
            <?php echo anchor('/curso/certificado/criar/' . $idCurso,
                'Clique aqui para enviar o primeiro!') ?></h4>
        <?php endif; ?>
    </div>
    <footer>
    </footer>
</div>