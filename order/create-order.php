<?php

require '../vendor/autoload.php';
require_once('NonceUtil.php');

use Razorpay\Api\Api;
use Kreait\Firebase\Factory;


ini_set('default_mimetype', 'text/plain');
ini_set('default_charset', 'ISO-8859-1');
define('NONCE_SECRET', 'waitwaitwaitbrocreateorder');
// header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");

// rz 
$key_id = 'rzp_test_z34MFsISPWM3ID';
$secret = 'CEwLD5qaYUYQUBJWXIhKewpg';



function cleanInput( $input, $isINT = false, $max_char = false){
  if($isINT){
    $result = preg_replace('/\D/', '', $input);
    return (int)$result;
  }
  elseif(!$isINT){
      $clean = strtolower( $input);
      $search = "/[^-a-z0-9\\/\\:\\?\\.\\_\\-]+/i"; 
      $subst = "";
      $result = preg_replace($search, $subst, $clean);
      if($max_char){
          $result = substr( $result,0,$max_char);
      }
      return $result;
  }else{
      return false;
  }
}






// security first :)
// $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'] &&
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payAmount'])) {
  $payAmount = cleanInput($_POST['payAmount'], true);
  if(!$payAmount){
    http_response_code(403);
    exit;
  }


  // realtime db auth
  $factory = (new Factory)
    ->withServiceAccount('grambaba-fb-firebase-adminsdk-53qcu-5cffdc1458.json')
    ->withDatabaseUri('https://grambaba-fb-default-rtdb.asia-southeast1.firebasedatabase.app/');

  $auth = $factory->createAuth();
  $realtimeDatabase = $factory->createDatabase();







  // create nonce valid for 6 hours
  $nonce = NonceUtil::generate(NONCE_SECRET, 21600);
  $expNonce = explode(",", $nonce);



  //razorpay generate order id
  $api = new Api($key_id, $secret);
  $order = $api->order->create(array(
    'partial_payment' => false,
    'amount' => $payAmount,
    'currency' => 'INR',
  ));

// generate randon unique string for order id mask
$randStr = substr(bin2hex(random_bytes(20)), 0, 20);
// echo "\n\n {$randStr} \n\n";


  // store all data -- salt and time saved -- send nonce hash value to user order form
  $reference = $realtimeDatabase->getReference("/orderReference/{$randStr}-gb")->set([
    "salt" => $expNonce[0],
    "time" => $expNonce[1],
    "rzorder" => $order->id
  ]);







  // return  rz order Id, order id, nonce hash
  $returnobj = new stdClass();
  $returnobj->rzorder = $order->id;
  $returnobj->orderid = $randStr;
  $returnobj->nonce = $expNonce[2];
  $returnJSON = json_encode($returnobj);

  echo $returnJSON;

} 
else {
  http_response_code(403);
  exit;
}


?>
