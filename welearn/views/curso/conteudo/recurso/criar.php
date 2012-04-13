<div id="recurso-criar-content">
    <header>
        <hgroup>
            <h1>Criar um Recurso Extra</h1>
            <h3>Preencha abaixo os campos com os dados
                necessários para criação do recurso.</h3>
        </hgroup>
        <p>
            Recursos extras servem para complementar seus curso e suas aulas.<br>
            Faça upload de arquivos de vários tipos e eles estarão vinculados ao curso
            ou a uma aula específica. <br>
            Assim, esses recursos ficarão disponíveis para download de acordo com seu
            tipo:
         </p>
        <ul>
            <li>Recursos Gerais: Disponíveis para qualquer aluno do curso.</li>
            <li>Recursos Restritos: Disponíveis somente para os alunos que já alcançaram
                a aula a que o recurso pertence.</li>
        </ul>
        <p>
            Não queria estar aqui?
            <?php echo anchor('/curso/conteudo/recurso/' . $idCurso,
                              'Volte para a index de recursos.') ?>
        </p>
    </header>
    <div>
        <?php echo $form ?>
    </div>
</div>