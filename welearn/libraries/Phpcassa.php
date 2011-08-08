<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Allan Marques.
 * Date: 20/06/11
 * Time: 11:51
 *
 * Description:
 * PHPCassa Loader Class for Code Igniter.
 */
class WL_Phpcassa {
    /**
     * @var ConnectionPool
     */
    private $_connectionPool;

    /**
     * @var CI_Controller
     */
    private $_ci;

    /**
     * @var boolean
     */
    private $_autoPackColumnNames;

    /**
     * @var boolean
     */
    private $_autoPackColumnValues;

    /**
     * @var int
     */
    private $_readConsistencyLevel;

    /**
     * @var int
     */
    private $_writeConsistencyLevel;

    /**
     * Loads the PHPCassa needed classes and initializes the connection pool.
     * The list of servers that are available in the connection pool can be configured in the phpcassa.php file
     * on the config folder of the application.
     */
    public function __construct()
    {
        require_once 'PHPCassa/connection'.EXT;
        require_once 'PHPCassa/sysmanager'.EXT;
        require_once 'PHPCassa/uuid'.EXT;
        require_once 'PHPCassa/columnfamily'.EXT;

        $this->_ci =& get_instance();
        $this->_connect();
    }

    /*
     * Initializes the connection with the given connection pool servers
     */
    private function _connect()
    {
        $keyspace = $this->_ci->config->item('keyspace');
        $servers = $this->_ci->config->item('cassandra_servers');
        $this->_readConsistencyLevel = $this->_ci->config->item('default_read_cl');
        $this->_writeConsistencyLevel = $this->_ci->config->item('default_write_cl');
        $this->_autoPackColumnNames = $this->_ci->config->item('autopack_column_names');
        $this->_autoPackColumnValues = $this->_ci->config->item('autopack_column_values');

        try {
            $this->_connectionPool = new ConnectionPool($keyspace, $servers);

            $msg_success = 'Connected to the given connection pool -> hosts:('
                           .implode(', ',$servers).') and keyspace:'.$keyspace;
            log_message('debug', $msg_success);
        } catch( Exception $e ) {
            log_message('error', $e->getMessage());
        }
    }

    /**
     * looks for the requested column family in the keyspace and return a ColumnFamily object
     * if it has been found, or else returns null.
     *
     * @param string $cf_name the name of the column family to look for in the keyspace.
     * @param array|null $options the options that will be used for this column family only.
     *                            the options are: 'autopack_names', 'autopack_values', 'read_cl', 'write_cl'.
     *                            if none or some of the options aren't set, will fallback to default.
     * @return ColumnFamily|null
     */
    public function getColumnFamily($cf_name = '', array $options = null)
    {
        $cf_name = (string)$cf_name;
        $cf = NULL;
        
        if( !($cf_name === '') ) {
            //are there options?
            $with_options =  !empty($options);

            //autopack column names? if no options fallback to default.
            $autopack_names = $with_options && isset($options['autopack_names'])
                              ? $options['autopack_names']
                              : $this->_autoPackColumnNames;

            //autopack column values? if no options fallback to default.
            $autopack_values = $with_options && isset($options['autopack_values'])
                               ? $options['autopack_values']
                               : $this->_autoPackColumnValues;

            //what read consistency level? if no options fallback to default.
            $read_cl = $with_options && isset($options['read_cl'])
                       ? $options['read_cl']
                       : $this->_readConsistencyLevel;

            //what write consistency level? if no options fallback to default.
            $write_cl = $with_options && isset($options['write_cl'])
                        ? $options['write_cl']
                        : $this->_writeConsistencyLevel;

            //now lets try to connect the column family with these options.
            try {
                $cf = new ColumnFamily(
                    $this->_connectionPool,
                    $cf_name,
                    $autopack_names,
                    $autopack_values,
                    $read_cl,
                    $write_cl
                );
                log_message('debug','CollumnFamily '.$cf_name.' loaded successfully');
            } catch( Exception $e ) {
                log_message('error', 'Erro ao carregar a collumn family "' . $cf_name . '" :' . $e->getMessage());
            }
        }
        
        return $cf;
    }

    /**
     * Returns the instance of this class initialized by de CI instance
     *
     * @static
     * @return WL_Phpcassa|null
     */
    public static function &getInstance()
    {
        $ci =& get_instance();

        return isset($ci->phpcassa) ? $ci->phpcassa : null;
    }

    /**
     * @param boolean $autoPackColumnNames
     */
    public function setAutoPackColumnNames($autoPackColumnNames)
    {
        $this->_autoPackColumnNames = (boolean)$autoPackColumnNames;
    }

    /**
     * @return boolean
     */
    public function getAutoPackColumnNames()
    {
        return $this->_autoPackColumnNames;
    }

    /**
     * @param boolean $autoPackColumnValues
     */
    public function setAutoPackColumnValues($autoPackColumnValues)
    {
        $this->_autoPackColumnValues = $autoPackColumnValues;
    }

    /**
     * @return boolean
     */
    public function getAutoPackColumnValues()
    {
        return $this->_autoPackColumnValues;
    }

    /**
     * @param \ConnectionPool $connectionPool
     */
    public function setConnectionPool($connectionPool)
    {
        $this->_connectionPool = $connectionPool;
    }

    /**
     * @return \ConnectionPool
     */
    public function getConnectionPool()
    {
        return $this->_connectionPool;
    }

    /**
     * @param int $readConsistencyLevel
     */
    public function setReadConsistencyLevel($readConsistencyLevel)
    {
        $this->_readConsistencyLevel = $readConsistencyLevel;
    }

    /**
     * @return int
     */
    public function getReadConsistencyLevel()
    {
        return $this->_readConsistencyLevel;
    }

    /**
     * @param int $writeConsistencyLevel
     */
    public function setWriteConsistencyLevel($writeConsistencyLevel)
    {
        $this->_writeConsistencyLevel = $writeConsistencyLevel;
    }

    /**
     * @return int
     */
    public function getWriteConsistencyLevel()
    {
        return $this->_writeConsistencyLevel;
    }
}

/* End of file Phpcassa.php */
/* Location: ./application/libraries/Phpcassa.php */