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

            )
        );
    }
}
