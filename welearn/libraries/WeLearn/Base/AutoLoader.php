<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 21:14
 * 
 * Description:
 *
 */

require_once LIBSPATH.'WeLearn/Base/Loader.php';
require_once LIBSPATH.'WeLearn/Base/LoaderNaoIniciadoException.php';

/**
 * @throws WeLearn_Base_LoaderNaoIniciadoException
 */
abstract class WeLearn_Base_AutoLoader
{
    /**
     * @var null|WeLearn_Base_Loader
     */
    private static $_loader = null;

    /**
     * Register given function as __autoload() implementation
     *
     * @static
     * @param callback $autoLoaderFunction The autoload function being registered.
     * @return bool
     */
    public static function registerAutoLoader($autoLoaderFunction)
    {
        return spl_autoload_register($autoLoaderFunction);
    }

    /**
     * Unregister given function as __autoload() implementation
     *
     * @static
     * @param callback $autoLoaderFunction The autoload function being unregistered.
     * @return bool
     */
    public static function unregisterAutoLoader($autoLoaderFunction)
    {
        return spl_autoload_unregister($autoLoaderFunction);
    }

    /**
     * Return all registered __autoload() functions
     *
     * @static
     * @return array
     */
    public static function getAutoloaderFunctions()
    {
        return spl_autoload_functions();
    }

    /**
     * Inits the autoloading.
     *
     * @static
     * @param string|array $includePath (optional) string or array of strings of include paths to add to the loader
     * @return bool
     */
    public static function init($includePath = null)
    {
        if (is_null($includePath)) {
            $includePath = realpath(
                dirname(__FILE__)
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR
            );
        }
        self::$_loader = WeLearn_Base_Loader::getInstance();
        self::$_loader->addIncludePath($includePath);
        return self::registerAutoLoader(__CLASS__.'::Autoload');
    }

    /**
     * This class method is called every time a class needs to be autoloaded.
     *
     * @static
     * @throws WeLearn_Base_LoaderNaoIniciadoException
     * @param string $className The name of the class needed to be loaded
     * @return bool
     */
    public static function Autoload($className)
    {
        if(!is_null(self::$_loader))
            return self::$_loader->loadClass($className);
        else
            throw new WeLearn_Base_LoaderNaoIniciadoException();
    }

    /**
     * Returns the loader.
     *
     * @static
     * @return null|WeLearn_Base_Loader
     */
    public static function getLoader()
    {
        return self::$_loader;
    }
}