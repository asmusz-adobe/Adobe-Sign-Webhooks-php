<?php 
// include functions (file has "prettyPrint" function to format incoming JSON)
include './functions.php';
// All Webhooks contain Adobe Sign REST V6 "agreement ids" and these ARE DIFFERENT than V5 ids

// Set to display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// get header param to variable
$asId = $_SERVER['HTTP_X_ADOBESIGN_CLIENTID'];
// add headers to response for this page
header('Content-Type: application/json');
header('X-AdobeSign-ClientId:'. $asId );

// get JSON from webhook POST request
$webhook = file_get_contents('php://input');
// format wehbook JSON w "prettyPrint" function
$print_webhook = prettyPrint( $webhook );

// get JSON from webhook to object for use in logging
// All Webhooks contain Adobe Sign REST V6 "agreement ids" and these are different from V5
// You can query against V6 using a V5 ID but V6 ids are NOT backwards compatible and calls against V6
//      endpoints will return agreements ids that can only be used for V6
$data = json_decode(file_get_contents('php://input'), true);
// write to log
$fp = fopen('webhook_log.txt', 'a');
fwrite($fp, date('"Y-m-d g:i:s a"') . ', "Event: '. $data['event'] . '", "ID: '. $data['agreement']['id'] . '", "By participantUserEmail: ' . $data['participantUserEmail'] . '"'."\r\n");

// create file path for new JSON file creation
$mkfle =  './evtfiles/' . $data['agreement']['id'] . '_' . $data['event'] . '.json';
echo $mkfle;

// write and close local JSON file with formatted JSON
$newFile = fopen($mkfle,'x+');
fwrite($newFile, $print_webhook);
fclose($newFile);

// done!
echo "Webhook Processed!";
?>
