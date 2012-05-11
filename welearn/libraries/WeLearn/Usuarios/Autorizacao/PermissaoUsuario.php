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

        );
    }
}
