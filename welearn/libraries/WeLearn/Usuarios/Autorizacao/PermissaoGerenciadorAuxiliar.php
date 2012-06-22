<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 08/05/12
 * Time: 20:28
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Usuarios_Autorizacao_PermissaoGerenciadorAuxiliar extends WeLearn_Usuarios_Autorizacao_PermissaoAluno
{
    public function __construct()
    {
        parent::__construct();

        $this->_aplicarPermissoesGerenciadorAuxiliar();
    }

    protected function _aplicarPermissoesGerenciadorAuxiliar()
    {
        $this->_permissoes = array_merge(
            $this->_permissoes,
            array(
                'exibicao/index' => false,
                'exibicao/exibir' => false,
                'exibicao/salvar_frequencia' => false,
                'exibicao/ir_para' => false,
                'exibicao/aula_anterior' => false,
                'exibicao/inicio_aula' => false,
                'exibicao/acessar_anterior' => false,
                'exibicao/acessar_proximo' => false,
                'exibicao/salvar_anotacao' => false,
                'aplicacao_avaliacao/index' => false,
                'aplicacao_avaliacao/aplicar' => false,
                'aplicacao_avaliacao/finalizar' => false,
                'aplicacao_avaliacao/exibir_resultados' => false,
                'aluno/desvincular' => true,
                'aluno/requisicoes' => true,
                'aluno/mais_requisicoes' => true,
                'aluno/aceitar_requisicao' => true,
                'aluno/recusar_requisicao' => true,
                'enquete/alterar_status' => true,
                'enquete/alterar_situacao' => true,
                'enquete/remover' => true,
                'categoria/index' => true,
                'categoria/listar' => true,
                'categoria/proxima_pagina' => true,
                'categoria/criar' => true,
                'categoria/alterar' => true,
                'categoria/remover' => true,
                'categoria/salvar' => true,
                'forum/alterar_status' => true,
                'forum/alterar' => true,
                'forum/remover' => true,
                'post/remover' => true,
                'certificado/index' => true,
                'certificado/listar' => true,
                'certificado/criar' => true,
                'certificado/alterar' => true,
                'certificado/exibir' => true,
                'certificado/remover' => true,
                'certificado/salvar_upload_temporario' => true,
                'certificado/remover_upload_temporario' => true,
                'certificado/salvar' => true,
                'review/enviar' => false,
                'review/salvar' => false,
                'review/responder' => true,
                'review/salvar_resposta' => true,
                'conteudo/index' => true,
                'modulo/index' => true,
                'modulo/listar' => true,
                'modulo/salvar_posicoes' => true,
                'modulo/remover' => true,
                'modulo/criar' => true,
                'modulo/alterar' => true,
                'modulo/salvar' => true,
                'aula/index' => true,
                'aula/listar' => true,
                'aula/criar' => true,
                'aula/alterar' => true,
                'aula/salvar_posicoes' => true,
                'aula/remover' => true,
                'aula/salvar' => true,
                'pagina/index' => true,
                'pagina/exibir' => true,
                'pagina/listar' => true,
                'pagina/criar' => true,
                'pagina/alterar' => true,
                'pagina/salvar_posicoes' => true,
                'pagina/salvar' => true,
                'pagina/remover' => true,
                'avaliacao/index' => true,
                'avaliacao/exibir' => true,
                'avaliacao/criar' => true,
                'avaliacao/alterar' => true,
                'avaliacao/remover' => true,
                'avaliacao/salvar' => true,
                'avaliacao/exibir_questao' => true,
                'avaliacao/adicionar_questao' => true,
                'avaliacao/alterar_questao' => true,
                'avaliacao/remover_questao' => true,
                'avaliacao/salvar_questao' => true,
                'avaliacao/salvar_qtd_questoes_exibir' => true,
                'recurso/index' => true,
                'recurso/geral' => true,
                'recurso/restrito' => true,
                'recurso/recuperar_lista_restrita' => true,
                'recurso/recuperar_proxima_pagina' => true,
                'recurso/criar' => true,
                'recurso/alterar' => true,
                'recurso/remover' => true,
                'recurso/salvar' => true,
                'recurso/salvar_upload_temporario' => true,
                'recurso/remover_upload' => true,
                'comentario/index' => true,
                'comentario/remover' => true,
            )
        );
    }
}
