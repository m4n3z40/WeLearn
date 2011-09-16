<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 02:47
 * 
 * Description:
 *
 */

interface WeLearn_DAO_IDAOFactory 
{
    /**
     * @static
     * @abstract
     * @param string $nomeDao
     * @param array|null $opcoes
     * @return IDAO|null
     */
    public static function create($nomeDao, array $opcoes = null);
}
