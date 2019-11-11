<?php

// phpinfo();
// exit(0);

include 'common.php';

header("Content-Type: application/json; charset=UTF-8");

// include "database.php";

// $mysqlnd = function_exists('mysqli_query'); /* mysqli_fetch_all */

// if ($mysqlnd) {
//     echo 'mysqlnd enabled!';
//     error_log( 'mysqlnd enabled!' );
// }
// else {
//     error_log( 'mysqlnd disabled!' );
// }

// exit(0);

$route = key($_GET);

openDbConnection();

if( $route == 'petrolStations/list' )
	petrolStationsList();

else if( $route == 'users/register' ) {

  $json = file_get_contents('php://input');
  $user = json_decode($json,true);

  if( $json === FALSE || $user === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  // error_log( "!!!".$json );
  // error_log( "!!!".print_r($user,true) );

  registerUser($user);
}

else if( $route == 'users/enter' )
  enter2( $_GET['phone'] );

else if( $route == 'users/getOneByPhone' ) {

	// echo( $_GET['phone'] );
	// exit(0);

	getOneUserByPhone( $_GET['phone'] );
}

else if( $route == 'users/getBombilaRating' )
  getBombilaRating( $_GET['phone'] );

else if( $route == 'users/sendBombilaLocation' ) {

  $json = file_get_contents('php://input');
  $bombilaLocation = json_decode($json,true);

  if( $json === FALSE || $bombilaLocation === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  // error_log( "!!!".$json );
  // error_log( "!!!".print_r($user,true) );

  sendBombilaLocation( $bombilaLocation );  
}

else if( $route == 'users/deleteBombilaLocation' )
  deleteBombilaLocation( $_GET['phone'] );

else if( $route == 'users/getBombilasLocations' )
  getBombilasLocations();

else if( $route == 'users/getNearBombilasLocations' )
  getBombilasLocations( $_GET['latitude'], $_GET['longitude'] );

else if( $route == 'users/getBombilaLocation' )
  getBombilaLocation( $_GET['phone'] );

else if( $route == 'photoControl/setCertificationPhoto' )
  setCertificationPhoto( $_POST['phone'] );

else if( $route == 'users/testImage' ) {

  $imageBytes = file_get_contents( '/var/www/vhosts/bombila/~2.jpg' ); 

  header("Content-type: image/jpeg");
  // header("Content-Disposition: attachment; filename=\"image_file\"");
  echo $imageBytes;  

}

else if( $route == 'photoControl/getCertificationPhoto' )
  getCertificationPhoto( $_GET['phone'] );

else if( $route == 'photoControl/getCertificationPhotoState' )
  getCertificationPhotoState( $_GET['phone'] );

else if( $route == 'photoControl/deleteCertificationPhoto' )
  deleteCertificationPhoto( $_GET['phone'] );

else if( $route == 'photoControl/setAutoPhoto' )
  setAutoPhoto( $_POST['phone'] );

else if( $route == 'photoControl/getAutoPhoto' )
  getAutoPhoto( $_GET['phone'] );

else if( $route == 'photoControl/getAutoPhotoState' )
  getAutoPhotoState( $_GET['phone'] );

else if( $route == 'photoControl/deleteAutoPhoto' )
  deleteAutoPhoto( $_GET['phone'] );

else if( $route == 'photoControl/getApprovalState' )
  getPhotoControlApprovalState( $_GET['phone'] );

else if( $route == 'photoControl/getBombilas' )
  getPhotoControlBombilas( $_GET['state'] );

else if( $route == 'photoControl/getData' )
  getPhotoControlData( $_GET['phone'] );

else if( $route == 'photoControl/approveCertPhoto' )
  approveCertPhoto( $_GET['phone'] );

else if( $route == 'photoControl/approveAutoPhoto' )
  approveAutoPhoto( $_GET['phone'] );

else if( $route == 'photoControl/declineCertPhoto' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  declineCertPhoto( $data['phone'], $data['reason'] );
}

else if( $route == 'photoControl/declineAutoPhoto' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  declineAutoPhoto( $data['phone'], $data['reason'] );
}

else if( $route == 'photoControl/searchBombilas' )
  searchBombilasForPhotoControl( $_GET['text'] );

else if( $route == 'messages/list' )
  getMessages( $_GET['phone'] );

else if( $route == 'chats/getMessages' )
  getChatMessages( $_GET['phone1'], $_GET['phone2'] );

else if( $route == 'chats/getLastMessageNumber' )
  getChatLastMessageNumber( $_GET['phone1'], $_GET['phone2'] );

else if( $route == 'chats/sendMessage' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }  

  sendChatMessage( $data['from_user'], $data['to_user'], $data['message'], $data['last_number'] );
}

else if( $route == 'chats/sendMessageIsRead' )
  sendChatMessageIsRead( $_GET['from_user'], $_GET['to_user'], $_GET['number'] );

else if( $route == 'chats/setAllMessagesRead' )
  setAllMessagesRead( $_GET['from_user'], $_GET['to_user'] );


else if( $route == 'chats/getChatsForDispatcher' )
  getChatsForDispatcher();

else if( $route == 'chats/getNewChatMessages' )
  getNewChatMessages( $_GET['phone1'], $_GET['phone2'], $_GET['last_number'] );

else if( $route == 'chats/getNewChatsMessages' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }  

  getNewChatsMessages( $data );
}


else if( $route == 'hotels/getNearest' )
  getNearestHotels( $_GET['latitude'], $_GET['longitude'] );

else if( $route == 'hotels/getOne' )
  getOneHotel( $_GET['id'] );

else if( $route == 'orders/new' ) {

  $json = file_get_contents('php://input');
  $order = json_decode($json,true);

  if( $json === FALSE || $order === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  error_log( "!!!".$json );
  error_log( "!!!".print_r($order,true) );

  newOrder( $order );  
}

else if( $route == 'orders/getOrderState' )
  getOrderState( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/searchBombila' )
  searchBombila( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/passengerCancelOrder' )
  passengerCancelOrder( $_GET['phone'], $_GET['order_id'] );


else if( $route == 'orders/getNearest' )
  getNearestOrders( $_GET['latitude'], $_GET['longitude'] );

else if( $route == 'orders/getSuggestedOrder' )
  getSuggestedOrder( $_GET['phone'] );

else if( $route == 'orders/accept' )
  acceptOrder( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/decline' )
  declineOrder( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/here' )
  hereOrder( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/go' )
  goOrder( $_GET['phone'], $_GET['order_id'], $_GET['waiting_price'] );

else if( $route == 'orders/finish' )
  finishOrder( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/markAsPaid' )
  markOrderAsPaid( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/getOrderSyncData' )
  getOrderSyncData( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/requestPassengerRoute' )
  requestPassengerRoute( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/confirmPassengerRoute' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  confirmPassengerRoute( $data['phone'], $data['order_id'] );
}

else if( $route == 'orders/declinePassengerRoute' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  declinePassengerRoute( $data['phone'], $data['order_id'] );  
}

else if( $route == 'orders/syncPricePassenger' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  syncPricePassenger( $data['phone'], $data['order_id'], $data['price'] );
}

else if( $route == 'orders/syncPriceBombila' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  syncPriceBombila( $data['phone'], $data['order_id'], $data['price'] );
}

else if( $route == 'orders/requestPriceOffer' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  requestPriceOffer( $data['phone'], $data['order_id'], $data['price'] );
}

else if( $route == 'orders/confirmPriceOffer' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  confirmPriceOffer( $data['phone'], $data['order_id'] );
}

else if( $route == 'orders/declinePriceOffer' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  declinePriceOffer( $data['phone'], $data['order_id'] );
}

else if( $route == 'history/get' )
  getTripsHistory( $_GET['phone'] );

else if( $route == 'userAgreement/get' )
  getUserAgreement();

else if( $route == 'userAgreement/save' )
  saveUserAgreement( file_get_contents('php://input') );

else if( $route == 'tariffs/list' )
  getTariffs();

else if( $route == 'tariffs/saveTariff' ) {

  $json = file_get_contents('php://input');
  $tariff = json_decode($json,true);

  if( $json === FALSE || $tariff === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  saveTariff( $tariff['name'], $tariff['value'] );
}

else if( $route == 'commission/set' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);
  }

  setCommission( $data['value'] );
}

else if( $route == 'commission/get' )
  getCommission();

else if( $route == 'mark/addMarkToBombila' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);
  }

  addMarkToBombila( $data['phone'], $data['order_id'], $data['mark'] );
}
else if( $route == 'mark/addMarkToPassenger' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);
  }

  addMarkToPassenger( $data['phone'], $data['order_id'], $data['mark'] );
}
else if( $route == 'waiting/setMinutesFree' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);
  }

  setMinutesFree( $data['value'] );  
}
else if( $route == 'waiting/getMinutesFree' )
  getMinutesFree();

else if( $route == 'waiting/setMinutePrice' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);
  }

  setMinutePrice( $data['value'] );  
}
else if( $route == 'waiting/getMinutePrice' )
  getMinutePrice();

else {
  http_response_code(404);
  echo '';
}


// print_r($_GET);
// echo $value;
// echo $p;
// echo $_GET['var1'];

// echo 'hello';

closeDbConnection();

function petrolStationsList() {
  global $connection;

  $query = "SELECT * FROM petrol_stations";
  $result = mysqli_query($connection, $query);

  $array = array();

  while($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
  }

  echo json_encode($array);
}

function getOneUserByPhone($phone) {
  global $connection;

  $query = sprintf("SELECT * FROM users where phone='%s' limit 1", $phone);  

  $result = mysqli_query($connection, $query);

	if( mysqli_num_rows($result) == 0  ) {
		http_response_code(404);
		echo '';
	}
	else {
	  $row = mysqli_fetch_assoc($result);
	  echo json_encode($row);	
	}
}

function registerUser($user) {
  global $connection;

  $stmt = mysqli_prepare($connection, "SELECT * FROM users where phone=? limit 1");
  mysqli_stmt_bind_param($stmt, "s", $user['phone']);
  $result = mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log( "error:".mysqli_error( $connection ) );     
    echo '';
    exit(0);
  }
  if( mysqli_stmt_num_rows($stmt) == 1 ) {
    http_response_code(406); // User already registered
    error_log("error: user with phone=".$user['phone']." already registered");         
    echo '';
    exit(0);    
  }

  $stmt = mysqli_prepare($connection, "INSERT INTO users(phone, firstname, lastname) VALUES(?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "sss", $user['phone'], $user['firstname'], $user['lastname']);
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: user with phone=".$user['phone']." insert error");  
    echo '';
    exit(0);     
  }

    /* проверить */

    // $stmt = mysqli_prepare($connection, "INSERT INTO bombilas_certifications_photos(phone, photo, extension) VALUES(?, ?, ?)");
    // $photo = null;
    // $extension = '';
    // mysqli_stmt_bind_param($stmt, "sbs", $user['phone'], $photo, $extension);
    // $result = mysqli_stmt_execute($stmt);    

    // if( !$result ) {
    //   http_response_code(500);
    //   error_log("error: bombilas_certifications_photos record insert error");  
    //   echo '';
    //   exit(0);     
    // }        

    // $stmt = mysqli_prepare($connection, "INSERT INTO bombilas_autos_photos(phone, photo, extension) VALUES(?, ?, ?)");
    // $photo = null;
    // $extension = '';    
    // mysqli_stmt_bind_param($stmt, "sbs", $user['phone'], $photo, $extension);
    // $result = mysqli_stmt_execute($stmt);    

    // if( !$result ) {
    //   http_response_code(500);
    //   error_log("error: bombilas_autos_photos record insert error");  
    //   echo '';
    //   exit(0);     
    // }    

  $currentdir = getcwd();

  mkdir( $currentdir.'/../../files/photocontrol/'.$user['phone'] );

  echo '';
}






function enter($phone) {
  global $connection;

  $stmt = mysqli_prepare($connection, "SELECT * FROM users where phone=? limit 1");
  mysqli_stmt_bind_param($stmt, "s", $phone);
  $result = mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log( "error:".mysqli_error( $connection ) );     
    echo '';
    mysqli_stmt_close($stmt);    
    exit(0);
  }
  if( mysqli_stmt_num_rows($stmt) == 1 ) {

    // mysqli_stmt_bind_result($stmt, $row);
    // $row = mysqli_stmt_fetch_assoc($stmt);

    // $row = mysqli_stmt_fetch_assoc($stmt);

    $row = mysqli_fetch_assoc( mysqli_stmt_get_result( $stmt ) );

    error_log("!!! user:".$row);
    echo json_encode($row);     
  }
  else {
    http_response_code(404);
    error_log("error: user with phone=".$phone." not registered");         
    echo '';
  }   

   mysqli_stmt_close($stmt);
}

function enter2($phone) {
  global $connection;

  $query = sprintf("SELECT * FROM users where phone='%s' limit 1", $phone);  

  $result = mysqli_query($connection, $query);

  if( mysqli_num_rows($result) == 0  ) {
    http_response_code(404);
    echo '';
  }
  else {
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row); 
  }  
}

function getBombilaRating( $phone ) {
  global $connection;

  $query = sprintf("SELECT bs.phone,u.firstname,u.lastname,bs.rating,bs.accepted_orders,bs.fullfilled_orders FROM bombilas_ratings as bs INNER JOIN users as u on bs.phone = u.phone where bs.phone='%s' limit 1", $phone);

  $result = mysqli_query($connection, $query);

  if( mysqli_num_rows($result) == 0  ) {
    http_response_code(404);
    echo '';
  }
  else {
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row); 
  }  
}

function getMessages( $phone ) {

  global $connection;

  $query = "SELECT header,text FROM messages where phone='".$phone."'";
  $result = mysqli_query($connection, $query);

  $array = array();

  while($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
  }

  echo json_encode($array);  
}

function getChatMessages( $phone1, $phone2 ) {
  global $connection;

  $query = "SELECT c.from_user,c.to_user,concat(u1.firstname,' ',u1.lastname) as 'from_fullname',c.message,c.number FROM chats as c inner join users as u1 on c.from_user = u1.phone inner join users as u2 on c.to_user = u2.phone where ( from_user='".$phone1."' and to_user='".$phone2."' ) or ( from_user='".$phone2."' and to_user='".$phone1."') order by c.time asc";
  $result = mysqli_query($connection, $query);

  $array = array();

  while($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
  }

  echo json_encode($array);    
}

function setCertificationPhoto( $phone ) {
  global $connection;

  $temp = $_FILES['image_file']['tmp_name'];
  $file = getcwd().'/../../files/photocontrol/'.$phone.'/cert.jpg';

  $result = copy( $temp, $file );
  if( !$result ) {
    http_response_code(500);    
    echo "Failed to save file: " . $file;
    exit(0);    
  }

  $query = 
"INSERT INTO photocontrol(user, type, state, reason) 
VALUES(?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
  user=?,
  type=?,
  state=?,
  reason=?
";

  $type = 'cert';
  $state = 'for_approval';
  $reason = '';

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "ssssssss", 
    $phone, 
    $type,
    $state,
    $reason,
    $phone,
    $type,
    $state,
    $reason
  );
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: user with phone=".$phone." insert error ". mysqli_connect_error());  
    echo '';
    exit(0);     
  }  

  echo '';
}

function setAutoPhoto( $phone ) {
  global $connection;

  $temp = $_FILES['image_file']['tmp_name'];
  $file = getcwd().'/../../files/photocontrol/'.$phone.'/auto.jpg';

  $result = copy( $temp, $file );
  if( !$result ) {
    http_response_code(500);    
    echo "Failed to save file: " . mysqli_connect_error();
    exit(0);    
  }

  $query = 
"INSERT INTO photocontrol(user, type, state, reason) 
VALUES(?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
  user=?,
  type=?,
  state=?,
  reason=?
";

  $type = 'auto';
  $state = 'for_approval';
  $reason = '';

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "ssssssss", 
    $phone, 
    $type,
    $state,
    $reason,
    $phone,
    $type,
    $state,
    $reason
  );
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: user with phone=".$phone." insert error");  
    echo '';
    exit(0);     
  }  

  echo '';
}

function getCertificationPhoto( $phone ) {
  $imageBytes = file_get_contents( getcwd().'/../../files/photocontrol/'.$phone.'/cert.jpg' );

  header("Content-type: image/jpeg");
  echo $imageBytes;  
}

function getCertificationPhotoState( $phone ) {
  global $connection;

  $query = "SELECT * from photocontrol where user='{$phone}' and type='cert' limit 1";
  $result = mysqli_query($connection, $query);

  $response;

  while($row = mysqli_fetch_assoc($result)) {

    $response['state'] = $row['state'];
    $response['reason'] = $row['reason'];

    break;
  }

  echo json_encode($response);  
}

function deleteCertificationPhoto( $phone ) {
  global $connection;  

  $result = unlink( getcwd().'/../../files/photocontrol/'.$phone.'/cert.jpg' );

  if( !$result ) {
      http_response_code(500);
      error_log("error: could not delete file");
      echo '';
      exit(0);       
  }

  $query = sprintf("DELETE FROM photocontrol where user='{$phone}' and type='cert'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol data was not deleted : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }      

  echo '';
}

function getAutoPhoto( $phone ) {
  $imageBytes = file_get_contents( getcwd().'/../../files/photocontrol/'.$phone.'/auto.jpg' );

  header("Content-type: image/jpeg");
  echo $imageBytes;  
}

function getAutoPhotoState( $phone ) {
  global $connection;

  $query = "SELECT * from photocontrol where user='{$phone}' and type='auto' limit 1";
  $result = mysqli_query($connection, $query);

  $response;

  while($row = mysqli_fetch_assoc($result)) {

    $response['state'] = $row['state'];
    $response['reason'] = $row['reason'];

    break;
  }

  echo json_encode($response);  
}

function deleteAutoPhoto( $phone ) {
  global $connection;

  $result = unlink( getcwd().'/../../files/photocontrol/'.$phone.'/auto.jpg' );

  if( !$result ) {
      http_response_code(500);
      error_log("error: could not delete file");
      echo '';
      exit(0);       
  }

  $query = sprintf("DELETE FROM photocontrol where user='{$phone}' and type='auto'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol data was not deleted : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }       

  echo '';
}

function getPhotoControlApprovalState( $phone ) {
  global $connection;

  $query = "SELECT * from photocontrol where user='{$phone}' limit 2";

  $result = mysqli_query($connection, $query);

  $response;

  if( mysqli_num_rows($result) == 0 ) {
    
    $response['state'] = 'no_data';
  }
  else if( mysqli_num_rows($result) == 1 ) {

    $response['state'] = 'not_enough_data';
  }
  else
  {
    while($row = mysqli_fetch_assoc($result)) {

      if( $row['state'] == 'for_approval' ) {

        $response['state'] = 'for_approval';
        break;
      }
      else if( $row['state'] == 'not_approved' ) {

        $response['state'] = 'not_approved';
        break;
      }
    }

    if( $response['state'] == NULL )
      $response['state'] = 'approved';
  }  

  echo json_encode($response);
}

function getNearestHotels( $latitude, $longitude ) {
  global $connection;

  $query = 
"SELECT 
   h.id, h.name, h.description_short, h.latitude, h.longitude, 
   111.11 *
    ROUND(DEGREES(ACOS(LEAST(COS(RADIANS(h.latitude))
         * COS(RADIANS({$latitude}))
         * COS(RADIANS(h.longitude - {$longitude}))
         + SIN(RADIANS(h.latitude))
         * SIN(RADIANS({$latitude})), 1.0))), 2) AS distance_in_km
FROM hotels as h
ORDER BY distance_in_km
LIMIT 100
";

  //$query = "select * from hotels";
  $result = mysqli_query($connection, $query);

  $array = array();

  while($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
  }

  echo json_encode($array);
}

function getOneHotel( $id ) {
  global $connection;

  $query = "SELECT * from hotels where id='{$id}' limit 1";
  $result = mysqli_query($connection, $query);

  if( mysqli_num_rows($result) == 0  ) {
    http_response_code(404);
    echo '';
    exit(0);
  }

  $hotel = mysqli_fetch_assoc($result);

  // отзывы

  $query = "SELECT reviewer_name,text,mark from hotels_reviews where hotel_id='{$id}'";
  $result = mysqli_query($connection, $query);

  $hotel['reviews'] = array();

  while($row = mysqli_fetch_assoc($result)) {
    $hotel['reviews'][] = $row;
  }  

  // изображения

  $files = scandir( getcwd().'/../../files/hotels/'.$hotel['id']);

  error_log( "!!! files: ".print_r($files,true) );

  $files = array_diff($files, array('.', '..'));

  error_log( "!!! files: ".print_r($files,true) );  

  foreach( $files as $file ) {
    $image['href'] = 'http://'.$_SERVER['HTTP_HOST'].'/files/hotels/'.$hotel['id'].'/'.$file;
    $hotel['images'][] = $image;
  }

  // $hotel['images'][0]['href'] = 'http://'.$_SERVER['HTTP_HOST'].'/cert.jpg';
  // $hotel['images'][1]['href'] = 'http://'.$_SERVER['HTTP_HOST'].'/cert.jpg';
  // $hotel['images'][2]['href'] = 'http://'.$_SERVER['HTTP_HOST'].'/cert.jpg';

  echo json_encode($hotel);
}

function newOrder( $order ) {
  global $connection;

  $query = 
"INSERT INTO orders( 
  passenger, 
  from_latitude, 
  from_longitude, 
  from_address, 
  to_latitude,
  to_longitude,
  to_address,
  passengers_comment,
  payment_method,
  not_to_call,
  wait,
  not_to_smoke,
  childish_armchair,
  state,
  accept_time_secs,
  accept_time_limit_secs,
  rated_by_passenger,
  rated_by_bombila
)
VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
";

  $order['state'] = 'new';
  $order['accept_time_secs'] = 0;
  $order['accept_time_limit_secs'] = 0;
  $order['rated_by_passenger'] = 0;  
  $order['rated_by_bombila'] = 0;  

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "sddsddsssiiiisiiii", 
    $order['passenger'], 
    $order['from_latitude'], 
    $order['from_longitude'],
    $order['from_address'],
    $order['to_latitude'],
    $order['to_longitude'],
    $order['to_address'],
    $order['passengers_comment'],
    $order['payment_method'],
    $order['not_to_call'],
    $order['wait'],
    $order['not_to_smoke'],
    $order['childish_armchair'],
    $order['state'],
    $order['accept_time_secs'],
    $order['accept_time_limit_secs'],
    $order['rated_by_passenger'],
    $order['rated_by_bombila']
  );  

  // error_log("!!! query:".);

  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: order from user=".$order['passenger']." insert error: ".mysqli_error( $connection ));  
    echo '';
    exit(0);     
  }

  $order['id'] = strval( mysqli_stmt_insert_id($stmt) );

  $response['order_id'] = $order['id'];

  echo json_encode( $response );
}

function getOrderState( $phone, $order_id ) {
  global $connection;

  $query = "SELECT * from orders where id={$order_id} and passenger='{$phone}' limit 1";

  // error_log('!!! $query'.$query);

  $result = mysqli_query($connection, $query);

  $response;

  while($row = mysqli_fetch_assoc($result)) {

    $response['state'] = $row['state'];
    $response['bombila'] = $row['bombila'];

    break;
  }

  echo json_encode($response);
}

function searchBombila( $phone, $order_id ) {
  global $connection;

  $query = sprintf("UPDATE orders set state='search_again' where id={$order_id} and passenger='{$phone}'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }  
}

function passengerCancelOrder( $phone, $order_id ) {
  global $connection;

  $query = sprintf("DELETE FROM orders where id={$order_id} and passenger='{$phone}'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders was not deleted : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }    
}

function acceptOrder( $phone, $order_id ) {
  global $connection;

  $query = sprintf("UPDATE orders set bombila='{$phone}', state='accepted' where id={$order_id}"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  echo '';
}

function declineOrder( $phone, $order_id ) {
  global $connection;

  $query = sprintf("UPDATE orders set state='declined', bombila='{$phone}', accept_time_secs=0, accept_time_limit_secs=0 where id={$order_id}");
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  echo '';  
}

function hereOrder( $phone, $order_id ) {
  global $connection;

  $query = sprintf("UPDATE orders set state='here' where id={$order_id} and bombila='{$phone}'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  echo '';
}


function goOrder( $phone, $order_id, $waiting_price ) {
  global $connection;

  mysqli_begin_transaction($connection, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);  

  $query = "UPDATE orders set state='in_way' where id={$order_id} and bombila='{$phone}'"; 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    mysqli_commit($connection);
    http_response_code(500);
    error_log( "error: orders status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) VALUES(?, ?, ?)
";

  $reason = "in_way";
  $state = "in_way";

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "iss", $order_id, $reason, $state);
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    mysqli_commit($connection);    
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) VALUES(?, ?, ?)
";

  $reason = "sync_waiting_price";
  $state = $waiting_price;

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "iss", $order_id, $reason, $state);
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    mysqli_commit($connection);      
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }  

  mysqli_commit($connection);  

  echo '';
}

function finishOrder( $phone, $order_id ) {
  global $connection;

  mysqli_begin_transaction($connection, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);    

  $query = sprintf("UPDATE orders set state='finished' where id={$order_id} and bombila='{$phone}'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    mysqli_commit($connection);    
    http_response_code(500);
    error_log( "error: orders status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) VALUES(?, ?, ?)
";

  $reason = "finished";
  $state = "finished";

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "iss", $order_id, $reason, $state);
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    mysqli_commit($connection);    
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }  

  $query = 
"INSERT into trips_history(
  passenger,
  from_latitude,
  from_longitude,
  from_address,
  to_latitude,
  to_longitude,
  to_address
)
SELECT
  passenger,
  from_latitude,
  from_longitude,
  from_address,
  to_latitude,
  to_longitude,
  to_address
FROM 
  orders
WHERE 
  id = {$order_id} and 
  passenger = '{$phone}'
";

  $result = mysqli_query($connection, $query);

  if( !$result ) {
    mysqli_commit($connection);    
    http_response_code(500);
    error_log( "error: trip history record can not be inserted : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  mysqli_commit($connection);    

  echo '';  
}

function markOrderAsPaid( $phone, $order_id ) {
  global $connection;

  mysqli_begin_transaction($connection, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);    

  $query = sprintf("UPDATE orders set state='paid' where id={$order_id} and bombila='{$phone}'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    mysqli_commit($connection);    
    http_response_code(500);
    error_log( "error: orders status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }  

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) VALUES(?, ?, ?)
";

  $reason = "paid";
  $state = "paid";

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "iss", $order_id, $reason, $state);
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    mysqli_commit($connection);    
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }     

  mysqli_commit($connection); 

  echo '';    
}

/* не используется */
function getNearestOrders( $latitude, $longitude ) {
  global $connection;

  $query = 
"SELECT 
   *, 
   111.11 *
    ROUND(DEGREES(ACOS(LEAST(COS(RADIANS(o.from_latitude))
         * COS(RADIANS({$latitude}))
         * COS(RADIANS(o.from_longitude - {$longitude}))
         + SIN(RADIANS(o.from_latitude))
         * SIN(RADIANS({$latitude})), 1.0))), 2) AS distance_in_km
FROM orders as o
WHERE o.state = 'new'
ORDER BY distance_in_km
LIMIT 100
";

  $result = mysqli_query($connection, $query);

  $array = array();

  while($row = mysqli_fetch_assoc($result)) {

    $row['not_to_call'] = boolval( $row['not_to_call'] );
    $row['wait'] = boolval( $row['wait'] );
    $row['not_to_smoke'] = boolval( $row['not_to_smoke'] );
    $row['childish_armchair'] = boolval( $row['childish_armchair'] );
    
    $array[] = $row;
  }

  echo json_encode($array);
}

function getSuggestedOrder( $phone ) {
  global $connection;

  $query = "SELECT * from orders where bombila='{$phone}' and state='suggested' limit 1";
  $result = mysqli_query($connection, $query);
  $order = NULL;

  while($row = mysqli_fetch_assoc($result)) {
    $order = $row;
    break;
  }

  if( $order != NULL ) {
    echo json_encode( $order );
  }
  else {
    http_response_code(404);
    echo '';
  }  
}

function sendBombilaLocation( $data ) {
  global $connection;

  // $data['latitude'] = floatval( $data['latitude'] );
  // $data['longitude'] = floatval( $data['longitude'] );  

  $query = 
"INSERT INTO 
bombilas_locations 
(
  phone, 
  latitude, 
  longitude, 
  barter_coin, 
  childish_armchair
) 
VALUES (?,?,?,?,?)
ON DUPLICATE KEY UPDATE

latitude=?, 
longitude=?, 
barter_coin=?, 
childish_armchair=?
";

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "sddiiddii", 
    $data['phone'], 
    $data['latitude'], 
    $data['longitude'],
    $data['barter_coin'],
    $data['childish_armchair'],

    $data['latitude'], 
    $data['longitude'],
    $data['barter_coin'],
    $data['childish_armchair']    
  );  

  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: couldn't update bombila location");  
    echo '';
    exit(0);     
  }
}

function deleteBombilaLocation( $phone ) {
  global $connection;

  $query = sprintf("DELETE FROM bombilas_locations where phone='$phone'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders was not deleted : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }      

  echo '';  
}

function getBombilasLocations() {
  global $connection;

  $query = "SELECT * from bombilas_locations";
  $result = mysqli_query($connection, $query);
  
  $locations = array();

  while($row = mysqli_fetch_assoc($result)) {
    $locations[] = $row;
  }  

  echo json_encode( $locations );
}

function getBombilaLocation( $phone ) {
  global $connection;

  $query = "SELECT * from bombilas_locations where phone='{$phone}' limit 1";
  $result = mysqli_query($connection, $query);
  
  if( mysqli_num_rows($result) == 0  ) {
    http_response_code(404);
    echo '';
  }
  else {
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row); 
  }    
}

function getNearBombilasLocations( $latitude, $longitude ) {
  global $connection;

  $query = 
"SELECT * FROM 
  (
  SELECT 
     *, 
     111.11 *
      ROUND(DEGREES(ACOS(LEAST(COS(RADIANS(b.latitude))
           * COS(RADIANS({$latitude}))
           * COS(RADIANS(b.longitude - {$longitude}))
           + SIN(RADIANS(b.latitude))
           * SIN(RADIANS({$latitude})), 1.0))), 2) AS distance_in_km
  FROM bombilas_locations as b
  LIMIT 100    
  ) t
WHERE t.distance_in_km <= 1000";  

  $result = mysqli_query($connection, $query);

  $locations = array();

  while($row = mysqli_fetch_assoc($result)) {
    $locations[] = $row;
  }  

  echo json_encode( $locations );
}

function getTripsHistory( $phone ) {
  global $connection;

  $query = "SELECT id, from_address, to_address from orders where passenger='{$phone}' order by id desc";

  $result = mysqli_query($connection, $query);

  $orders = array();

  while($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
  }  

  echo json_encode( $orders );  
}

function getPhotoControlBombilas( $state ) {
  global $connection;

  if( $state == 'all' ) {

  $query = 
"SELECT * from users as u where phone in ( select user from photocontrol )
";

  }
  else {

  $query = 
"SELECT * from users as u where phone in ( select user from photocontrol where state='{$state}' )
";

  }

  $result = mysqli_query($connection, $query);

  $bombilas = array();

  while($row = mysqli_fetch_assoc($result)) {
    $bombilas[] = $row;
  }  

  echo json_encode( $bombilas );    
}

function getPhotoControlData( $phone ) {
  global $connection;

  $query = "SELECT * from photocontrol as u where user='{$phone}' limit 2";

  $result = mysqli_query($connection, $query);

  $datas = array();

  while($row = mysqli_fetch_assoc($result)) {
    $datas[] = $row;
  }  

  echo json_encode( $datas );      
}

function approveCertPhoto( $phone ) {
  global $connection;

  $query = sprintf("UPDATE photocontrol set state='approved',reason='' where user='{$phone}' and type='cert'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  echo '';
}

function approveAutoPhoto( $phone ) {
  global $connection;

  $query = sprintf("UPDATE photocontrol set state='approved',reason='' where user='{$phone}' and type='auto'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  echo '';
}

function declineCertPhoto( $phone, $reason ) {
  global $connection;

  $query = sprintf("UPDATE photocontrol set state='not_approved',reason='{$reason}' where user='{$phone}' and type='cert'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  echo '';
}

function declineAutoPhoto( $phone, $reason ) {
  global $connection;

  $query = sprintf("UPDATE photocontrol set state='not_approved',reason='{$reason}' where user='{$phone}' and type='auto'"); 
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol status was not updated : ".mysqli_error( $connection ) );
    echo '';
    exit(0);
  }

  echo '';
}

function searchBombilasForPhotoControl( $text ) {
  global $connection;

  $text = '%'.$text.'%';

  $query = 
"SELECT * from users 
where phone in ( select user from photocontrol ) and
( phone like '{$text}' or firstname like '{$text}' or lastname like '{$text}' )
";

  $result = mysqli_query($connection, $query);

  $bombilas = array();

  while($row = mysqli_fetch_assoc($result)) {
    $bombilas[] = $row;
  }  

  echo json_encode( $bombilas );      
}

function getUserAgreement() {
  global $connection;

  $query = 
"SELECT data from user_agreement limit 1
";

  $result = mysqli_query($connection, $query);

  $response;

  while($row = mysqli_fetch_assoc($result)) {
    $response = $row['data'];
  }  

  header("Content-Type: text/html; charset=UTF-8");
  echo $response;
}

function saveUserAgreement( $text ) {
  global $connection;

  $query = "UPDATE user_agreement set data = '{$text}'";

  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(500);
    echo '';
  }
  else
    echo '';
}

function getTariffs() {
  global $connection;

  $query = 
"SELECT * from tariffs
";

  $result = mysqli_query($connection, $query);

  $tariffs = array();

  while($row = mysqli_fetch_assoc($result)) {
    $tariffs[] = $row;
  }  

  echo json_encode( $tariffs );
}

function saveTariff( $name, $value ) {
  global $connection;

  error_log('!!! name'.$name);
  error_log('!!! value'.$value);

  $query = "UPDATE tariffs set value = {$value} where name='{$name}'";

  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';
}

function getChatLastMessageNumber( $from_user, $to_user ) {
  global $connection;

  $query = 
"SELECT 
  COALESCE( max(number), 0 ) as 'number' 
  from chats 
  where 
    (from_user='{$from_user}' and to_user='{$to_user}') or 
    (from_user='{$to_user}' and to_user='{$from_user}')
";

  $result = mysqli_query($connection, $query);

  $number = 0;  

  while($row = mysqli_fetch_assoc($result)) {
    $number = intval( $row['number'] );
    break;
  }

  $output['last_number'] = $number;

  echo json_encode( $output );
}

function sendChatMessage( $from_user, $to_user, $message, $last_number ) {
  global $connection;

  mysqli_begin_transaction($connection, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

  $time = time();
  $number = 0;

  $query = 
"SELECT 
  COALESCE( max(number), 0 ) as 'number' 
  from chats 
  where 
    (from_user='{$from_user}' and to_user='{$to_user}') or 
    (from_user='{$to_user}' and to_user='{$from_user}')
";

  $result = mysqli_query($connection, $query);

  while($row = mysqli_fetch_assoc($result)) {
    $number = intval( $row['number'] );
    break;
  }

  $number += 1;

  error_log('!!! number: '.$number);

  $stmt = mysqli_prepare($connection, "INSERT INTO chats(from_user, to_user, message, time, number) VALUES(?, ?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "sssii", $from_user, $to_user, $message, $time, $number);
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    mysqli_commit($connection);  
    http_response_code(500);
    error_log("error: chat message can not be inserted");
    echo '';
    exit(0);     
  }

  $query = "SELECT c.from_user,c.to_user,concat(u1.firstname,' ',u1.lastname) as 'from_fullname',c.message,c.number FROM chats as c inner join users as u1 on c.from_user = u1.phone inner join users as u2 on c.to_user = u2.phone where (( from_user='{$from_user}' and to_user='{$to_user}' ) or ( from_user='{$to_user}' and to_user='{$from_user}')) and c.number > {$last_number} order by c.number asc";
  error_log('!!! query'.$query );
  $result = mysqli_query($connection, $query);

  $messages = array();

  while($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
  }

  mysqli_commit($connection);  

  echo json_encode($messages);     
}

function sendChatMessageIsRead( $from_user, $to_user, $number ) {
  global $connection;

  $query = "UPDATE chats set is_read = 1 where from_user='{$from_user}' and to_user='{$to_user}' and number={'$number'}";

  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';  
}

function getChatsForDispatcher() {
  global $connection;

  $query = 
"SELECT 
   c.from_user,
   u.firstname, 
   u.lastname,
   c.last_number,
   ur.n_unread
from  
( select 
    n.from_user,
    max( n.number ) as 'last_number'
    from (
      select 
        case when from_user <> 'dispatcher' then from_user else to_user end as 'from_user',
        'dispatcher' as 'to_user',
        number
      from chats 
      where ( from_user='dispatcher' or to_user='dispatcher')
    ) as n
    group by n.from_user
) as c
inner join users as u on c.from_user = u.phone
inner join (
  select
    from_user,
      count( IF(is_read=0,1, NULL) ) as 'n_unread'
    from chats
    where from_user <> 'dispatcher' and to_user='dispatcher'
    group by from_user
) as ur on c.from_user = ur.from_user
";

  $result = mysqli_query($connection, $query);

  $datas = array();

  while($row = mysqli_fetch_assoc($result)) {
    $datas[] = $row;
  }  

  echo json_encode( $datas );     
}

function setAllMessagesRead( $from_user, $to_user ) {
  global $connection;

  $query = "UPDATE chats set is_read = 1 where from_user='{$from_user}' and to_user='{$to_user}'";
  $result = mysqli_query($connection, $query);

  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';   
}

function getNewChatMessages( $phone1, $phone2, $last_number ) {
  global $connection;

  $query = "SELECT c.from_user,c.to_user,concat(u1.firstname,' ',u1.lastname) as 'from_fullname',c.message,c.number FROM chats as c inner join users as u1 on c.from_user = u1.phone inner join users as u2 on c.to_user = u2.phone where (( from_user='{$phone1}' and to_user='{$phone2}' ) or ( from_user='{$phone2}' and to_user='{$phone1}')) and c.number > {$last_number} order by c.number asc";
  $result = mysqli_query($connection, $query);

  $array = array();

  while($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
  }

  echo json_encode($array);    
}

function getNewChatsMessages( $chats ) {
  global $connection;  

  $output = array();

  $chat;

  for($i=0;$i<count( $chats );$i++) {

    $chat = $chats[$i];

    $phone1 = 'dispatcher';
    $phone2 = $chat['from_user'];
    $last_number = $chat['last_number'];

    $query = "SELECT c.from_user,c.to_user,concat(u1.firstname,' ',u1.lastname) as 'from_fullname',c.message,c.number FROM chats as c inner join users as u1 on c.from_user = u1.phone inner join users as u2 on c.to_user = u2.phone where (( from_user='{$phone1}' and to_user='{$phone2}' ) or ( from_user='{$phone2}' and to_user='{$phone1}')) and c.number > {$last_number} order by c.number asc";
    $result = mysqli_query($connection, $query);

    $messages = array();

    while($row = mysqli_fetch_assoc($result)) {
      $messages[] = $row;
    }

    if( count($messages) > 0 )
      $last_number = intval( $messages[count($messages)-1]['number'] );

    $output_row['from_user'] = $chat['from_user'];
    $output_row['last_number'] = $last_number;
    $output_row['messages'] = $messages;

    $output[] = $output_row;
  }

  echo json_encode($output);  
}

function setCommission( $commission ) {
  global $connection;

  $query = "UPDATE commission set value={$commission}";
  $result = mysqli_query($connection, $query);

  echo '';
}

function getCommission() {
  global $connection;

  $query = "SELECT value from commission limit 1";
  $result = mysqli_query($connection, $query);

  $output;

  while($row = mysqli_fetch_assoc($result)) {
    $output['value'] = $row['value'];
    break;
  }

  echo json_encode($output);        
}

function addMarkToBombila( $phone, $order_id, $mark ) {
  global $connection;

  mysqli_begin_transaction($connection, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);  

  $query = 
"UPDATE users 
set 
  total_mark=total_mark+{$mark}, 
  marks_amount=marks_amount+1 
where phone in ( 
  select bombila from orders where id={$order_id} and rated_by_passenger=0 
) 
limit 1";
  $result = mysqli_query($connection, $query);

  $query = "UPDATE orders set rated_by_passenger=1 where id={$order_id} limit 1";
  $result = mysqli_query($connection, $query);

  $query = 
"DELETE from orders_sync 
where order_id in ( 
  select id from orders 
  where id={$order_id} and rated_by_passenger=1 and rated_by_bombila=1
)";
  $result = mysqli_query($connection, $query);

  mysqli_commit($connection);   

  echo '';
}

function addMarkToPassenger( $phone, $order_id, $mark ) {
  global $connection;

  mysqli_begin_transaction($connection, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);    

  $query = 
"UPDATE users 
set 
  total_mark=total_mark+{$mark}, 
  marks_amount=marks_amount+1 
where phone in ( 
  select passenger from orders where id={$order_id} and rated_by_bombila=0 
) 
limit 1";

  error_log('!!! query'. $query );

  $result = mysqli_query($connection, $query);

  $query = "UPDATE orders set rated_by_bombila=1 where id={$order_id} limit 1";
  $result = mysqli_query($connection, $query);

  $query = 
"DELETE from orders_sync 
where order_id in ( 
  select id from orders 
  where id={$order_id} and rated_by_passenger=1 and rated_by_bombila=1
) 
";
  $result = mysqli_query($connection, $query);  

  mysqli_commit($connection);  

  echo '';
}

function setMinutesFree( $value ) {
  global $connection;

  $query = "UPDATE waiting set value={$value} where name='minutes_free'";
  $result = mysqli_query($connection, $query);


  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';     
}

function getMinutesFree() {
  global $connection;

  $query = "SELECT value from waiting where name='minutes_free'";
  $result = mysqli_query($connection, $query);

  $output;

  while($row = mysqli_fetch_assoc($result)) {
    $output['value'] = $row['value'];
    break;
  }

  echo json_encode($output);      
}

function setMinutePrice( $value ) {
  global $connection;

  $query = "UPDATE waiting set value={$value} where name='minute_price' limit 1";
  $result = mysqli_query($connection, $query);


  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';     
}

function getMinutePrice() {
  global $connection;

  $query = "SELECT value from waiting where name='minute_price'";
  $result = mysqli_query($connection, $query);

  $output;

  while($row = mysqli_fetch_assoc($result)) {
    $output['value'] = $row['value'];
    break;
  }

  echo json_encode($output);  
}

function getOrderSyncData( $phone, $order_id ) {
  global $connection;

  $query = 
"SELECT os.order_id, os.reason, os.state 
from orders_sync as os
inner join orders as o on ( os.order_id = o.id )
where 
  os.order_id = {$order_id} and
  ( o.passenger = '{$phone}' or o.bombila = '{$phone}' )
";

  $result = mysqli_query($connection, $query);

  $output = array();

  while($row = mysqli_fetch_assoc($result)) {
    $output[] = $row;
  }

  echo json_encode($output);    
}

function requestPassengerRoute( $phone, $order_id ) {
  global $connection;  

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) 
select ? as order_id, ? as reason, ? as state
from orders
where bombila=? and id=?
ON DUPLICATE KEY UPDATE state=?
";

  $reason = "passenger_route";
  $state = 'request';

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "isssis", $order_id, $reason, $state, $phone, $order_id, $state);
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }  

  echo '';  
}

function confirmPassengerRoute( $phone, $order_id ) {
  global $connection;

  $query = 
"UPDATE orders_sync set state='confirmed' 
where order_id={$order_id} and reason='passenger_route' and order_id in ( 
  select id from orders where id={$order_id} and passenger='{$phone}' 
)
";

  $result = mysqli_query($connection, $query);

  echo '';
}

function declinePassengerRoute( $phone, $order_id ) {
  global $connection;

  $query = 
"UPDATE orders_sync set state='declined' 
where order_id={$order_id} and reason='passenger_route' and order_id in ( 
  select id from orders where id={$order_id} and passenger='{$phone}' 
)";

  $result = mysqli_query($connection, $query);

  echo '';
}

function syncPricePassenger( $phone, $order_id, $price ) {
  global $connection;

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) 
select ? as order_id, ? as reason, ? as state
from orders
where passenger=? and id=?
ON DUPLICATE KEY UPDATE order_id=order_id
";

  $reason = "sync_price_passenger";
  $state = $price;

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "isssi", $order_id, $reason, $state, $phone, $order_id );
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }  

  echo '';  
}

function syncPriceBombila( $phone, $order_id, $price ) {
  global $connection;

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) 
select ? as order_id, ? as reason, ? as state
from orders
where bombila=? and id=?
ON DUPLICATE KEY UPDATE order_id=order_id
";

  $reason = "sync_price_bombila";
  $state = $price;

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "isssi", $order_id, $reason, $state, $phone, $order_id );
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }  

  echo '';  
}

function requestPriceOffer( $phone, $order_id, $price ) {
  global $connection;

  $query = 
"INSERT INTO orders_sync(order_id, reason, state) 
select ? as order_id, ? as reason, ? as state
from orders
where bombila=? and id=?
ON DUPLICATE KEY UPDATE state=?
";

  $reason = "price_offer";
  $state = $price;

  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "isssis", $order_id, $reason, $state, $phone, $order_id, $state );
  $result = mysqli_stmt_execute($stmt);

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted");
    echo '';
    exit(0);     
  }  

  echo '';  
}

function confirmPriceOffer( $phone, $order_id ) {
  global $connection;

  $query = 
"UPDATE orders_sync set state='confirmed' 
where order_id={$order_id} and reason='price_offer' and order_id in ( 
  select id from orders where id={$order_id} and passenger='{$phone}' 
)";

  $result = mysqli_query($connection, $query);

  echo '';
}

function declinePriceOffer( $phone, $order_id ) {
  global $connection;

  $query = 
"UPDATE orders_sync set state='declined' 
where order_id={$order_id} and reason='price_offer' and order_id in ( 
  select id from orders where id={$order_id} and passenger='{$phone}' 
)";

  $result = mysqli_query($connection, $query);

  echo ''; 
}


?>