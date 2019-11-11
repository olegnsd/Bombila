<?php 

include 'common.php';

// initMemcached2();
doJobMySql();

function doJobMySql() {
	global $database;

	error_log("!!! doMySqlJob");	

	openDbConnection();

	// сначала удаляем тех, кто ушёл в оффлайн
	removeAwayBombilas();	

	$query = "SELECT * from orders where state IN ('new','declined','search_again','suggested','no_bombila')";

  $stmt = $database->prepare( $query );
  $stmt->execute();  	

  $orders = $stmt->fetchAll();

  $query = "SELECT * from bombilas_locations"; // здесь берём со всеми статусами

  $stmt = $database->prepare( $query );
  $stmt->execute();  	

  $bombilas = $stmt->fetchAll();

	$system_time_secs = time();
	$accept_time_secs = 60;

	foreach( $orders as &$order ) {

		if( $order['state'] == 'new' ) {

      error_log('!!! order:'.print_r($order,true));			

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



      saveOrderData( $order );
		}
		else if( $order['state'] == 'declined' ||
						 $order['state'] == 'search_again' ||
						 ( $order['state'] == 'suggested' && !isBombilaStillOnline( $order['bombila'], $bombilas ) ) || // случай когда водителю назначили и он отключился до того, как назначили, переводим дальше
						 ( $order['state'] == 'suggested' && $order['accept_time_limit_secs'] < $system_time_secs ) ) {

      error_log('!!! order:'.print_r($orders,true));		
      error_log('!!! not_to_call:'.$order['state']);		    
      error_log('!!! order:'.print_r($bombilas,true));	    

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

function isBombilaStillOnline( $phone, $bombilas ) {

	foreach( $bombilas as $bombila ) {

		if( $bombila['phone'] == $phone )
			return true;
	}

	return false;
}

function saveOrderData( $order ) {
	global $database;

	$order_id = $order['id'];
	$state = $order['state'];
	$bombila = $order['bombila']; // phone or null
	$accept_time_secs = $order['accept_time_secs'];
	$accept_time_limit_secs = $order['accept_time_limit_secs'];

	$query = 
"UPDATE orders 
SET 
	state=:state, 
	bombila=:bombila, 
	accept_time_secs=:accept_time_secs, 
	accept_time_limit_secs=:accept_time_limit_secs 
where 
	id=:order_id
";

	// error_log('!!! query'.$query);
	error_log('!!! bombila'.$bombila);

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':state', $state);  
  $stmt->bindParam(':bombila', $bombila);  
  $stmt->bindParam(':accept_time_secs', $accept_time_secs);  
  $stmt->bindParam(':accept_time_limit_secs', $accept_time_limit_secs);  
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->execute(); 	
}

function findNearestBombila( $bombilas, $order, $bombila_phone = NULL ) {

  $nearest = NULL;

  $min = PHP_INT_MAX;
  $distance_meters = 0;

  foreach( $bombilas as &$bombila ) {

  	if( $bombila['phone'] == $bombila_phone ) // skip current
  		continue;

  	if( $bombila['state'] == 'viewing_order' || $bombila['state'] == 'in_action' ) // пропускаем тех, кто выполняет или принимает заказ
  		continue;  

  	error_log("!!! strpos: ".strpos( $bombila['payment_methods'], $order['payment_method'] ) );		

  	if( strpos( $bombila['payment_methods'], $order['payment_method'] ) === false ) { // пропускаем если не принимает выбранный метод оплаты {
  		
  		error_log("!!! continue");		
  	  continue;
    }

    if( $order['childish_armchair'] == 1 && $bombila['childish_armchair'] == 0 ) // пропускаем если не имеет детского кресла
      continue;

    $distance_meters = vincentyGreatCircleDistance( $order['from_latitude'], $order['from_longitude'], $bombila['latitude'], $bombila['longitude'] );
    if( $distance_meters < 1000000 && $distance_meters < $min ) {
      $min = $distance_meters;
      $nearest = $bombila;
      break;
    }
  }

  return $nearest;
}

function removeAwayBombilas() {
	global $database;

	$now = time();
	$lifetime_secs = 10;

  $query = "DELETE from bombilas_locations where update_time + :lifetime_secs <= :now";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':lifetime_secs', $lifetime_secs);  
  $stmt->bindParam(':now', $now);  
  $stmt->execute(); 	
}

?>