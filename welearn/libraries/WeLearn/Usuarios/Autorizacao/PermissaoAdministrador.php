<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 08/05/12
 * Time: 20:29
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Usuarios_Autorizacao_PermissaoAdministrador extends WeLearn_Usuarios_Autorizacao_PermissaoGerenciadorPrincipal
{
    public function __construct()
    {
        parent::__construct();

        $this->_aplicarPermissoesAdministrador();
    }

    protected function _aplicarPermissoesAdministrador()
    {
        $this->_permissoes = array_merge(
            $this->_permissoes,
            array(

            )
        );
    }

    public function isAutorizado($funcao)
    {
        return true;
    }
}
