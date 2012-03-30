<div id="aula-index-content">
    <header>
        <hgroup>
            <h1>Aulas do Curso</h1>
            <h3>Aqui é onde você encontra/gerencia as aulas existentes no curso.</h3>
        </hgroup>
        <p>
            Escolha um módulo abaixo exibir as aulas respectivas a ele.
        </p>
    </header>
    <div>
        <h4>Escolha o módulo desejado: </h4>
        <?php echo $selectModulos ?>
        <hr>
        <?php echo anchor('/curso/conteudo/modulo/' . $idCurso, 'Gerenciar Módulos') ?>
    </div>
</div>