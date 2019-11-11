<?php 

$DATABASE_HOST = "localhost";
$DATABASE = "bombila_dev";
$LOGIN = "bombila_dev";
$PASSWORD = "nBQVypTvoZ";

$database;

$memcache;

function openDbConnection() {
  global $database;
  global $DATABASE_HOST;
  global $LOGIN;
  global $PASSWORD;
  global $DATABASE;  

  try {

    $database = new PDO("mysql:host={$DATABASE_HOST};dbname={$DATABASE};charset=utf8mb4", $LOGIN, $PASSWORD);

    $database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );     
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }
  catch(PDOException $e) {  

    error_log( $e->getMessage() );

    http_response_code(500);    
    exit(0);
  }  
}

function closeDbConnection() {
  global $database;

  $database = null; // closes connection
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
