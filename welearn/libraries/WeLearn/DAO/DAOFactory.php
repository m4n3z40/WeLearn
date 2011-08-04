<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 02:53
 *
 * Description:
 *
 */

class WeLearn_DAO_DAOFactory extends WeLearn_DAO_AbstractDAOFactory
{
    public static function registerDAOPath($path)
    {
        if (WeLearn_Base_AutoLoader::hasInitiated())
            return WeLearn_Base_Loader::getInstance()->addIncludePath($path);
        else
            throw new WeLearn_Base_LoaderNaoIniciadoException();
    }

    public static function unregisterDAOPath($path)
    {
        if (WeLearn_Base_AutoLoader::hasInitiated())
            return WeLearn_Base_Loader::getInstance()->removeIncludePath($path);
        else
            throw new WeLearn_Base_LoaderNaoIniciadoException();
    }
}