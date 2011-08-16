<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
| -------------------------------------------------------------------
|  Cassandra Servers
| -------------------------------------------------------------------
|
| The array list of the cassandra servers IPs that will be available for connection in the Phpcassa library.
*/
$config['cassandra_servers'] = array(
                                  'localhost:9160'
                              );

/**
| -------------------------------------------------------------------
|  keyspace
| -------------------------------------------------------------------
|
| The default keyspace that will be connected when the connection pool initializes and will be used for
| looking up the requested column families.
*/
$config['keyspace'] = 'welearn';

/**
| -------------------------------------------------------------------
|  Default Read Consistency Level
| -------------------------------------------------------------------
|
| The default consistency level for reading operations in cassandra server.
|
| ONE = 1;
| QUORUM = 2;
| LOCAL_QUORUM = 3;
| EACH_QUORUM = 4;
| ALL = 5;
| TWO = 7;
| THREE = 8;
*/
$config['default_read_cl'] = 1;

/**
| -------------------------------------------------------------------
|  Default Write Consistency Level
| -------------------------------------------------------------------
|
| The default consistency level writing operations in cassandra server.
|
| ONE = 1;
| QUORUM = 2;
| LOCAL_QUORUM = 3;
| EACH_QUORUM = 4;
| ALL = 5;
| ANY = 6;
| TWO = 7;
| THREE = 8;
*/
$config['default_write_cl'] = 1;

/**
| -------------------------------------------------------------------
|  AutoPack Column Names
| -------------------------------------------------------------------
|
| Whether or not to automatically convert column names
| to and from their binary representation in Cassandra
*/
$config['autopack_column_names'] = true;

/**
| -------------------------------------------------------------------
|  AutoPack Column Values
| -------------------------------------------------------------------
|
| Whether or not to automatically convert column values
| to and from their binary representation in Cassandra
*/
$config['autopack_column_values'] = true;

/* End of file phpcassa.php */
/* Location: ./application/config/phpcassa.php */