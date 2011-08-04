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
    private $_connectionPool;
    private $_ci;

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
     * @return ColumnFamily|null
     */
    public function getColumnFamily($cf_name = '')
    {
        $cf_name = (string)$cf_name;
        $cf = NULL;
        
        if( !($cf_name === '') ) {
            try {
                $cf = new ColumnFamily($this->_connectionPool, $cf_name);
                log_message('debug','CollumnFamily '.$cf_name.' loaded successfully');
            } catch( Exception $e ) {
                log_message('error', 'Erro ao carregar a collumn family "' . $cf_name . '" :' . $e->getMessage());
            }
        }
        
        return $cf;
    }

    /**
     * Return the ConnectionPool object that represents the connection with the cassandra server
     *
     * @return ConnectionPool
     */
    public function getConnection()
    {
        return $this->_connectionPool;
    }
}

/* End of file Phpcassa.php */
/* Location: ./application/libraries/Phpcassa.php */