<?php

/*
  Автор и владелец кода - Станислав Романенко
  Копирование без разрешения запрещено
  По всем вопросам: stanislav.romanenko@gmail.com

  Я разрешаю использование этого кода компании "Милитари Холдинг" в тестовом режиме в течение августа месяца 2019 года.
  Далее, если компания "Милитари Холдинг" не выкупит права на использование - использование будет запрещено.
*/

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

function checkAuthorization() {
  global $AUTH_KEY;

  $headers = getallheaders();

  foreach ($headers as $name => $value) {

      if( $name === 'Authorization' && $value === $AUTH_KEY )
        return;
      // echo error_log("$name: $value <br>"); 
  }

  http_response_code(401);    
  exit(0);   
}

checkAuthorization();


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

else if( $route == 'users/editUser' ) {

  $json = file_get_contents('php://input');
  $user = json_decode($json,true);

  if( $json === FALSE || $user === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  editUser( $user );
}


else if( $route == 'users/updatePushToken' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  updatePushToken( $data['phone'], $data['push_token'] );
}

else if( $route == 'users/enter' )
  enter( $_GET['phone'] );

else if( $route == 'users/setUserType' )
  setUserType( $_GET['phone'], $_GET['user_type'] );

else if( $route == 'users/isPhoneNumberFree' )
  isPhoneNumberFree( $_GET['phone'] );

else if( $route == 'users/getSimilarPhones' )
  getSimilarPhones( $_GET['phone'] );

else if( $route == 'users/getOneByPhone' )
	getOneUserByPhone( $_GET['phone'] );

else if( $route == 'users/list' )
  getUsers();

else if( $route == 'users/searchUsers' )
  searchUsers( $_GET['text'] );


else if( $route == 'users/getBombilaRating' )
  getBombilaRating( $_GET['phone'] );

else if( $route == 'users/getPassengerRating' )
  getPassengerRating( $_GET['phone'] );

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
  getNearBombilasLocations( $_GET['phone'], $_GET['latitude'], $_GET['longitude'] );

else if( $route == 'users/getBombilaLocation' )
  getBombilaLocation( $_GET['phone'] );

else if( $route == 'users/getUserRating' )
  getUserRating( $_GET['phone'] );

else if( $route == 'users/getUserReviews' )
  getUserReviews( $_GET['phone'] );


else if( $route == 'users/testImage' ) {

  $imageBytes = file_get_contents( '/var/www/vhosts/bombila/~2.jpg' ); 

  header("Content-type: image/jpeg");
  // header("Content-Disposition: attachment; filename=\"image_file\"");
  echo $imageBytes;  

}

else if( $route == 'photoControl/setPhoto' )
  setPhotoControlPhoto( $_POST['phone'], $_POST['type'] );

else if( $route == 'photoControl/getPhoto' )
  getPhotoControlPhoto( $_GET['phone'], $_GET['type'] );

else if( $route == 'photoControl/getPhotoState' )
  getPhotoControlPhotoState( $_GET['phone'], $_GET['type'] );

else if( $route == 'photoControl/deletePhoto' )
  deletePhotoControlPhoto( $_GET['phone'], $_GET['type'] );


else if( $route == 'photoControl/getApprovalState' )
  getPhotoControlApprovalState( $_GET['phone'] );

else if( $route == 'photoControl/getBombilas' )
  getPhotoControlBombilas( $_GET['state'] );

else if( $route == 'photoControl/getData' )
  getPhotoControlData( $_GET['phone'] );

else if( $route == 'photoControl/approvePhoto' )
  approvePhotoControlPhoto( $_GET['phone'], $_GET['type'] );

else if( $route == 'photoControl/declinePhoto' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  declinePhotoControlPhoto( $data['phone'], $data['type'], $data['reason'] );
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

  sendChatMessage( $data['sender_type'], $data['from_user'], $data['to_user'], $data['message'], $data['last_number'] );
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

else if( $route == 'orders/list' )
  getOrders();

else if( $route == 'orders/searchOrders' )
  searchOrders( $_GET['text'] );


else if( $route == 'orders/new' ) {

  $json = file_get_contents('php://input');
  $order = json_decode($json,true);

  if( $json === FALSE || $order === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  newOrder( $order );  
}

else if( $route == 'orders/getOrderState' )
  getOrderState( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/searchBombila' )
  searchBombila( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/passengerCancelOrderSoft' )
  passengerCancelOrderSoft( $_GET['phone'], $_GET['order_id'] );


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

else if( $route == 'orders/reached' )
  reachedOrder( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/forPayment' )
  forPaymentOrder( $_GET['phone'], $_GET['order_id'], $_GET['price_end'] );

else if( $route == 'orders/finish' )
  finishOrder( $_GET['phone'], $_GET['order_id'] );

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

else if( $route == 'orders/timeoutPassengerRoute' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  timeoutPassengerRoute( $data['phone'], $data['order_id'] );  
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

else if( $route == 'orders/timeoutPriceOffer' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  timeoutPriceOffer( $data['phone'], $data['order_id'] );
}

else if( $route == 'orders/requestPassengerPayment' )
  requestPassengerPayment( $_GET['phone'], $_GET['order_id'], $_GET['bombila_card'] );

else if( $route == 'orders/passengerPaymentPassed' )
  passengerPaymentPassed( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/passengerPaymentNotPassed' )
  passengerPaymentNotPassed( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'orders/payNegativeCommission' )
  payNegativeCommission( $_GET['phone'], $_GET['order_id'], $_GET['bombila_card_number'], $_GET['sum'] );

else if( $route == 'orders/updateOrderComissionData' )
  updateOrderComissionData( $_GET['phone'], $_GET['order_id'], $_GET['percent'], $_GET['sum'] );

else if( $route == 'orders/getSystemBarterCoinCardNumber' )
  getSystemBarterCoinCardNumber();

else if( $route == 'orders/bombilaCancelOrderHard' )
  bombilaCancelOrderHard( $_GET['phone'], $_GET['order_id'], $_GET['reason_side'] );

else if( $route == 'orders/passengerCancelOrderHard' )
  passengerCancelOrderHard( $_GET['phone'], $_GET['order_id'], $_GET['reason_side'] );

else if( $route == 'orders/sendBombilaCardOnPassengerOrderCancelHard' )
  sendBombilaCardOnPassengerOrderCancelHard( $_GET['phone'], $_GET['order_id'], $_GET['card_number'] );

else if( $route == 'orders/sendPaymentResultOnPassengerOrderCancelHard' )
  sendPaymentResultOnPassengerOrderCancelHard( $_GET['phone'], $_GET['order_id'], $_GET['payment_result'] );

else if( $route == 'orders/cleanUpOrderSyncDataOnCancel' )
  cleanUpOrderSyncDataOnCancel( $_GET['phone'], $_GET['order_id'] );

else if( $route == 'history/get' )
  getTripsHistory( $_GET['phone'] );

else if( $route == 'userAgreement/get' )
  getUserAgreement();

else if( $route == 'userAgreement/save' )
  saveUserAgreement( file_get_contents('php://input') );

else if( $route == 'tariffs/list' )
  getTariffs();

else if( $route == 'tariffs/getTariff' )
  getTariff( $_GET['name'] );

else if( $route == 'tariffs/saveTariff' ) {

  $json = file_get_contents('php://input');
  $tariff = json_decode($json,true);

  if( $json === FALSE || $tariff === NULL ) {
      http_response_code(400);    
      exit(0);  
  }

  saveTariff( $tariff['name'], $tariff['value'] );
}

else if( $route == 'mark/addMarkToBombila' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);
  }

  addMarkToBombila( $data['from_user'], $data['to_user'], $data['order_id'], $data['mark'], $data['review'] );
}
else if( $route == 'mark/addMarkToPassenger' ) {

  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  if( $json === FALSE || $data === NULL ) {
      http_response_code(400);    
      exit(0);
  }

  addMarkToPassenger( $data['from_user'], $data['to_user'], $data['order_id'], $data['mark'], $data['review'] );  
}
else if( $route == 'push/send' )
  sendPushNotification( $_GET['to_phone'], $_GET['event'], $_GET['ext'] );

else if( $route == 'settings/get' )
  getSetting( $_GET['name'] );


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
  global $database;

  $query = "SELECT * FROM petrol_stations";
  $stmt = $database->prepare( $query ); 
  $stmt->execute();

  echo json_encode( $stmt->fetchAll() );
}

function getOneUserByPhone($phone) {
  global $database;

  $stmt = $database->prepare( "SELECT * FROM users where phone=:phone limit 1" );
  $stmt->bindParam(':phone', $phone);
  $stmt->execute(); 

  $users = $stmt->fetchAll();

	if( count($users) == 0  ) {
		http_response_code(404);
		echo '';
	}
	else {
	  echo json_encode( $users[0] );	
	}
}

function isPhoneNumberFree( $phone ) {
  global $database;

  $stmt = $database->prepare( "SELECT * FROM users where phone=:phone limit 1" );
  $stmt->bindParam(':phone', $phone);
  $stmt->execute(); 

  $users = $stmt->fetchAll();

  if( count($users) == 0  ) {
    echo '';
  }
  else {
    http_response_code(406);
    echo '';
  }

}

function getSimilarPhones( $phone ) {
  global $database;

  $like_phone = $phone;

  // для российских номеров делаем поиск номера с другими префиксами

  if( strpos($phone, '7') === 0 )
    $like_phone = substr( $phone, 1 );

  else if( strpos($phone, '+7') === 0 )
    $like_phone = substr( $phone, 2 );

  else if( strpos($phone, '8') === 0 )
    $like_phone = substr( $phone, 1 );

  $like_phone = '%'.$like_phone;

  // error_log( '!!! like_phone: '.$like_phone );
  // echo $likePhone;

  $stmt = $database->prepare( "SELECT * FROM users where phone like :like_phone limit 5" );
  $stmt->bindParam(':like_phone', $like_phone);
  $stmt->execute(); 

  $users = $stmt->fetchAll();

  echo json_encode( $users );    
}

function getUsers() {
  global $database;

  $query = "SELECT * FROM users";
  $stmt = $database->prepare( $query ); 
  $stmt->execute();

  echo json_encode( $stmt->fetchAll() );  
}

function searchUsers( $text ) {
  global $database;

  $text = '%'.$text.'%';

  $query = 
"SELECT * from users 
where ( phone like :text or firstname like :text or lastname like :text )
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':text', $text);
  $stmt->execute(); 

  echo json_encode( $stmt->fetchAll() );  
}

function registerUser($user) {
  global $database;

  $database->beginTransaction();

  try {

    $query = "SELECT * FROM users where phone=:phone limit 1";

    $stmt = $database->prepare( $query );
    $stmt->bindParam(':phone', $user['phone']);
    $stmt->execute(); 

    if( count( $stmt->fetchAll() ) == 1 ) {
      $database->rollBack();      
      http_response_code(406); // User already registered
      error_log("error: user with phone=".$user['phone']." already registered");         
      echo '';
      exit(0);          
    }

    $query = "INSERT INTO users(phone, firstname, lastname, registration_date) VALUES(:phone, :firstname, :lastname, NOW())";

    $stmt = $database->prepare( $query );
    $stmt->bindParam(':phone', $user['phone']);
    $stmt->bindParam(':firstname', $user['firstname']);
    $stmt->bindParam(':lastname', $user['lastname']);
    $result = $stmt->execute();  

    if( !$result ) {
      $database->rollBack();
      throw new PDOException("error: user with phone=".$user['phone']." insert error");
    }       

    $query = "INSERT INTO push_tokens(user, token) VALUES(:phone, :token)";

    $stmt = $database->prepare( $query );
    $stmt->bindParam(':phone', $user['phone']);
    $stmt->bindParam(':token', $user['push_token']);
    $result = $stmt->execute();  

    if( !$result ) {
      $database->rollBack(); 
      throw new PDOException("error: user with phone=".$user['phone']." insert error");
    }           
  }
  catch(PDOException $e) {

    $database->rollBack();
    error_log( "error:".$e->getMessage() );
    http_response_code(500);
    echo '';   
    exit(0);    
  }

  $database->commit();

  $currentdir = getcwd();

  mkdir( $currentdir.'/../../files/photocontrol/'.$user['phone'] );

  echo '';
}

function editUser( $user ) {
  global $database;

  $database->beginTransaction();

  $firstname = $user['firstname'];
  $lastname = $user['lastname'];
  $old_phone = $user['old_phone'];
  $phone = $user['phone'];
  $email = $user['email'];    

  try {

    $query = "SELECT * FROM users where phone=:old_phone limit 1";

    $stmt = $database->prepare( $query );
    $stmt->bindParam(':old_phone', $user['old_phone']);
    $stmt->execute(); 

    if( count( $stmt->fetchAll() ) == 0 ) {
      $database->rollBack();      
      http_response_code(400); // User already registered
      error_log("error: user with phone=".$user['old_phone']." not registered");          
      echo '';
      exit(0);          
    }

    error_log("!!! flag 010");

    if( $user['phone'] == $user['old_phone'] ) {

      $query = "SELECT * FROM users where email=:email and email<>'' and phone<>:phone  limit 1";

      $stmt = $database->prepare( $query );
      $stmt->bindParam(':email', $user['email']);
      $stmt->bindParam(':phone', $user['phone']);
      $stmt->execute();

      if( count( $stmt->fetchAll() ) == 1 ) {
        $database->rollBack();
        http_response_code(406); // already registered
        error_log("error: user with email=".$user['email']." already registered");
        echo '';
        exit(0);          
      }
    }
    else {

      $query = "SELECT * FROM users where ( ( phone=:phone or email=:email ) and not email='' ) and not ( phone=:old_phone and email=:email ) limit 1";

      $stmt = $database->prepare( $query );
      $stmt->bindParam(':email', $user['email']);
      $stmt->bindParam(':phone', $user['phone']);
      $stmt->bindParam(':old_phone', $user['old_phone']);
      $stmt->execute();

      if( count( $stmt->fetchAll() ) == 1 ) {
        $database->rollBack();
        http_response_code(406); // already registered
        error_log("error: user with phone=".$user['phone']." or email=".$user['email']." already registered");         
        echo '';
        exit(0);         
      }
    }

    error_log("!!! flag 020");


    $query = "UPDATE users set firstname=:firstname, lastname=:lastname, phone=:phone, email=:email where phone=:old_phone limit 1";

    $stmt = $database->prepare( $query );
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':old_phone', $old_phone);
    $result = $stmt->execute(); 

    if( !$result ) {
      $database->rollBack();
      throw new PDOException("error: user with phone=".$user['phone']." update error");      
    }    

    // change folder name

    $currentdir = getcwd();

    $oldfoldername = $currentdir.'/../../files/photocontrol/'.$user['old_phone'];
    $newfoldername = $currentdir.'/../../files/photocontrol/'.$user['phone'];  

    if( $user['phone'] != $user['old_phone'] && file_exists($oldfoldername) ) {

      $result = rename( $oldfoldername, $newfoldername );  

      if( !$result ) {
        $database->rollBack();      
        throw new PDOException("error: user was not updated : folder rename failed");        
      }    
    }    

    $database->commit();
  }
  catch(PDOException $e) {

    $database->rollBack();
    error_log( "error:".$e->getMessage() );
    http_response_code(500);
    echo '';   
    exit(0);    
  }

  echo '';
}


function updatePushToken( $phone, $token ) {
  global $database;

  $query = 
"INSERT into push_tokens( user, token )
values( :phone, :token )
ON DUPLICATE KEY update token=:token";

  $stmt = $database->prepare( $query );
  $stmt->bindParam( ':phone', $phone );
  $stmt->bindParam( ':token', $token );
  $result = $stmt->execute();

  error_log("!!! push-token: ".$token);

  if( !$result ) {
    http_response_code(500);
    error_log("error: user with phone=".$phone." insert/update error ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);      
  }

  echo '';  
}


function enter($phone) {
  global $database;

  $query = "SELECT * FROM users where phone=:phone limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  error_log("!!! phone:". $phone);  

  if( !$result ) {
    http_response_code(500);
    error_log( "error:".$stmt->errorInfo()[2] );     
    echo '';
    exit(0);
  }

  $users = $stmt->fetchAll();

  if( count( $users ) == 1 ) {

    echo json_encode( reset($users) );
  }  
  else {

    http_response_code(404);
    error_log("error: user with phone=".$phone." not registered");         
    echo '';    
  }
}

function setUserType( $phone, $user_type ) {
  global $database;

  $query = "UPDATE users set user_type=:user_type where phone=:phone limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':user_type', $user_type);
  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log( "error: user_type was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }      

  echo '';
}

function getBombilaRating( $phone ) {
  global $database;

  $output;  

  $query = 
"SELECT 
  phone,
  firstname,
  lastname
FROM users
WHERE phone=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['phone'] = $row['phone'];    
  $output['firstname'] = $row['firstname'];
  $output['lastname'] = $row['lastname'];  

  $query = 
"SELECT 
  ROUND( SUM( mark ) / COUNT(*), 3 ) as 'average_mark'
FROM users_reviews
WHERE to_user=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['average_mark'] = $row['average_mark'];  


  $query = 
"SELECT 
    COALESCE( ROUND( SUM( IF(accepted=1, 1, 0) ) / COUNT( order_id ) * 100 ), 0 ) as 'accepted_orders'
FROM bombilas_accepted_orders
WHERE bombila=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['accepted_orders'] = $row['accepted_orders'];  

  /* 
    для рассчёта выполненных заказов также включаем те, которые были отменены, но причина была на стороне водителя
    т.к. водитель в этом не виноват, и на рейтинг это сказываться не должно
   */

  $query = 
"SELECT 
    COALESCE( 
      ROUND( SUM( IF( cancellation_reason_on_bombila_side=0, 1, 0) ) / COUNT( id ) * 100 ), 
    0 ) as 'fullfilled_orders'
from orders
WHERE bombila=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['fullfilled_orders'] = $row['fullfilled_orders'];  

  echo json_encode($output);
}

function getPassengerRating( $phone ) {
  global $database;

  $output;

  $query = 
"SELECT 
  phone,
  firstname,
  lastname
FROM users
WHERE phone=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['phone'] = $row['phone'];    
  $output['firstname'] = $row['firstname'];
  $output['lastname'] = $row['lastname'];  


  $query = 
"SELECT 
  ROUND( SUM( mark ) / COUNT(*), 3 ) as 'average_mark'
FROM users_reviews
WHERE to_user=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['average_mark'] = $row['average_mark'];  

  /* 
    для рассчёта завершённых заказов также включаем те, которые были отменены, но причина была на стороне пассажира
    т.к. пассажир в этом не виноват, и на рейтинг это сказываться не должно
   */  

  $query = 
"SELECT 
    COALESCE( ROUND( SUM( IF(cancellation_reason_on_passenger_side=0, 1, 0) ) / COUNT( id ) * 100 ), 0 ) as 'finished_orders'
from orders
WHERE passenger=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['finished_orders'] = $row['finished_orders'];  

  echo json_encode($output);  
}

function getUserReviews( $phone ) {
  global $database;

  $query = 
"SELECT
  u.firstname,
  u.lastname,
  ur.mark,
  ur.review
from users_reviews as ur
inner join users as u on ur.from_user = u.phone
where ur.to_user=:phone
";
  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->execute();

  $reviews = $stmt->fetchAll();

  echo json_encode($reviews);
}

// не используется
function getMessages( $phone ) {
  global $database;

  $query = "SELECT header,text FROM messages where phone=:phone";
  
  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->execute();

  $messages = $stmt->fetchAll();

  echo json_encode($messages);  
}

function getChatMessages( $phone1, $phone2 ) {
  global $database;

  $query = 
"SELECT 
  c.from_user,
  c.to_user,
  case 
    when c.from_user <> 'dispatcher' 
      then concat(u1.firstname,' ',u1.lastname) 
      else 'Оператор поддержки'
  end as 'from_fullname',
  c.message,
  c.number 
FROM chats as c 
inner join users as u1 on c.from_user = u1.phone 
inner join users as u2 on c.to_user = u2.phone 
where ( from_user=:phone1 and to_user=:phone2 ) or ( from_user=:phone2 and to_user=:phone1 ) 
order by c.time asc";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone1', $phone1);
  $stmt->bindParam(':phone2', $phone2);
  $stmt->execute();

  $messages = $stmt->fetchAll();

  echo json_encode($messages);    
}

function setPhotoControlPhoto( $phone, $type ) { // type: auto, cert, sts
  global $database;

  $temp = $_FILES['image_file']['tmp_name'];
  $file = getcwd().'/../../files/photocontrol/'.$phone.'/'.$type.'.jpg';

  $result = copy( $temp, $file );
  if( !$result ) {
    http_response_code(500);    
    error_log("Failed to save file: " . $file);
    exit(0);    
  }

  $query = 
"INSERT INTO photocontrol(user, type, state, reason) 
VALUES(:phone, :type, :state, :reason)
ON DUPLICATE KEY UPDATE
  user=:phone,
  type=:type,
  state=:state,
  reason=:reason
";

  // $type = 'auto';
  $state = 'for_approval';
  $reason = '';

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':reason', $reason);
  $result = $stmt->execute();    

  if( !$result ) {
    http_response_code(500);
    error_log("error: user with phone=".$phone." insert error". $stmt->errorInfo()[2]);  
    echo '';
    exit(0);     
  }  

  echo '';  
}

function getPhotoControlPhoto( $phone, $type ) {
  $imageBytes = file_get_contents( getcwd().'/../../files/photocontrol/'.$phone.'/'.$type.'.jpg' );

  header("Content-type: image/jpeg");
  echo $imageBytes;    
}

function getPhotoControlPhotoState( $phone, $type ) {
  global $database;

  $query = "SELECT state,reason from photocontrol where user=:phone and type=:type limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':type', $type);
  $result = $stmt->execute();  

  $response = $stmt->fetch();

  echo json_encode($response);    
}

function deletePhotoControlPhoto( $phone, $type ) {
  global $database;  

  $result = unlink( getcwd().'/../../files/photocontrol/'.$phone.'/'.$type.'.jpg' );

  if( !$result ) {
      http_response_code(500);
      error_log("error: could not delete file");
      echo '';
      exit(0);       
  }

  $query = "DELETE FROM photocontrol where user=:phone and type=:type";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':type', $type);
  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol data was not deleted : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }      

  echo '';
}

function getPhotoControlApprovalState( $phone ) {

  $response = getPhotoControlApprovalStateReturn( $phone );

  echo json_encode($response);
}

function getPhotoControlApprovalStateReturn( $phone ) {
  global $database;

  $query = "SELECT * from photocontrol where user=:phone limit 3";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $rows = $stmt->fetchAll();
  $num_rows = count( $rows ); 

  $response;

  if( $num_rows == 0 ) {
    
    $response['state'] = 'no_data';
  }
  else if( $num_rows == 1 || $num_rows == 2 ) {

    $response['state'] = 'not_enough_data';
  }
  else
  {

    foreach( $rows as $row ) {

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

  return $response;
}

function getNearestHotels( $latitude, $longitude ) {
  global $database;

  $query = 
"SELECT 
   h.id, h.name, h.description_short, h.latitude, h.longitude, 
   111.11 *
    ROUND(DEGREES(ACOS(LEAST(COS(RADIANS(h.latitude))
         * COS(RADIANS( :latitude ))
         * COS(RADIANS(h.longitude - :longitude ))
         + SIN(RADIANS(h.latitude))
         * SIN(RADIANS( :latitude )), 1.0))), 2) AS distance_in_km
FROM hotels as h
ORDER BY distance_in_km
LIMIT 100
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':latitude', $latitude);
  $stmt->bindParam(':longitude', $longitude);
  $result = $stmt->execute();

  $hotels = $stmt->fetchAll();

  echo json_encode($array);
}

function getOneHotel( $id ) {
  global $database;

  $query = "SELECT * from hotels where id=:id limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $hotels = $stmt->fetchAll();

  if( count( $hotels ) == 0  ) {
    http_response_code(404);
    echo '';
    exit(0);
  }

  $hotel = reset( $hotels );

  // отзывы

  $query = "SELECT reviewer_name,text,mark from hotels_reviews where hotel_id=:id";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $hotel['reviews'] = $stmt->fetchAll();

  // изображения

  $files = scandir( getcwd().'/../../files/hotels/'.$hotel['id']);

  error_log( "!!! files: ".print_r($files,true) );

  $files = array_diff($files, array('.', '..'));

  error_log( "!!! files: ".print_r($files,true) );  

  foreach( $files as $file ) {
    $image['href'] = 'http://'.$_SERVER['HTTP_HOST'].'/files/hotels/'.$hotel['id'].'/'.$file;
    $hotel['images'][] = $image;
  }

  echo json_encode($hotel);
}


function getOrders() {
  global $database;

  $query = "SELECT * FROM orders";

  $stmt = $database->prepare( $query );
  $stmt->execute();

  $orders = $stmt->fetchAll();

  echo json_encode($orders);  
}

function searchOrders( $text ) {
  global $database;

  $text = '%'.$text.'%';

  $query = 
"SELECT * from orders 
where 
( 
  id = CONVERT(:text, UNSIGNED ) or 
  passenger like :text or
  bombila like :text or 
  from_address like :text or 
  to_address like :text or 
  state like :text
)
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':text', $text);
  $stmt->execute();

  $orders = $stmt->fetchAll();

  echo json_encode($orders);  
}

function newOrder( $order ) {
  global $database;

  // error_log('!!!!! order: '.print_r($order,true));

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
  tariff,
  tariff_name,
  commission_percent,
  price_start,
  price_end,
  commission,
  commission_paid,
  not_to_call,
  wait,
  not_to_smoke,
  childish_armchair,
  state,
  accept_time_secs,
  accept_time_limit_secs,
  canceled_by_passenger,
  canceled_by_bombila,
  cancellation_reason_on_passenger_side,
  cancellation_reason_on_bombila_side,
  rated_by_passenger,
  rated_by_bombila,
  order_date,
  finish_date
)
VALUES( 
  :passenger, 
  :from_latitude, 
  :from_longitude, 
  :from_address, 
  :to_latitude,
  :to_longitude,
  :to_address,
  :passengers_comment,
  :payment_method,
  :tariff,
  :tariff_name,
  :commission_percent,
  :price_start,
  :price_end,
  :commission,
  :commission_paid,
  :not_to_call,
  :wait,
  :not_to_smoke,
  :childish_armchair,
  :state,
  :accept_time_secs,
  :accept_time_limit_secs,
  :canceled_by_passenger,
  :canceled_by_bombila,
  :cancellation_reason_on_passenger_side,
  :cancellation_reason_on_bombila_side,  
  :rated_by_passenger,
  :rated_by_bombila,
  NOW(),
  NULL  
)
";

  // error_log('!!! order: '.print_r($order,true) );

  $order['state'] = 'new';
  $order['commission_percent'] = NULL;
  $order['price_end'] = NULL;
  $order['commission'] = NULL;  
  $order['commission_paid'] = 0;
  $order['accept_time_secs'] = 0;
  $order['accept_time_limit_secs'] = 0;
  $order['canceled_by_passenger'] = 0;
  $order['canceled_by_bombila'] = 0;
  $order['cancellation_reason_on_passenger_side'] = 0;
  $order['cancellation_reason_on_bombila_side'] = 0;
  $order['rated_by_passenger'] = 0;  
  $order['rated_by_bombila'] = 0;  

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':passenger',                             $order['passenger']);
  $stmt->bindParam(':from_latitude',                         $order['from_latitude']);
  $stmt->bindParam(':from_longitude',                        $order['from_longitude']);
  $stmt->bindParam(':from_address',                          $order['from_address']);
  $stmt->bindParam(':to_latitude',                           $order['to_latitude']);
  $stmt->bindParam(':to_longitude',                          $order['to_longitude']);
  $stmt->bindParam(':to_address',                            $order['to_address']);
  $stmt->bindParam(':passengers_comment',                    $order['passengers_comment']);
  $stmt->bindParam(':payment_method',                        $order['payment_method']);
  $stmt->bindParam(':tariff',                                $order['tariff']);
  $stmt->bindParam(':tariff_name',                           $order['tariff_name']);
  $stmt->bindParam(':commission_percent',                    $order['commission_percent']);
  $stmt->bindParam(':price_start',                           $order['price_start']);
  $stmt->bindParam(':price_end',                             $order['price_end']);
  $stmt->bindParam(':commission',                            $order['commission']);
  $stmt->bindParam(':commission_paid',                       $order['commission_paid']);
  $stmt->bindParam(':not_to_call',                           $order['not_to_call']);
  $stmt->bindParam(':wait',                                  $order['wait']);
  $stmt->bindParam(':not_to_smoke',                          $order['not_to_smoke']);
  $stmt->bindParam(':childish_armchair',                     $order['childish_armchair']);
  $stmt->bindParam(':state',                                 $order['state']);
  $stmt->bindParam(':accept_time_secs',                      $order['accept_time_secs']);
  $stmt->bindParam(':accept_time_limit_secs',                $order['accept_time_limit_secs']);
  $stmt->bindParam(':canceled_by_passenger',                 $order['canceled_by_passenger']);
  $stmt->bindParam(':canceled_by_bombila',                   $order['canceled_by_bombila']);
  $stmt->bindParam(':cancellation_reason_on_passenger_side', $order['cancellation_reason_on_passenger_side']);
  $stmt->bindParam(':cancellation_reason_on_bombila_side',   $order['cancellation_reason_on_bombila_side']);
  $stmt->bindParam(':rated_by_passenger',                    $order['rated_by_passenger']);
  $stmt->bindParam(':rated_by_bombila',                      $order['rated_by_bombila']);

  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log("error: order from user=".$order['passenger']." insert error: ".$stmt->errorInfo()[2]);  
    echo '';
    exit(0);     
  }

  $order['id'] = $database->lastInsertId();

  $response['order_id'] = $order['id'];

  echo json_encode( $response );
}

function getOrderState( $phone, $order_id ) {
  global $database;

  $query = "SELECT * from orders where id=:order_id and passenger=:phone limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $stmt->execute();  

  // error_log('!!! $query'.$query);

  $row = $stmt->fetch();

  $response;

  $response['state'] = $row['state'];
  $response['bombila'] = $row['bombila'];  

  echo json_encode($response);
}

function searchBombila( $phone, $order_id ) {
  global $database;

  $query = "UPDATE orders set state='search_again' where id=:order_id and passenger=:phone limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }  
}

function passengerCancelOrderSoft( $phone, $order_id ) {
  global $database;

  $query = "UPDATE orders set state='canceled_before_accepted' where id=:order_id and passenger=:phone limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders was not deleted : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }    
}

function acceptOrder( $phone, $order_id ) {
  global $database;

  $database->beginTransaction();

  $query = "UPDATE orders set bombila=:phone, state='accepted' where id=:order_id limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':order_id', $order_id);  
  $result = $stmt->execute();  

  if( !$result ) {
    $database->rollback();
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO bombilas_accepted_orders(bombila, order_id, accepted) 
VALUES(:phone, :order_id, :accepted)
ON DUPLICATE KEY update accepted=:accepted 
";

  $accepted = 1;

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':accepted', $accepted);
  $result = $stmt->execute();    

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log("error: bombilas_accepted_orders data can not be inserted");
    echo '';
    exit(0);     
  }

  $database->commit();   

  echo '';
}

function declineOrder( $phone, $order_id ) {
  global $database;

  $database->beginTransaction();  

  $query = 
"UPDATE orders 
set 
  state='declined', 
  bombila=:phone, 
  accept_time_secs=0, 
  accept_time_limit_secs=0 
where 
  id=:order_id
limit 1
  ";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute();    

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO bombilas_accepted_orders(bombila, order_id, accepted) 
VALUES(:phone, :order_id, :accepted)
ON DUPLICATE KEY UPDATE accepted=:accepted 
";

  $accepted = 0;

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':accepted', $accepted);
  $result = $stmt->execute();      

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log("error: bombilas_accepted_orders data can not be inserted");
    echo '';
    exit(0);     
  }

  $database->commit();  

  echo '';  
}

function hereOrder( $phone, $order_id ) {
  global $database;

  $database->beginTransaction();  

  $query = "UPDATE orders set state='here' where id=:order_id and bombila=:phone limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute();   

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2]  );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "here";
  $state = "here";
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();    

  if( !$result ) {
    $database->rollback();  
    http_response_code(500);
    error_log( "error: order sync data can not be inserted".$stmt->errorInfo()[2] );
    echo '';
    exit(0);     
  }  

  $database->commit();  

  echo '';
}


function goOrder( $phone, $order_id, $waiting_price ) {
  global $database;

  $database->beginTransaction();  

  $query = "UPDATE orders set state='in_way' where id=:order_id and bombila=:phone limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute(); 

  if( !$result ) {
    $database->rollback(); 
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "in_way";
  $state = "in_way";
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();   

  if( !$result ) {
    $database->rollback();   
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "sync_waiting_price";
  $state = $waiting_price;
  $now = time();  

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();     

  if( !$result ) {
    $database->rollback();      
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  $database->commit(); 

  echo '';
}

function reachedOrder( $phone, $order_id ) {
  global $database;

  $database->beginTransaction();  

  $query = "UPDATE orders set state='reached' where id=:order_id and bombila=:phone limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();   

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "reached";
  $state = "reached";
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();  

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  $database->commit();   

  echo '';  
}

function forPaymentOrder( $phone, $order_id, $price_end ) {
  global $database;

  $database->beginTransaction();  

  $query = 
"UPDATE orders 
set 
  state='for_payment',
  price_end=:price_end
where 
  id=:order_id and 
  bombila=:phone 
limit 1"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':price_end', $price_end);
  $result = $stmt->execute();   

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "for_payment";
  $state = "for_payment";
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();  

  if( !$result ) {
    $database->rollback();    
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }    

  $database->commit();   

  echo '';
}

function finishOrder( $phone, $order_id ) {
  global $database;

  $database->beginTransaction();  

  $query = "UPDATE orders set state='finished', finish_date=NOW() where id=:order_id and bombila=:phone"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();   

  if( !$result ) {
    $database->rollback();   
    http_response_code(500);
    error_log( "error: orders status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "finished";
  $state = "finished";
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();    

  if( !$result ) {
    $database->rollback();      
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
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
  id = :order_id and 
  passenger = :phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();  

  if( !$result ) {
    $database->rollback();  
    http_response_code(500);
    error_log( "error: trip history record can not be inserted : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $database->commit();

  echo '';  
}

function passengerCancelOrderHard( $phone, $order_id, $reason_side ) {
  global $database;

  $database->beginTransaction();   

  $cancellation_reason_on_passenger_side = $reason_side == 'passenger' ? 1 : 0;
  $cancellation_reason_on_bombila_side = $reason_side == 'bombila' ? 1 : 0;  

  $query = 
"UPDATE orders 
set 
  state='canceled', 
  finish_date=NOW(), 
  canceled_by_passenger=1,
  cancellation_reason_on_passenger_side=:cancellation_reason_on_passenger_side,
  cancellation_reason_on_bombila_side=:cancellation_reason_on_bombila_side  
where 
  id=:order_id and passenger=:phone
"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':cancellation_reason_on_passenger_side', $cancellation_reason_on_passenger_side);
  $stmt->bindParam(':cancellation_reason_on_bombila_side', $cancellation_reason_on_bombila_side);  
  $result = $stmt->execute();  

  if( !$result ) {
    $database->rollback();     
    http_response_code(500);
    error_log( "error: order was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }    

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "canceled_by_passenger";
  $state = $reason_side;
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();    

  if( !$result ) {
    $database->rollback(); 
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  $database->commit();

  echo '';
}

function bombilaCancelOrderHard( $phone, $order_id, $reason_side ) {
  global $database;

  $database->beginTransaction();

  $cancellation_reason_on_passenger_side = $reason_side == 'passenger' ? 1 : 0;
  $cancellation_reason_on_bombila_side = $reason_side == 'bombila' ? 1 : 0;

  $query = 
"UPDATE orders 
set 
  state='canceled', 
  finish_date=NOW(), 
  canceled_by_bombila=1,
  cancellation_reason_on_passenger_side=:cancellation_reason_on_passenger_side,
  cancellation_reason_on_bombila_side=:cancellation_reason_on_bombila_side
where 
  id=:order_id and bombila=:phone
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':cancellation_reason_on_passenger_side', $cancellation_reason_on_passenger_side);
  $stmt->bindParam(':cancellation_reason_on_bombila_side', $cancellation_reason_on_bombila_side);
  $result = $stmt->execute();

  if( !$result ) {
    $database->rollback();     
    http_response_code(500);
    error_log( "error: order was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }  

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
VALUES(:order_id, :reason, :state, :now)
";

  $reason = "canceled_by_bombila";
  $state = $reason_side;
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':reason', $reason);
  $stmt->bindParam(':state', $state);
  $stmt->bindParam(':now', $now);
  $result = $stmt->execute();

  if( !$result ) {
    $database->rollback();
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  $database->commit();

  echo '';
}

function sendBombilaCardOnPassengerOrderCancelHard( $phone, $order_id, $card_number ) {
  global $database;

  $query = 
"UPDATE orders_sync set state=:card_number 
where order_id=:order_id and reason='canceled_by_passenger' and order_id in ( 
  select id from orders where id=:order_id and bombila=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':card_number', $card_number);
  $result = $stmt->execute();

  echo '';
}

function sendPaymentResultOnPassengerOrderCancelHard( $phone, $order_id, $payment_result ) {
  global $database;

  $query = 
"UPDATE orders_sync set state=:payment_result 
where order_id=:order_id and reason='canceled_by_passenger' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':payment_result', $payment_result);
  $result = $stmt->execute();

  echo '';
}

function getSuggestedOrder( $phone ) {
  global $database;

  $query = "SELECT * from orders where bombila=:phone and state='suggested' limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();
  
  $order = $stmt->fetch();

  if( $order ) {

    $order['not_to_call'] = boolval( $order['not_to_call'] );
    $order['wait'] = boolval( $order['wait'] );
    $order['not_to_smoke'] = boolval( $order['not_to_smoke'] );
    $order['childish_armchair'] = boolval( $order['childish_armchair'] );  

    echo json_encode( $order );    
  }
  else {
    http_response_code(404);
    echo '';    
  }
}

function sendBombilaLocation( $data ) {
  global $database;

  $query = 
"INSERT INTO 
bombilas_locations 
(
  phone, 
  latitude, 
  longitude, 
  payment_methods, 
  childish_armchair,
  state,
  update_time
) 
VALUES 
(
  :phone, 
  :latitude, 
  :longitude, 
  :payment_methods, 
  :childish_armchair,
  :state,
  :update_time
)
ON DUPLICATE KEY UPDATE

latitude=:latitude, 
longitude=:longitude, 
payment_methods=:payment_methods, 
childish_armchair=:childish_armchair,
state=:state,
update_time=:update_time
";

  $data['update_time'] = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone',             $data['phone']);
  $stmt->bindParam(':latitude',          $data['latitude']);
  $stmt->bindParam(':longitude',         $data['longitude']);
  $stmt->bindParam(':payment_methods',   $data['payment_methods']);
  $stmt->bindParam(':childish_armchair', $data['childish_armchair']);
  $stmt->bindParam(':state',             $data['state']);
  $stmt->bindParam(':update_time',       $data['update_time']);

  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log("error: couldn't update bombila location".$stmt->errorInfo()[2]);  
    echo '';
    exit(0);     
  }
}

function deleteBombilaLocation( $phone ) {
  global $database;

  $query = "DELETE FROM bombilas_locations where phone=:phone";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  if( !$result ) {
    http_response_code(500);
    error_log( "error: orders was not deleted : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }      

  echo '';  
}

// не используется
function getBombilasLocations() {
  global $database;

  $query = "SELECT * from bombilas_locations";

  $stmt = $database->prepare( $query );
  $stmt->execute();
  
  $locations = $stmt->fetchAll();

  echo json_encode( $locations );
}

function getBombilaLocation( $phone ) {
  global $database;

  $query = "SELECT * from bombilas_locations where phone=:phone limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $location = $stmt->fetch();

  if( !$location ) {
    http_response_code(404);
    echo '';
  }
  else {
    echo json_encode( $location ); 
  }    
}

function getNearBombilasLocations( $phone, $latitude, $longitude ) {
  global $database;

  $query = 
"SELECT * FROM 
  (
  SELECT 
     *, 
     111.11 *
      ROUND(DEGREES(ACOS(LEAST(COS(RADIANS(b.latitude))
           * COS(RADIANS( :latitude ))
           * COS(RADIANS(b.longitude - :longitude ))
           + SIN(RADIANS(b.latitude))
           * SIN(RADIANS( :latitude )), 1.0))), 2) AS distance_in_km
  FROM bombilas_locations as b
  WHERE 
    b.phone <> :phone and 
    b.state <> 'in_action' 
  LIMIT 100    
  ) t
WHERE t.distance_in_km <= 1000";  

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':latitude', $latitude);
  $stmt->bindParam(':longitude', $longitude);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  $locations = $stmt->fetchAll();

  echo json_encode( $locations );
}

function getTripsHistory( $phone ) {
  global $database;

  $query = "SELECT id, from_address, to_address from orders where passenger=:phone order by id desc";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();  

  $orders = $stmt->fetchAll();

  echo json_encode( $orders );  
}

function getPhotoControlBombilas( $state ) {
  global $database;

  if( $state == 'all' )
    $query = "SELECT * from users as u where phone in ( select user from photocontrol )";
  else
    $query = "SELECT * from users as u where phone in ( select user from photocontrol where state=:state )";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':state', $state);
  $stmt->execute();  

  $bombilas = $stmt->fetchAll();

  echo json_encode( $bombilas );    
}

function getPhotoControlData( $phone ) {
  global $database;

  $query = "SELECT * from photocontrol as u where user=:phone limit 3";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->execute();    

  $datas = $stmt->fetchAll();

  echo json_encode( $datas );      
}

function approvePhotoControlPhoto( $phone, $type ) {
  global $database;

  $query = "UPDATE photocontrol set state='approved',reason='' where user=:phone and type=:type"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':type', $type);
  $result = $stmt->execute();

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  $response = getPhotoControlApprovalStateReturn( $phone );

  if( $response['state'] == '' )

  echo '';  
}

function declinePhotoControlPhoto( $phone, $type, $reason ) {
  global $database;

  $query = "UPDATE photocontrol set state='not_approved',reason=:reason where user=:phone and type=:type"; 

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':reason', $reason);
  $result = $stmt->execute();

  if( !$result ) {
    http_response_code(500);
    error_log( "error: photocontrol status was not updated : ".$stmt->errorInfo()[2] );
    echo '';
    exit(0);
  }

  sendPushNotification( $phone, 'photocontrol_not_passed', '' );

  echo '';
}

function searchBombilasForPhotoControl( $text ) {
  global $database;

  $text = '%'.$text.'%';

  $query = 
"SELECT * from users 
where phone in ( select user from photocontrol ) and
( phone like :text or firstname like :text or lastname like :text )
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':text', $text);
  $result = $stmt->execute();

  $bombilas = $stmt->fetchAll();

  echo json_encode( $bombilas );      
}

function getUserAgreement() {
  global $database;

  $query = "SELECT data from user_agreement limit 1";

  $stmt = $database->prepare( $query );
  $result = $stmt->execute();

  $row = $stmt->fetch();
  $response = $row['data'];

  header("Content-Type: text/html; charset=UTF-8");
  echo $response;
}

function saveUserAgreement( $text ) {
  global $database;

  $query = "UPDATE user_agreement set data = :text";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':text', $text);
  $result = $stmt->execute();

  if( !$result ) {
    http_response_code(500);
    error_log( "error: user_agreement was not updated : ".$stmt->errorInfo()[2] );    
    echo '';
  }
  else
    echo '';
}

function getTariffs() {
  global $database;

  $query = "SELECT * from tariffs";

  $stmt = $database->prepare( $query );
  $result = $stmt->execute();

  $tariffs = $stmt->fetchAll();

  echo json_encode( $tariffs );
}

function getTariff( $name ) {
  global $database;

  $query = "SELECT * from tariffs where name = :name limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':name', $name);
  $result = $stmt->execute();

  $tariff = $stmt->fetch();

  echo json_encode( $tariff );
}

function saveTariff( $name, $value ) {
  global $database;

  // error_log('!!! name'.$name);
  // error_log('!!! value'.$value);

  $query = "UPDATE tariffs set value = :value where name = :name";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':value', $value);
  $result = $stmt->execute();

  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';
}

function getChatLastMessageNumber( $from_user, $to_user ) {
  global $database;

  $query = 
"SELECT 
  COALESCE( max(number), 0 ) as 'number' 
  from chats 
  where 
    (from_user=:from_user and to_user=:to_user ) or 
    (from_user=:to_user and to_user=:from_user)
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':to_user', $to_user);
  $result = $stmt->execute();

  $row = $stmt->fetch();

  $output['last_number'] = intval( $row['number'] );

  echo json_encode( $output );
}

function sendChatMessage( $sender_type, $from_user, $to_user, $message, $last_number ) {
  global $database;

  $database->beginTransaction();

  $time = time();
  $number = 0;

  $query = 
"SELECT 
  COALESCE( max(number), 0 ) as 'number' 
  from chats 
  where 
    (from_user=:from_user and to_user=:to_user) or 
    (from_user=:to_user and to_user=:from_user)
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':to_user', $to_user);
  $result = $stmt->execute();

  $row = $stmt->fetch();
  $number = intval( $row['number'] );

  $number += 1;

  // error_log('!!! number: '.$number);

  $query = 
"INSERT INTO chats
(
  from_user, 
  to_user, 
  message, 
  time, 
  number
) 
VALUES
(
  :from_user, 
  :to_user, 
  :message, 
  :time, 
  :number
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':to_user', $to_user);
  $stmt->bindParam(':message', $message);
  $stmt->bindParam(':time', $time);
  $stmt->bindParam(':number', $number);
  $result = $stmt->execute();

  if( !$result ) {
    $database->rollback();  
    http_response_code(500);
    error_log("error: chat message can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }

  if( $to_user != 'dispatcher' ) {

    if( $sender_type == 'dispatcher' )
        sendPushNotification( $to_user, 'dispatcher_message', $message );

    else {

      if( $sender_type == 'passenger' )
        sendPushNotification( $to_user, 'passenger_message', $message );

      else if( $sender_type == 'bombila' )
        sendPushNotification( $to_user, 'bombila_message', $message );         
    }
  }

  $query = 
"SELECT
  c.from_user,
  c.to_user,
  case 
    when c.from_user <> 'dispatcher' 
      then concat(u1.firstname,' ',u1.lastname) 
      else 'Оператор поддержки'
  end as 'from_fullname',
  c.message,
  c.number 
FROM chats as c 
inner join users as u1 on c.from_user = u1.phone 
inner join users as u2 on c.to_user = u2.phone 
where 
  (( from_user=:from_user and to_user=:to_user ) or ( from_user=:to_user and to_user=:from_user )) 
  and c.number > :last_number 
order by c.number asc";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':to_user', $to_user);
  $stmt->bindParam(':last_number', $last_number);
  $result = $stmt->execute();

  $messages = $stmt->fetchAll();

  $database->commit();

  echo json_encode($messages);     
}

function sendChatMessageIsRead( $from_user, $to_user, $number ) {
  global $database;

  $query = "UPDATE chats set is_read = 1 where from_user=:from_user and to_user=:to_user and number=:number";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':to_user', $to_user);
  $stmt->bindParam(':number', $number);
  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';  
}

function getChatsForDispatcher() {
  global $database;

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

  $stmt = $database->prepare( $query );
  $stmt->execute();  

  $datas = $stmt->fetchAll();

  echo json_encode( $datas );     
}

function setAllMessagesRead( $from_user, $to_user ) {
  global $database;

  $query = "UPDATE chats set is_read = 1 where from_user=:from_user and to_user=:to_user";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':to_user', $to_user);
  $result = $stmt->execute(); 

  if( !$result ) {
    http_response_code(404);
    echo '';
  }
  else
    echo '';   
}

function getNewChatMessages( $phone1, $phone2, $last_number ) {
  global $database;

  $query = 
"SELECT 
  c.from_user,
  c.to_user,
  case 
    when c.from_user <> 'dispatcher' 
      then concat(u1.firstname,' ',u1.lastname) 
      else 'Оператор поддержки'
  end as 'from_fullname',
  c.message,
  c.number 
FROM chats as c 
inner join users as u1 on c.from_user = u1.phone 
inner join users as u2 on c.to_user = u2.phone 
where 
  (( from_user=:phone1 and to_user=:phone2 ) or ( from_user=:phone2 and to_user=:phone1 )) 
  and c.number > :last_number 
order by c.number asc";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone1', $phone1);
  $stmt->bindParam(':phone2', $phone2);
  $stmt->bindParam(':last_number', $last_number);
  $result = $stmt->execute(); 

  $messages = $stmt->fetchAll();

  echo json_encode($messages);    
}

function getNewChatsMessages( $chats ) {
  global $database;

  // error_log('!!!! chats: '. print_r($chats,true) );

  $database->beginTransaction();

  $output = array();

  $chat;

  for($i=0;$i<count( $chats );$i++) {

    $chat = $chats[$i];

    $phone1 = 'dispatcher';
    $phone2 = $chat['from_user'];
    $last_number = $chat['last_number'];

    if( $last_number === 'undefined' )
      continue;

    $query = 
"SELECT 
  c.from_user,
  c.to_user,
  case 
    when c.from_user <> 'dispatcher' 
      then concat(u1.firstname,' ',u1.lastname) 
      else 'Оператор поддержки'
  end as 'from_fullname',
  c.message,
  c.number 
FROM chats as c 
inner join users as u1 on c.from_user = u1.phone 
inner join users as u2 on c.to_user = u2.phone 
where 
  (( from_user=:phone1 and to_user=:phone2 ) or ( from_user=:phone2 and to_user=:phone1 )) 
  and c.number > :last_number 
order by c.number asc";

    $stmt = $database->prepare( $query );
    $stmt->bindParam(':phone1', $phone1);
    $stmt->bindParam(':phone2', $phone2);
    $stmt->bindParam(':last_number', $last_number);
    $result = $stmt->execute(); 

    $messages = $stmt->fetchAll();

    if( count($messages) > 0 )
      $last_number = intval( $messages[count($messages)-1]['number'] );

    $output_row['from_user'] = $chat['from_user'];
    $output_row['last_number'] = $last_number;
    $output_row['messages'] = $messages;      



    $output[] = $output_row;
  }

  $database->commit();  

  echo json_encode($output);  
}

function addMarkToBombila( $from_user, $to_user, $order_id, $mark, $review ) {
  global $database;

  $database->beginTransaction(); 

  $query = 
"INSERT into users_reviews( to_user, from_user, mark, review ) 
  select :to_user, :from_user, :mark, :review
  from orders
  where id=:order_id and rated_by_passenger=0 
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':to_user', $to_user);
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':mark', $mark);
  $stmt->bindParam(':review', $review);
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute();

  if( !$result ) {
    $database->rollback();  
    http_response_code(500);
    error_log("error: mark can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  $query = "UPDATE orders set rated_by_passenger=1 where id=:order_id limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute();

  if( !$result ) {
    $database->rollback();  
    http_response_code(500);
    error_log("error: order was not updated".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }    

  $query = 
"DELETE from orders_sync 
where order_id in ( 
  select id from orders 
  where id=:order_id and rated_by_passenger=1 and rated_by_bombila=1
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute();

  $database->commit();

  echo '';
}

function addMarkToPassenger( $from_user, $to_user, $order_id, $mark, $review ) {
  global $database;

  $database->beginTransaction(); 

  $query = 
"INSERT into users_reviews( to_user, from_user, mark, review ) 
  select :to_user, :from_user, :mark, :review
  from orders
  where id=:order_id and rated_by_bombila=0 
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':to_user', $to_user);
  $stmt->bindParam(':from_user', $from_user);
  $stmt->bindParam(':mark', $mark);
  $stmt->bindParam(':review', $review);
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute();

  if( !$result ) {
    $database->rollback();  
    http_response_code(500);
    error_log("error: mark can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }    

  $query = "UPDATE orders set rated_by_bombila=1 where id=:order_id limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute();

  if( !$result ) {
    $database->rollback();  
    http_response_code(500);
    error_log("error: order was not updated".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }     

  $query = 
"DELETE from orders_sync 
where order_id in ( 
  select id from orders 
  where id=:order_id and rated_by_passenger=1 and rated_by_bombila=1
) 
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $result = $stmt->execute(); 

  $database->commit();

  echo '';
}

function getOrderSyncData( $phone, $order_id ) {
  global $database;

  $query = 
"SELECT os.order_id, os.reason, os.state 
from orders_sync as os
inner join orders as o on ( os.order_id = o.id )
where 
  os.order_id = :order_id and
  ( o.passenger = :phone or o.bombila = :phone )
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute(); 

  $output = $stmt->fetchAll();

  echo json_encode($output);    
}

function requestPassengerRoute( $phone, $order_id ) {
  global $database;  

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
select :order_id as order_id, :reason as reason, :state as state, :now as sync_time
from orders
where bombila=:phone and id=:order_id
ON DUPLICATE KEY UPDATE state=:state, sync_time=:now
";

  $reason = "passenger_route";
  $state = 'request';
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':reason', $reason);  
  $stmt->bindParam(':state', $state);  
  $stmt->bindParam(':now', $now);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();   

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  echo '';  
}

function confirmPassengerRoute( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='confirmed' 
where order_id=:order_id and reason='passenger_route' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)
";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();   

  echo '';
}

function declinePassengerRoute( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='declined' 
where order_id=:order_id and reason='passenger_route' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();   

  echo '';
}

function timeoutPassengerRoute( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='timeout' 
where order_id=:order_id and reason='passenger_route' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();   

  echo '';
}

function syncPricePassenger( $phone, $order_id, $price ) {
  global $database;

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
select :order_id as order_id, :reason as reason, :state as state, :now as sync_time
from orders
where passenger=:phone and id=:order_id
ON DUPLICATE KEY UPDATE order_id=order_id, sync_time=:now
";

  $reason = "sync_price_passenger";
  $state = $price;
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $stmt->bindParam(':reason', $reason);  
  $stmt->bindParam(':state', $state);  
  $stmt->bindParam(':now', $now);  
  $result = $stmt->execute();    

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  echo '';  
}

function syncPriceBombila( $phone, $order_id, $price ) {
  global $database;

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
select :order_id as order_id, :reason as reason, :state as state, :now as sync_time
from orders
where bombila=:phone and id=:order_id
ON DUPLICATE KEY UPDATE order_id=order_id, sync_time=:now
";

  $reason = "sync_price_bombila";
  $state = $price;
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $stmt->bindParam(':reason', $reason);  
  $stmt->bindParam(':state', $state);  
  $stmt->bindParam(':now', $now);  
  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  echo '';  
}

function requestPriceOffer( $phone, $order_id, $price ) {
  global $database;

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
select :order_id as order_id, :reason as reason, :state as state, :now as sync_time
from orders
where bombila=:phone and id=:order_id
ON DUPLICATE KEY UPDATE state=:state, sync_time=:now
";

  $reason = "price_offer";
  $state = $price;
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $stmt->bindParam(':reason', $reason);  
  $stmt->bindParam(':state', $state);  
  $stmt->bindParam(':now', $now);  
  $result = $stmt->execute();   

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  echo '';  
}

function confirmPriceOffer( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='confirmed' 
where order_id=:order_id and reason='price_offer' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();  

  echo '';
}

function declinePriceOffer( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='declined' 
where order_id=:order_id and reason='price_offer' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();  

  echo ''; 
}

function timeoutPriceOffer( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='timeout' 
where order_id=:order_id and reason='price_offer' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();  

  echo ''; 
}

function requestPassengerPayment( $phone, $order_id, $bombila_card ) {
  global $database;

  $query = 
"INSERT INTO orders_sync(order_id, reason, state, sync_time) 
select :order_id as order_id, :reason as reason, :state as state, :now as sync_time
from orders
where bombila=:phone and id=:order_id
ON DUPLICATE KEY UPDATE state=:state, sync_time=:now
";

  $reason = "card_payment";
  $state = $bombila_card;
  $now = time();

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $stmt->bindParam(':reason', $reason);  
  $stmt->bindParam(':state', $state);  
  $stmt->bindParam(':now', $now);  
  $result = $stmt->execute();  

  if( !$result ) {
    http_response_code(500);
    error_log("error: order sync data can not be inserted".$stmt->errorInfo()[2]);
    echo '';
    exit(0);     
  }  

  echo '';    
}

function passengerPaymentPassed( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='passed' 
where order_id=:order_id and reason='card_payment' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();  

  echo '';
}

function passengerPaymentNotPassed( $phone, $order_id ) {
  global $database;

  $query = 
"UPDATE orders_sync set state='not_passed' 
where order_id=:order_id and reason='card_payment' and order_id in ( 
  select id from orders where id=:order_id and passenger=:phone 
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();  

  echo '';
}

function cleanUpOrderSyncDataOnCancel( $phone, $order_id ) {
  global $database;

  $query = 
"DELETE from orders_sync 
where order_id in ( 
  select id from orders 
  where 
    id=:order_id and 
    ( passenger=:phone or bombila=:phone ) and 
    state='canceled'
)";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $result = $stmt->execute();

  echo '';
}

function sendPushNotification( $phone, $event, $ext ) {
  global $database;

  $query = "SELECT token from push_tokens where user=:phone";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute();  

  $row = $stmt->fetch();
  $push_token = $row['token'];

  // error_log('!!!!! sendPushNotification token: '.$push_token);

  if( !$push_token ) {
    http_response_code(400);
    echo '';
    exit(0);
  }

  $send_data;

  $send_data['to'] = $push_token;
  // $send_data['notification']['title'] = $event;
  // $send_data['notification']['body'] = $ext;
  $send_data['priority'] = 'high';
  $send_data['data']['event'] = $event;
  $send_data['data']['ext'] = $ext;
  $send_data['time_to_live'] = 10 * 60; // secs - 10 min

  $headers = array (
    'Authorization: key=AAAAzV5u8bw:APA91bH3LswNIzDqKpTb_fP-LDFxzkTR6Qm0Z6h9a0W56OvjrgpMQnsvdGNf1Bj5rgsS8K8E0RYljEICcQ-n_kGOgrNcWIW6tkyDrxkxbTuUd42PrByRw-bdA-zbxCNZA-oexe7sVLIb',
    'Content-Type: application/json'
  );  

  $curl = curl_init();
  curl_setopt( $curl, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send ' );
  curl_setopt( $curl, CURLOPT_POST, true );
  curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
  curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $send_data ) );
  $result = curl_exec( $curl );
  curl_close( $curl );  

  echo '';
}

function payNegativeCommission( $phone, $order_id, $bombila_card_number, $sum ) {
  global $database;

/*

https://bartercoin.holding.bz/api_phone/pay_bombila.php

token = md5(“num1”.”month1”.”year1”.”cvc1”.”secret”.”round((float)sum,0)”.”num2”); //md5(конкатенация в строку)
*/

/*
send_json

  {
  “num1”: “системный номер карты",
  “month1”: “месяц”,
  “year1”: “год”,
  “cvc1”: “cvc”,
  “num2”: “номер карты водителя”,
  “sum”: “сумма оплаты без копеек”, 
  “token”: token
} */

/*

card_data

1000885577611546
12/20
410

*/

  $query = 
"SELECT * from orders 
where 
  id=:order_id and 
  bombila=:phone and 
  state='for_payment' and 
  commission_paid=0 
limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);  
  $stmt->bindParam(':phone', $phone);  
  $result = $stmt->execute(); 

  $orders = $stmt->fetchAll();

  if( count( $orders ) == 0 ) {
    http_response_code(404);
    echo '';
    exit(0);    
  }

  $send_data;

  $num1 = '1000885577611546';
  $month1 = '12';
  $year1 = '20';
  $cvc1 = '410';
  $num2 = $bombila_card_number;
  $sum = intval( round( (float)$sum, 0 ) );
  $secret = '98jsHF8fhcGDJ7';

  $send_data['num1'] = $num1;
  $send_data['month1'] = $month1;
  $send_data['year1'] = $year1;
  $send_data['cvc1'] = $cvc1;
  $send_data['num2'] = $num2;
  $send_data['sum'] = $sum;
  $send_data['token'] = md5( $num1.$month1.$year1.$cvc1.$secret.$sum.$num2 );

//  token = md5(“num1”.”month1”.”year1”.”cvc1”.”secret”.”round((float)sum,0)”.”num2”); //md5(конкатенация в строку)

  $headers = array (
    'Content-Type: application/json'
  );    

  $curl = curl_init();
  curl_setopt( $curl, CURLOPT_URL, 'https://bartercoin.holding.bz/api_pay/pay_bombila.php' );
  curl_setopt( $curl, CURLOPT_POST, true );
  curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
  curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $send_data ) );
  $response = curl_exec( $curl ); // json_encoded
  curl_close( $curl );

  error_log("!!! response: ".$response);

  echo $response;
}

function getSystemBarterCoinCardNumber() {

  $response['num'] = '1000885577611546';
  echo json_encode( $response );  
}

function updateOrderComissionData( $phone, $order_id, $percent, $sum ) {
  global $database;  

  $query = 
"UPDATE orders
set 
  commission_percent=:commission_percent,
  commission=:commission,
  commission_paid=:commission_paid
where
  bombila=:phone and
  id=:order_id and
  state = 'for_payment'
limit 1
";

  $commission_paid = 1;

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':order_id', $order_id);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':commission_percent', $percent);
  $stmt->bindParam(':commission', $sum);
  $stmt->bindParam(':commission_paid', $commission_paid);
  $result = $stmt->execute();

  echo '';
}

function getSetting( $name ) {
  global $database;

  $query = "SELECT * from settings where name = :name limit 1";

  $stmt = $database->prepare( $query );
  $stmt->bindParam(':name', $name);    
  $result = $stmt->execute();

  $setting = $stmt->fetch();

  echo json_encode( $setting );
}


?>