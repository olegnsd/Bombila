<?php 

include 'common.php';

$memcache = new Memcache;

$memcache->connect( $MEMCACHED_HOST , $MEMCACHED_PORT );
if( !$memcache ) {
  error_log( "Unable to connect to Memcached" );
  exit(0);
}

$orders = $memcache->get('orders');
$bombilas = $memcache->get('bombilas_locations');

$result = array();

$result['bombilas_locations'] = $bombilas;
$result['orders'] = $orders;

header("Content-Type: application/json; charset=UTF-8");

echo json_encode( $result );

?>