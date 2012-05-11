<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 08/05/12
 * Time: 19:52
 * To change this template use File | Settings | File Templates.
 */
interface WeLearn_Usuarios_Autorizacao_Papel
{
    /**
     * @abstract
     * @return int
     */
    public function getNivelAcesso();
}
