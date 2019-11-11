<?php 

include 'common.php';


echo 'hello<br>';

initMemcached3();
doJob();

function doJob() {
  global $memcache;	

	$orders = $memcache->get('orders');

	echo '!!! doJob<br>';
	if( $orders == NULL ) {
		echo '$orders: NULL<br>';
  	print_r( $orders );		
	}
	else {
		echo '$orders: <br>';
  	print_r( $orders );
  }

	$system_time_secs = time();
	$accept_time_secs = 60;

	foreach( $orders as &$order ) {

		if( $order['state'] == 'new' ) {

			$bombila = findNearestBombila( $order );
			error_log("!!! nearest".print_r($bombila,true));
		  if( $bombila != NULL ) {
		    $order['bombila'] = $bombila['phone'];
		    $order['state'] = 'suggested';
		    $order['accept_time_secs'] = $accept_time_secs;
		    $order['accept_time_limit_secs'] = $system_time_secs + $accept_time_secs;
		  }
      else {
        $order['bombila'] = NULL;
        $order['state'] = 'no_bombila';
        $order['accept_time_secs'] = 0;       
        $order['accept_time_limit_secs'] = 0;        
      }
		}
		else if( $order['state'] == 'declined' ||
						 $order['state'] == 'search_again' ||
						 ( $order['state'] == 'suggested' && $order['accept_time_limit_secs'] < $system_time_secs ) ) {

			$bombila = findNearestBombila( $order, $order['bombila'] );
			if( $bombila != NULL ) {
		    $order['bombila'] = $bombila['phone'];
		    $order['state'] = 'suggested';
		    $order['accept_time_secs'] = $accept_time_secs;		    
		    $order['accept_time_limit_secs'] = $system_time_secs + $accept_time_secs;
			}
			else {
		    $order['bombila'] = NULL;
		    $order['state'] = 'no_bombila';
		    $order['accept_time_secs'] = 0;		    
  	    $order['accept_time_limit_secs'] = 0;
			}
		}
	}

  // $bombila = findNearestBombila( $order );

	$memcache->set('orders', $orders, false, 0);
}

function findNearestBombila( $order, $bombila_phone = NULL ) {
  global $memcache;

  $bombilas = $memcache->get('bombilas_locations');
  $nearest = NULL;

  $min = PHP_INT_MAX;
  $distance_meters = 0;

  foreach( $bombilas as &$bombila ) {

  	if( $bombila['phone'] == $bombila_phone ) // skip current
  		continue;

  	if( $order['barter_coin'] == true && $bombila['barter_coin'] == false ) // пропускаем если не принимает бартеркоин
      continue;

    if( $order['childish_armchair'] == true && $bombila['childish_armchair'] == false ) // пропускаем если не имеет детского кресла
      continue;

    $distance_meters = vincentyGreatCircleDistance( $order['from_latitude'], $order['from_longitude'], $bombila['latitude'], $bombila['longitude'] );
    if( $distance_meters < 1000000 && $distance_meters < $min ) {
      $min = $distance_meters;
      $nearest = $bombila;
    }
  }

  return $nearest;
}

function initMemcached3() {
  global $memcache;
  global $MEMCACHED_HOST;
  global $MEMCACHED_PORT;

  $memcache = new Memcache;

  $result = $memcache->connect( 'unix:///tmp/memcached' , 0 );
  // $result = $memcache->connect( 'localhost' , 11211 );
  if( !$result ) {
    echo 'Unable to connect to Memcached';
    exit(0);
  }

  echo 'Connected';

  $memcache->set('my_var', 'hello_var');

  $my_var = $memcache->get('my_var');

  var_dump( $my_var );

  exit(0);

  if( !is_array( $memcache->get('bombilas_locations') ) ) {
    $memcache->set('bombilas_locations', [], false, 0 );
    echo '!!!: memcache bombilas_locations created';
  }

  if( !is_array( $memcache->get('orders') ) ) {

    global $connection;

    openDbConnection();

    $query = "SELECT * from orders";

    $result = mysqli_query($connection, $query);

    $orders = array();

    while($row = mysqli_fetch_assoc($result)) {

      $row['from_latitude'] = floatval( $row['from_latitude'] );
      $row['from_longitude'] = floatval( $row['from_longitude'] );      
      $row['to_latitude'] = floatval( $row['to_latitude'] );
      $row['to_longitude'] = floatval( $row['to_longitude'] );
      $row['not_to_call'] = boolval( $row['not_to_call'] );
      $row['wait'] = boolval( $row['wait'] );
      $row['not_to_smoke'] = boolval( $row['not_to_smoke'] );
      $row['childish_armchair'] = boolval( $row['childish_armchair'] );
      
      $orders[] = $row;
    }    

    $memcache->set('orders', $orders, false, 0 );

    closeDbConnection();

    echo '!!!: memcache orders created';
  }
}

?>
