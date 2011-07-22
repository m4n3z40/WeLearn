<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 03:06
 *
 * Description:
 *
 */

/**
 *
 */
interface WeLearn_DTO_IDTO
{

    /**
     * Retorna se o Data Transfer Object já foi persistido ou não no Banco de Dados.
     *
     * @abstract
     * @return boolean
     */
    public function isPersistido();

    /**
     * Seta se o objeto é ou não persistido no Banco de Dados.
     *
     * @param $persistido boolean O indicador se o objeto é persistido ou não.
     * @return void
     */
    public function setPersistido($persistido);
}
