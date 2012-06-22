<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 08/05/12
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Usuarios_Autorizacao_PermissaoUsuario implements WeLearn_Usuarios_Autorizacao_Permissao
{
    /**
     *
     * @var array
     */
    protected $_permissoes;

    /**
     * @param null|WeLearn_Usuarios_Autorizacao_Papel $papel
     */
    public function __construct()
    {
        $this->_aplicarPermissoes();
    }

    /**
     * @return array
     */
    public function getPermissoes()
    {
        return $this->_permissoes;
    }

    /**
     * @param string $funcao
     * @return bool
     */
    public function isAutorizado($funcao)
    {
        if ( isset( $this->_permissoes[ $funcao ] ) ) {
            return $this->_permissoes[ $funcao ];
        }

        return false;
    }

    /**
     * @return void
     */
    protected function _aplicarPermissoes()
    {
        $this->_permissoes = array(
            'curso/index' => true,
            'curso/exibir' => true,
            'curso/buscar' => true,
            'curso/mais_resultados' => true,
            'curso/meus_cursos_criador' => true,
            'curso/meus_cursos_gerenciador' => true,
            'curso/meus_cursos_aluno' => true,
            'curso/meus_cursos_em_espera' => true,
            'curso/meus_convites' => true,
            'curso/meus_certificados' => true,
            'curso/inscrever' => true,
            'curso/criar' => true,
            'curso/salvar' => true,
            'aluno/index' => true,
            'aluno/listar' => true,
            'aluno/mais_alunos' => true,
            'enquete/index' => true,
            'enquete/listar' => true,
            'enquete/proxima_pagina' => true,
            'enquete/exibir' => true,
            'enquete/exibir_resultados' => true,
            'enquete/votar' => true,
            'forum/index' => true,
            'forum/listar' => true,
            'forum/proxima_pagina' => true,
            'forum/listar_categorias' => true,
            'forum/proxima_pagina_categorias' => true,
            'post/index' => true,
            'post/listar' => true,
            'post/proxima_pagina' => true,
            'gerenciador/index' => true,
            'gerenciador/listar' => true,
            'gerenciador/mais_gerenciadores' => true,
            'gerenciador/aceitar_convite' => true,
            'gerenciador/recusar_convite' => true,
            'aluno/cancelar_requisicao' => true,
            'certificado/exibir_aluno' => true
        );
    }
}
