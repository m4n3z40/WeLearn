<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 08/05/12
 * Time: 20:27
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Usuarios_Autorizacao_PermissaoAluno extends WeLearn_Usuarios_Autorizacao_PermissaoUsuario
{
    public function __construct()
    {
        parent::__construct();

        $this->_aplicarPermissoesAluno();
    }

    protected function _aplicarPermissoesAluno()
    {
        $this->_permissoes = array_merge(
            $this->_permissoes,
            array(
                'curso/sair' => true,
                'review/index' => true,
                'review/listar' => true,
                'review/recuperar_proxima_pagina' => true,
                'review/enviar' => true,
                'review/salvar' => true,
                'enquete/criar' => true,
                'enquete/salvar' => true,
                'forum/criar' => true,
                'forum/salvar' => true,
                'exibicao/index' => true,
                'exibicao/exibir' => true,
                'exibicao/salvar_frequencia' => true,
                'exibicao/ir_para' => true,
                'exibicao/aula_anterior' => true,
                'exibicao/inicio_aula' => true,
                'exibicao/acessar_anterior' => true,
                'exibicao/acessar_proximo' => true,
                'exibicao/salvar_anotacao' => true,
                'aplicacao_avaliacao/index' => true,
                'aplicacao_avaliacao/aplicar' => true,
                'aplicacao_avaliacao/finalizar' => true,
                'aplicacao_avaliacao/exibir_resultados' => true,
                'aula/recuperar_lista' => true,
                'pagina/recuperar_lista' => true,
                'recurso/recuperar_recursos_aluno' => true,
                'comentario/recuperar_lista' => true,
                'comentario/salvar' => true
            )
        );
    }
}
