<?php 

header("Content-Type: application/json; charset=UTF-8");

$sms_code = generateSmsCode();
$sms_text = 'Бомбила Такси. Код доступа: '.$sms_code;

$sms_response = sms( $_GET['email'], $_GET['password'], $_GET['phone'], $sms_text );

$response['sms_response'] = $sms_response;
$response['sms_code'] = $sms_code ;

echo json_encode( $response );


function generateSmsCode() { 
	$n=4; 	
  $characters = '0123456789'; 
  $randomString = ''; 

  for ($i = 0; $i < $n; $i++) { 
      $index = rand(0, strlen($characters) - 1); 
      $randomString .= $characters[$index]; 
  } 

  return $randomString; 
} 


function _smsapi_communicate($request, $cookie=NULL){

$request['format'] = "json";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://apisys.goip.holding.bz:8080");
curl_setopt($curl, CURLOPT_POST, True);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
if(!is_null($cookie)){
curl_setopt($curl, CURLOPT_COOKIE, $cookie);
}
$data = curl_exec($curl);
curl_close($curl);
if($data === False){
return NULL;
}
$js = json_decode($data, $assoc=True);
if(!isset($js['response'])) return NULL;
$rs = &$js['response'];
if(!isset($rs['msg'])) return NULL;
$msg = &$rs['msg'];
if(!isset($msg['err_code'])) return NULL;
$ec = intval($msg['err_code']);
if(!isset($rs['data'])){ $data = NULL; }else{ $data = $rs['data']; }
return array($ec, $data);

}

function sms($email, $password, $phone, $text, $params = NULL){

$req = array(
"method" => "push_msg",
"api_v"=>"1.1",
"email"=>$email,
"password"=>$password,
"phone"=>$phone,
"text"=>$text);
if(!is_null($params)){
$req = array_merge($req, $params);
}
$resp = _smsapi_communicate($req);
if(is_null($resp)){
// Broken API request
return NULL;
return "";
}
$ec = $resp[0];
if($ec != 0){
return array($ec,);
return "";
}return $resp;
if(!isset($resp[1]['n_raw_sms'])){
return NULL; // No such fields in response while expected
return "";
}
$n_raw_sms = $resp[1]['n_raw_sms'];
return array(0, $n_raw_sms);
return "";

}

?>