<?php
/**
 * Created by Allan Marques
 * Date: 23/07/11
 * Time: 01:16
 *
 * Description:
 *
 */

require_once LIBSPATH . 'WeLearn/Base/ClasseNaoEncontradaException.php';
require_once LIBSPATH . 'WeLearn/Base/IncludePathInexistenteException.php';
require_once LIBSPATH . 'WeLearn/Base/ParametroInvalidoException.php';

/**
 * @throws WeLearn_Base_ClasseNaoEncontradaException|WeLearn_Base_IncludePathInexistenteException|WeLearn_Base_ParametroInvalidoException
 */
class WeLearn_Base_Loader
{
    /**
     * @var null|WeLearn_Base_Loader
     */
    private static $_instance = null;

    /**
     * @var string
     */
    private $_namespaceSeparator = '_';

    /**
     * @var string
     */
    private $_fileSuffix = '.php';

    /**
     * @var array
     */
    private $_includePaths = array();

    /**
     * Initializes the Loader and it's include paths.
     * Only accessible by this class.
     */
    private function __construct()
    {
        $this->_includePaths = explode(PATH_SEPARATOR, get_include_path());
    }

    /**
     * Cloning is not allowed.
     *
     * @return void
     */
    private function __clone()
    {

    }

    /**
     * Unserialization is not allowed.
     *
     * @return void
     */
    private function __wakeup()
    {

    }

    /**
     * Initializes or get the initialized Loader. (Singleton)
     *
     * @static
     * @return null|WeLearn_Base_Loader
     */
    public static function &getInstance()
    {
        if (is_null(self::$_instance)) {
            $className = __CLASS__;
            self::$_instance = new $className;
        }

        return self::$_instance;
    }

    /**
     * Check to see if the given file param exists in any of de registered include paths.
     *
     * @param string $file the path and/or name of the file that is to be checked
     * @return bool
     */
    public function fileExists($file)
    {
        if (substr($file, 0, 1) === DIRECTORY_SEPARATOR) {
            if (file_exists($file))
                return true;

            $file = ltrim($file, DIRECTORY_SEPARATOR);
        }

        foreach ($this->_includePaths as $aPath) {
            $fullFilePath = $aPath . DIRECTORY_SEPARATOR . $file;
            if (file_exists($fullFilePath))
                return true;
        }

        return false;
    }

    /**
     * Loads a file of a class that follows the Zend code style class naming.
     *
     * @throws WeLearn_Base_ClasseNaoEncontradaException
     * @param string|array $className the string containing the name of the class e.g. 'Vendor_Package_Class'
     * @return bool
     */
    public function loadClass($className)
    {
        $className = (string)$className;
        $pathAndFile = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $className)
                       . $this->_fileSuffix;

        if ($this->fileExists($pathAndFile)) {
            require_once $pathAndFile;

            if (class_exists($className) || interface_exists($className)) {
                return true;
            } else {
                throw new WeLearn_Base_ClasseNaoEncontradaException($className);
            }
        }
        return false;
    }

    /**
     * Adds the path or list of paths in the param to the include_path environmental variable.
     * Files in this paths will be automatically looked up for when using the 'include' and 'require'
     * functions, and in the loadClass and fileExists methods of this class as well.
     *
     * @throws WeLearn_Base_IncludePathInexistenteException|WeLearn_Base_ParametroInvalidoException
     * @param string|array $path a string or an array of strings, containing the full path
     * @return
     */
    public function addIncludePath($path)
    {
        $atLeastOneAdded = false;

        if (is_array($path)) {
            foreach ($path as $aPath) {
                $this->addIncludePath((string)$aPath);
            }
        } elseif (is_string($path)) {
            $path = rtrim($path, PATH_SEPARATOR);

            if (is_dir($path)) {
                if (!in_array($path, $this->_includePaths)) {
                    $this->_includePaths[] = $path;
                }

                $atLeastOneAdded = true;
            } else {
                throw new WeLearn_Base_IncludePathInexistenteException($path);
            }
        }

        if ($atLeastOneAdded) {
            $this->_registerIncludePaths();
            return;
        }

        throw new WeLearn_Base_ParametroInvalidoException();
    }

    /**
     * Removes the path passed in the param iF it has already been registered.
     *
     * @throws WeLearn_Base_IncludePathInexistenteException|WeLearn_Base_ParametroInvalidoException
     * @param int|string $pathOrKey The string containing the path
     *        or the number representing the key of the includepath
     * @return
     */
    public function removeIncludePath($pathOrKey)
    {
        if (is_int($pathOrKey)) {
            if (isset($this->_includePaths[$pathOrKey])) {
                unset($this->_includePaths[$pathOrKey]);
                $this->_registerIncludePaths();
                return;
            } else {
                throw new WeLearn_Base_IncludePathInexistenteException($pathOrKey);
            }
        } elseif (is_string($pathOrKey)) {
            if (in_array($pathOrKey, $this->_includePaths)) {
                $key = (int)array_search($pathOrKey, $this->_includePaths);
                unset($this->_includePaths[$key]);
                $this->_registerIncludePaths();
                return;
            } else {
                throw new WeLearn_Base_IncludePathInexistenteException($pathOrKey);
            }
        }

        throw new WeLearn_Base_ParametroInvalidoException();
    }

    /**
     * Register the added include paths to the php environment
     *
     * @return void
     */
    private function _registerIncludePaths()
    {
        set_include_path(implode(PATH_SEPARATOR, $this->_includePaths));
    }

    /**
     * Returns the array of the include paths added
     *
     * @return array
     */
    public function getIncludePaths()
    {
        return $this->_includePaths;
    }

    /**
     * Prints a humanized form of the include paths list
     *
     * @return void
     */
    public function showIncludePaths()
    {
        echo '<pre>' . print_r($this->_includePaths) . '</pre>';
    }

    /**
     * Sets the file suffix that will be used when loading the class.
     * e.g: '.class.php'
     * default: '.php'
     *
     * @param string $fileSuffix
     * @return void
     */
    public function setFileSuffix($fileSuffix)
    {
        $this->_fileSuffix = (string)$fileSuffix;
    }

    /*
     * Returns the file suffix being used by the loader
     */
    public function getFileSuffix()
    {
        return $this->_fileSuffix;
    }

    /**
     * Sets the separator of the class namespaces
     * e.g: '\' means -> 'Vendor\Package\Class'
     * default: '_' meaning -> 'Vendor_Package_Class'
     *
     * @param string $namespaceSeparator
     * @return void
     */
    public function setNamespaceSeparator($namespaceSeparator)
    {
        $this->_namespaceSeparator = (string)$namespaceSeparator;
    }

    /**
     * Returns the namespace separator being used by the loader
     *
     * @return string
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }
}