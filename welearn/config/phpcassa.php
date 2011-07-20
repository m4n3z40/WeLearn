<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
| -------------------------------------------------------------------
|  Cassandra Servers
| -------------------------------------------------------------------
|
| The array list of the cassandra servers IPs that will be available for connection in the Phpcassa library.
*/
$config['cassandra_servers'] = array(
                                  '10.0.0.1:9160',
                                  '10.0.0.2:9160',
                                  '10.0.0.3:9160',
                                  '10.0.0.4:9160',
                              );

/**
| -------------------------------------------------------------------
|  keyspace
| -------------------------------------------------------------------
|
| The default keyspace that will be connected when the connection pool initializes and will be used for
| looking up the requested column families.
*/
$config['keyspace']          = 'welearn';

/* End of file phpcassa.php */
/* Location: ./application/config/phpcassa.php */