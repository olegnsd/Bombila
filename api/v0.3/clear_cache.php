<?php

include 'common.php';

$memcache = new Memcache;

$memcache->connect( $MEMCACHED_HOST , $MEMCACHED_PORT );
if( !$memcache ) {
  error_log( "Unable to connect to Memcached" );
  exit(0);
}

$memcache->delete('orders');
$memcache->delete('bombilas_locations');

echo 'memcache cleared';

?>