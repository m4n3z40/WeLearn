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

            )
        );
    }
}
