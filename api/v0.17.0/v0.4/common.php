<?php 

$DATABASE_HOST = "localhost";
$DATABASE = "bombila_main";
$LOGIN = "bombila_main";
$PASSWORD = "MQivz6tnKI";

$connection;

$MEMCACHED_HOST = 'unix:///tmp/systemd-private-07e2881a14244f81bf46e5d11d77835a-memcached.service-y9aCpg'; /* unix:///tmp/memcached */ /* localhost */ /* unix:///tmp/systemd-private-07e2881a14244f81bf46e5d11d77835a-memcached.service-y9aCpg */
$MEMCACHED_PORT = 0; // 11211 0

$memcache;

function openDbConnection() {
  global $connection;
  global $DATABASE_HOST;
  global $LOGIN;
  global $PASSWORD;
  global $DATABASE;  
  $connection = mysqli_connect($DATABASE_HOST, $LOGIN, $PASSWORD, $DATABASE);
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
  global $MEMCACHED_HOST;
  global $MEMCACHED_PORT;

  $memcache = new Memcache;

  $memcache->connect( $MEMCACHED_HOST , $MEMCACHED_PORT );
  if( !$memcache ) {
    error_log( "Unable to connect to Memcached" );
    exit(0);
  }

  if( !is_array( $memcache->get('bombilas_locations') ) ) {
    $memcache->set('bombilas_locations', [], false, 0 );
    error_log( "!!!: memcache bombilas_locations created" );
  }

  if( !is_array( $memcache->get('orders') ) ) {

    global $connection;

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
  }
}

// function initMemcached2() {
//   global $memcache;
//   global $MEMCACHED_HOST;
//   global $MEMCACHED_PORT;

//   $memcache = new Memcache;

//   $memcache->connect( $MEMCACHED_HOST , $MEMCACHED_PORT );
//   if( !$memcache ) {
//     error_log( "Unable to connect to Memcached" );
//     exit(0);
//   }

//   if( !is_array( $memcache->get('bombilas_locations') ) ) {
//     $memcache->set('bombilas_locations', [], false, 0 );
//     error_log( "!!!: memcache bombilas_locations created" );
//   }

//   if( !is_array( $memcache->get('orders') ) ) {

//     global $connection;

//     openDbConnection();

//     $query = "SELECT * from orders";

//     $result = mysqli_query($connection, $query);

//     $orders = array();

//     while($row = mysqli_fetch_assoc($result)) {

//       $row['from_latitude'] = floatval( $row['from_latitude'] );
//       $row['from_longitude'] = floatval( $row['from_longitude'] );      
//       $row['to_latitude'] = floatval( $row['to_latitude'] );
//       $row['to_longitude'] = floatval( $row['to_longitude'] );
//       $row['not_to_call'] = boolval( $row['not_to_call'] );
//       $row['wait'] = boolval( $row['wait'] );
//       $row['not_to_smoke'] = boolval( $row['not_to_smoke'] );
//       $row['childish_armchair'] = boolval( $row['childish_armchair'] );
      
//       $orders[] = $row;
//     }    

//     $memcache->set('orders', $orders, false, 0 );

//     closeDbConnection();

//     error_log( "!!!: memcache orders created" );
//   }
// }

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
