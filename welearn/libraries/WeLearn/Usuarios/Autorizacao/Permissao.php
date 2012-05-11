<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 08/05/12
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */
interface WeLearn_Usuarios_Autorizacao_Permissao
{
    /**
     * @abstract
     * @return array
     */
    public function getPermissoes();

    /**
     * @abstract
     * @param string $funcao
     * @return bool
     */
    public function isAutorizado($funcao);
}
