<div id="avaliacao-questao-criar-content">
    <header>
        <hgroup>
            <h1>Adicionar questão na Avaliação <em>"<?php echo $avaliacao->nome ?>"</em></h1>
            <h3>Preencha abaixo os dados necessários para adição da questão na
                avaliação do módulo <?php echo $avaliacao->modulo->nome ?> </h3>
            <p>Não se preocupe com a ordem das alternativas, elas serão "embaralhadas"
               ao serem exibidas para o aluno.</p>
        </hgroup>
    </header>
    <div>
        <?php echo $form; ?>
    </div>
</div>