#!/usr/bin/php
<?php 

$memcache;

$SERVER = "localhost";
$DATABASE = "bombila_main";
$LOGIN = "bombila_main";
$PASSWORD = "MQivz6tnKI";

$connection = "";

initMemcached();
doJob();


function openDbConnection() {
  global $connection;
  global $DATABASE;
  global $LOGIN;
  global $PASSWORD;
  $connection = mysqli_connect("localhost", $LOGIN, $PASSWORD, $DATABASE);
  if (mysqli_connect_errno()) {
		http_response_code(500);  	
    // echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit(0);
  }
  mysqli_set_charset($connection, "utf8mb4");
}

function closeDbConnection() {
  global $connection;
  mysqli_close($connection);
}


function initMemcached() {
	global $memcache;

  $memcache = new Memcache;

  $memcache->connect('unix:///tmp/memcached', 0);
  if( !$memcache ) {
    error_log( "Unable to connect to Memcached" );
    exit(0);
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
    
    error_log( "!!!: memcache orders created" );

    closeDbConnection();
  }
}

function doJob() {
  global $memcache;	

	$orders = $memcache->get('orders');

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



/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function vincentyGreatCircleDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}


?>
