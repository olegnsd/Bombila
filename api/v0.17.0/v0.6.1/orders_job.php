<?php 

include 'common.php';

// initMemcached2();
doJobMySql();



function doJobMySql() {
	global $connection;

	error_log("!!! doMySqlJob");	

	openDbConnection();

	$query = "SELECT * from orders where state IN ('new','declined','search_again','suggested','no_bombila')";

  $result = mysqli_query($connection, $query);

  $orders = array();

  while($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
  }

  $query = "SELECT * from bombilas_locations where state='accepting_orders'";

  $result = mysqli_query($connection, $query);

  $bombilas = array();

  while($row = mysqli_fetch_assoc($result)) {
    $bombilas[] = $row;
  }

	$system_time_secs = time();
	$accept_time_secs = 60;

	foreach( $orders as &$order ) {

		if( $order['state'] == 'new' ) {

			$bombila = findNearestBombila( $bombilas, $order );
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

      error_log('!!! order:'.print_r($order,true));

      saveOrderData( $order );
		}
		else if( $order['state'] == 'declined' ||
						 $order['state'] == 'search_again' ||
						 ( $order['state'] == 'suggested' && $order['accept_time_limit_secs'] < $system_time_secs ) ) {

			$bombila = findNearestBombila( $bombilas, $order, $order['bombila'] );
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

      saveOrderData( $order );			
		}
	}


	closeDbConnection();
}

function saveOrderData( $order ) {
	global $connection;

	$order_id = $order['id'];
	$state = $order['state'];
	$bombila = $order['bombila'] != NULL ? $order['bombila'] : 'NULL';
	$accept_time_secs = $order['accept_time_secs'];
	$accept_time_limit_secs = $order['accept_time_limit_secs'];

	$query = "UPDATE orders SET state='{$state}', bombila={$bombila}, accept_time_secs={$accept_time_secs}, accept_time_limit_secs={$accept_time_limit_secs} where id={$order_id}";

	error_log('!!! query'.$query);

  $result = mysqli_query($connection, $query);	
}

function findNearestBombila( $bombilas, $order, $bombila_phone = NULL ) {

  $nearest = NULL;

  $min = PHP_INT_MAX;
  $distance_meters = 0;

  foreach( $bombilas as &$bombila ) {

  	if( $bombila['phone'] == $bombila_phone ) // skip current
  		continue;

  	if( $order['payment_method'] == 'barter_coin' && $bombila['barter_coin'] == false ) // пропускаем если не принимает бартеркоин
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

function doJob() {
  global $memcache;	

	$orders = $memcache->get('orders');
  $bombilas = $memcache->get('bombilas_locations');

	error_log("!!! doJob");

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

?>