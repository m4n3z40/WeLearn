<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 08/05/12
 * Time: 21:03
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Usuarios_Autorizacao_ControleAcesso
{
    protected $_permissoes;

    /**
     * @param array|null $permissoesCustomizadas
     */
    public function __construct($permissoesCustomizadas = null)
    {
        if ( is_array( $permissoesCustomizadas ) ) {
            $this->_permissoes = array();
        } else {
            $this->_permissoes = $this->_permissoesPadrao();
        }
    }

    /**
     * @param WeLearn_Usuarios_Autorizacao_Papel $papel
     * @param WeLearn_Usuarios_Autorizacao_Permissao $permissao
     */
    public function adicionarRegraPermissao(WeLearn_Usuarios_Autorizacao_Papel $papel,
                                            WeLearn_Usuarios_Autorizacao_Permissao $permissao)
    {
        $this->_permissoes[ $papel->getNivelAcesso() ] = $permissao;
    }

    /**
     * @param WeLearn_Usuarios_Autorizacao_Papel $papel
     */
    public function removerRegraPermissao(WeLearn_Usuarios_Autorizacao_Papel $papel)
    {
        if ( $this->_papelValido( $papel ) ) {

            unset( $this->_permissoes[ $papel->getNivelAcesso() ] );

        }
    }

    /**
     * @param WeLearn_Usuarios_Autorizacao_Papel $papel
     * @param $funcao
     * @return bool
     */
    public function isAutorizado(WeLearn_Usuarios_Autorizacao_Papel $papel, $funcao)
    {
        if ( $this->_papelValido( $papel ) ) {

            return $this->_permissoes[ $papel->getNivelAcesso() ]->isAutorizado( $funcao );

        }

        return false;
    }

    /**
     * @param WeLearn_Usuarios_Autorizacao_Papel $papel
     * @return null|array
     */
    public function getPermissoes(WeLearn_Usuarios_Autorizacao_Papel $papel)
    {
        if ( $this->_papelValido( $papel ) ) {

            return $this->_permissoes[ $papel->getNivelAcesso() ]->getPermissoes();

        }

        return null;
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuarioParaComparar
     * @param WeLearn_Usuarios_Usuario $oCriador
     * @return bool
     */
    public function isAutor(WeLearn_Usuarios_Usuario $usuarioParaComparar,
                            WeLearn_Usuarios_Usuario $oCriador)
    {
        return $usuarioParaComparar->getId() === $oCriador->getId();
    }

    /**
     * @param WeLearn_Usuarios_Autorizacao_Papel $papel
     * @return bool
     */
    protected function _papelValido(WeLearn_Usuarios_Autorizacao_Papel $papel)
    {
        $nivel = $papel->getNivelAcesso();

        return

            isset( $this->_permissoes[ $nivel ] ) &&

            $this->_permissoes[ $nivel ] instanceof WeLearn_Usuarios_Autorizacao_Permissao;
    }

    /**
     * @return array
     */
    protected function _permissoesPadrao()
    {
        return array(
            WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO                  => new WeLearn_Usuarios_Autorizacao_PermissaoUsuario(),
            WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO                    => new WeLearn_Usuarios_Autorizacao_PermissaoAluno(),
            WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR     => new WeLearn_Usuarios_Autorizacao_PermissaoGerenciadorAuxiliar(),
            WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_PRINCIPAL    => new WeLearn_Usuarios_Autorizacao_PermissaoGerenciadorPrincipal(),
            WeLearn_Usuarios_Autorizacao_NivelAcesso::ADMINISTRADOR_DO_SERVICO => new WeLearn_Usuarios_Autorizacao_PermissaoAdministrador()
        );
    }
}
